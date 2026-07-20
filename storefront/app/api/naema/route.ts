import { getCatalog } from "@/lib/catalog";
import { fetchOrderStatus } from "@/lib/hub";
import { SITE } from "@/lib/site";
import {
  classifyIntent,
  scoreProducts,
  type ChatMessage,
  type NaemaAction,
  type NaemaIntent,
  type NaemaReply,
  type NaemaRequest,
  type PageContext,
} from "@/lib/naema";
import type { Product } from "@/lib/products";

/* ============================================================
   Naema AI gateway — the single server-side entry for every AI
   request (advisory §2–3). The browser never talks to Grok or holds
   Hub write-credentials; it POSTs here, and this route:

     • enforces guardrails (size caps, per-session rate limit)
     • runs Naema on Grok with function-calling tools when configured
       (NAEMA_API_KEY set), grounding answers in the live catalog
     • falls back to a deterministic, catalog-grounded orchestrator
       when Grok isn't configured or errors — so the widget always
       works (mirrors lib/hub.ts / lib/lookup.ts "demo mode")
     • returns a validated NaemaReply the frontend renders as UI
     • logs one structured line per turn for observability (§9)

   Configure the model (all server-only, never NEXT_PUBLIC_):
     NAEMA_API_KEY   — bearer token for the OpenAI-compatible endpoint
     NAEMA_API_URL   — base URL (default https://api.x.ai/v1)
     NAEMA_MODEL     — model id (default grok-4)
   ============================================================ */

const AI_KEY = process.env.NAEMA_API_KEY;
const AI_URL = process.env.NAEMA_API_URL || "https://api.x.ai/v1";
const AI_MODEL = process.env.NAEMA_MODEL || "grok-4";

const MAX_MSG_CHARS = 2000;
const MAX_HISTORY = 12;
const RATE_LIMIT = 20; // requests
const RATE_WINDOW_MS = 60_000; // per minute, per session

// WhatsApp deep-link to the shop line (E.164, no + or spaces).
const WA_NUMBER = SITE.phone.replace(/[^\d]/g, "");
const waLink = (text: string) => `https://wa.me/${WA_NUMBER}?text=${encodeURIComponent(text)}`;

/* ---------------- guardrails ---------------- */

// Per-instance token bucket. Fine for a single container; move to Redis/hub
// when the storefront scales horizontally.
const buckets = new Map<string, { count: number; resetAt: number }>();
function rateLimited(sessionId: string): boolean {
  const now = Date.now();
  const b = buckets.get(sessionId);
  if (!b || now > b.resetAt) {
    buckets.set(sessionId, { count: 1, resetAt: now + RATE_WINDOW_MS });
    return false;
  }
  b.count += 1;
  return b.count > RATE_LIMIT;
}

const clampMsg = (m: ChatMessage): ChatMessage => ({
  role: m.role === "assistant" ? "assistant" : "user",
  content: String(m.content ?? "").slice(0, MAX_MSG_CHARS),
});

/* ---------------- tool executors (grounding) ---------------- */

/** Compact product shape the model (and the fallback) reason over. */
function toToolProduct(p: Product) {
  return {
    slug: p.slug,
    name: p.name,
    category: p.category,
    price_kes: p.price,
    price_usd: p.priceUsd,
    made_to_order: Boolean(p.producible),
    in_stock: p.inStock !== false,
    summary: p.short,
  };
}

async function execSearchProducts(query: string): Promise<ReturnType<typeof toToolProduct>[]> {
  const all = await getCatalog();
  return scoreProducts(all, query, 5).map(toToolProduct);
}

async function execGetOrderStatus(paymentToken: string) {
  const status = await fetchOrderStatus(paymentToken);
  if (!status) return { found: false, note: "No live status — ask the customer to use ‘Find my orders’." };
  return { found: true, ...status };
}

/* ---------------- Grok path (OpenAI-compatible function calling) ---------------- */

interface GrokToolCall { id: string; type: "function"; function: { name: string; arguments: string } }
interface GrokMessage {
  role: "system" | "user" | "assistant" | "tool";
  content: string | null;
  tool_calls?: GrokToolCall[];
  tool_call_id?: string;
}

const TOOLS = [
  {
    type: "function",
    function: {
      name: "search_products",
      description: "Search the live Bethany House catalog. Use for any product question. Returns canonical slugs, prices (KES/USD), category and stock. Only recommend products this returns, by their exact slug.",
      parameters: {
        type: "object",
        properties: { query: { type: "string", description: "What the customer is looking for, in their own words." } },
        required: ["query"],
      },
    },
  },
  {
    type: "function",
    function: {
      name: "get_order_status",
      description: "Look up a live order by its payment token. If you don't have a token, do NOT guess — return the find_orders action instead.",
      parameters: {
        type: "object",
        properties: { payment_token: { type: "string" } },
        required: ["payment_token"],
      },
    },
  },
];

function systemPrompt(ctx: PageContext | undefined, locale: string | undefined): string {
  const here = ctx?.productSlug ? `The customer is viewing product "${ctx.productSlug}". ` : ctx?.category ? `The customer is browsing "${ctx.category}". ` : "";
  return [
    `You are Naema, the sales & service agent for ${SITE.name} — ${SITE.tagline}`,
    `Shop: ${SITE.address}, ${SITE.city}. Hours: ${SITE.hours}. Phone/WhatsApp: ${SITE.phone}. Payments: ${SITE.payments}. ${SITE.deliveryPromise}. We ship worldwide.`,
    here + (locale ? `Customer locale: ${locale}. ` : ""),
    "Rules:",
    "- Ground every product, price and stock fact in search_products. NEVER invent prices, availability or shipping figures.",
    "- Prices are KES for Kenya, USD elsewhere. Made-to-order garments need measurements before production.",
    "- For parish/diocese bulk orders, complex quotes, or anything you're unsure of, set handoff.required=true and offer WhatsApp.",
    "- Be warm, concise and commercial. Move the customer toward a clear next step.",
    "Respond with ONLY a JSON object (no prose, no code fences) matching:",
    `{"intent":"greeting|product_inquiry|quote|shipping|order_support|measurement|other","message":"reply to the customer","confidence":0..1,"products":[{"slug":"exact-slug","reason":"why"}],"questions":[{"id":"short_id","label":"a one-tap question"}],"actions":[{"type":"view_product|whatsapp|request_quote|find_orders|shop","label":"button text","value":"slug or url"}],"handoff":{"required":false,"reason":null},"readiness":"low|medium|high"}`,
  ].join("\n");
}

async function grokChat(messages: GrokMessage[], useTools: boolean) {
  const r = await fetch(`${AI_URL}/chat/completions`, {
    method: "POST",
    headers: { "Content-Type": "application/json", Authorization: `Bearer ${AI_KEY}` },
    body: JSON.stringify({
      model: AI_MODEL,
      messages,
      temperature: 0.4,
      ...(useTools ? { tools: TOOLS, tool_choice: "auto" } : { response_format: { type: "json_object" } }),
    }),
  });
  if (!r.ok) throw new Error(`grok ${r.status}`);
  const data = await r.json();
  return data.choices?.[0]?.message as GrokMessage;
}

async function runWithGrok(req: NaemaRequest, toolsUsed: string[]): Promise<NaemaReply> {
  const history: GrokMessage[] = [
    { role: "system", content: systemPrompt(req.pageContext, req.locale) },
    ...req.messages.map((m) => ({ role: m.role, content: m.content } as GrokMessage)),
  ];

  // Retrieval loop — let Naema call tools until it has what it needs.
  for (let i = 0; i < 4; i++) {
    const msg = await grokChat(history, true);
    if (!msg.tool_calls?.length) {
      history.push(msg);
      break;
    }
    history.push(msg);
    for (const call of msg.tool_calls) {
      toolsUsed.push(call.function.name);
      let result: unknown = {};
      try {
        const args = JSON.parse(call.function.arguments || "{}");
        if (call.function.name === "search_products") result = await execSearchProducts(String(args.query ?? ""));
        else if (call.function.name === "get_order_status") result = await execGetOrderStatus(String(args.payment_token ?? ""));
      } catch {
        result = { error: "tool failed" };
      }
      history.push({ role: "tool", tool_call_id: call.id, content: JSON.stringify(result) });
    }
  }

  // Compose the final structured answer (JSON mode, no tools).
  const finalMsg = await grokChat(
    [...history, { role: "user", content: "Now return ONLY the JSON reply object." }],
    false,
  );
  const parsed = safeJson(finalMsg?.content ?? "");
  if (!parsed) return runFallback(req, toolsUsed); // model spoke but not JSON — ground a real answer
  return normalize(parsed, true);
}

/* ---------------- deterministic fallback (always works) ---------------- */

function actionsFor(intent: NaemaIntent, products: Product[], ctx?: PageContext): NaemaAction[] {
  const out: NaemaAction[] = [];
  if (products[0]) out.push({ type: "view_product", label: `View ${products[0].short || products[0].name}`, value: products[0].slug });
  if (intent === "order_support") out.push({ type: "find_orders", label: "Find my orders", value: "/orders" });
  if (intent === "quote") out.push({ type: "request_quote", label: "Request a quote on WhatsApp", value: waLink("Hello Bethany House, I'd like a quotation for our church.") });
  out.push({ type: "whatsapp", label: "Chat on WhatsApp", value: waLink(`Hello Bethany House${ctx?.productSlug ? ` — I'm interested in ${ctx.productSlug}` : ""}.`) });
  return out;
}

// whether the message clearly signals a non-product intent (so a product-page
// visitor asking "how do I ship this?" isn't forced into product_inquiry)
const classifyMatch = (t: string) => classifyIntent(t) !== "product_inquiry";

function shapeText(intent: NaemaIntent, q: string, ctx?: PageContext) {
  switch (intent) {
    case "greeting":
      return {
        intent, confidence: 0.9,
        message: `Hello! I'm Naema, here to help you find the right communion elements, clergy apparel or church gifts — and get them to your church anywhere in the world. What are you looking for today?`,
        questions: [{ id: "cat_communion", label: "Communion elements" }, { id: "cat_clergy", label: "Clergy apparel" }, { id: "cat_gifts", label: "Gifts & accessories" }],
        query: "", readiness: "low" as const, handoff: { required: false },
      };
    case "shipping":
      return {
        intent, confidence: 0.7,
        message: `Yes — we ship worldwide from Nairobi. ${SITE.deliveryPromise}. Tell me your country and city and what you'd like, and I'll guide you to a shipping estimate; for a precise international quote our team finishes it on WhatsApp.`,
        questions: [{ id: "country", label: "Which country?" }, { id: "city", label: "Which city?" }],
        query: q, readiness: "medium" as const, handoff: { required: false },
      };
    case "measurement":
      return {
        intent, confidence: 0.7,
        message: `Happy to help with sizing. Our cassocks, chasubles and gowns are made to order from your measurements (neck, shoulders, sleeves, chest and full length). Open the product and I'll walk you through each measurement before anything goes to production — no guesswork.`,
        questions: [{ id: "which_garment", label: "Which garment?" }, { id: "ready_made", label: "Ready-made sizes?" }],
        query: q, readiness: "medium" as const, handoff: { required: false },
      };
    case "order_support":
      return {
        intent, confidence: 0.75,
        message: `I can help you track an order. Use “Find my orders” to pull your full history with just the phone number or email on the order — no account needed. If it's urgent, our team is on WhatsApp.`,
        questions: [], query: "", readiness: "low" as const, handoff: { required: false },
      };
    case "quote":
      return {
        intent, confidence: 0.8,
        message: `Absolutely — for parishes and dioceses we prepare structured quotations with quantity pricing, consolidated delivery and invoicing. Tell me the items and quantities and I'll start it; our team confirms the final quote on WhatsApp.`,
        questions: [{ id: "items", label: "Which items?" }, { id: "quantity", label: "How many?" }, { id: "city", label: "Deliver to?" }],
        query: q, readiness: "high" as const, handoff: { required: true, reason: "bulk/parish quotation" },
      };
    default:
      return {
        intent: "product_inquiry" as NaemaIntent, confidence: 0.6,
        message: `Here's what I'd recommend from our catalogue. Tap any item for details and pricing, or tell me more about the occasion, denomination or budget and I'll narrow it down.`,
        questions: [{ id: "budget", label: "Set a budget?" }, { id: "occasion", label: "For an occasion?" }],
        query: q || ctx?.category || "", readiness: "medium" as const, handoff: { required: false },
      };
  }
}

async function runFallback(req: NaemaRequest, toolsUsed: string[]): Promise<NaemaReply> {
  const lastUser = [...req.messages].reverse().find((m) => m.role === "user")?.content ?? "";
  const intent: NaemaIntent = req.pageContext?.productSlug && !classifyMatch(lastUser) ? "product_inquiry" : classifyIntent(lastUser);
  const shaped = shapeText(intent, lastUser, req.pageContext);

  // Ground product recommendations in the live catalog.
  let products: Product[] = [];
  const wantsProducts = ["product_inquiry", "quote", "greeting"].includes(shaped.intent);
  if (wantsProducts) {
    toolsUsed.push("search_products");
    const all = await getCatalog();
    const query = shaped.query || req.pageContext?.category || lastUser;
    products = scoreProducts(all, query, shaped.intent === "greeting" ? 3 : 4);
    // If on a product page, make sure that product leads.
    if (req.pageContext?.productSlug) {
      const here = all.find((p) => p.slug === req.pageContext!.productSlug);
      if (here) products = [here, ...products.filter((p) => p.slug !== here.slug)].slice(0, 4);
    }
  }

  return normalize(
    {
      intent: shaped.intent,
      message: shaped.message,
      confidence: shaped.confidence,
      products: products.map((p) => ({ slug: p.slug, reason: p.category })),
      questions: shaped.questions,
      actions: actionsFor(shaped.intent, products, req.pageContext),
      handoff: shaped.handoff,
      readiness: shaped.readiness,
      sources: products.map((p) => ({ type: "catalog" as const, recordId: p.slug })),
    },
    false,
  );
}

/* ---------------- normalize / validate the contract ---------------- */

function safeJson(text: string): Record<string, unknown> | null {
  const cleaned = text.trim().replace(/^```(?:json)?/i, "").replace(/```$/, "").trim();
  try {
    const v = JSON.parse(cleaned);
    return v && typeof v === "object" ? (v as Record<string, unknown>) : null;
  } catch {
    return null;
  }
}

const INTENTS: NaemaIntent[] = ["greeting", "product_inquiry", "quote", "shipping", "order_support", "measurement", "other"];

function normalize(raw: Record<string, unknown>, grounded: boolean): NaemaReply {
  const asArr = <T,>(v: unknown): T[] => (Array.isArray(v) ? (v as T[]) : []);
  const intent = INTENTS.includes(raw.intent as NaemaIntent) ? (raw.intent as NaemaIntent) : "other";
  const readiness = ["low", "medium", "high"].includes(raw.readiness as string) ? (raw.readiness as "low" | "medium" | "high") : "low";
  const handoff = (raw.handoff && typeof raw.handoff === "object" ? raw.handoff : {}) as { required?: unknown; reason?: unknown };
  return {
    intent,
    message: String(raw.message ?? "").slice(0, 1200) || "How can I help you today?",
    confidence: typeof raw.confidence === "number" ? Math.max(0, Math.min(1, raw.confidence)) : grounded ? 0.8 : 0.6,
    products: asArr<{ slug?: unknown; reason?: unknown }>(raw.products)
      .filter((p) => typeof p?.slug === "string")
      .slice(0, 6)
      .map((p) => ({ slug: String(p.slug), reason: p.reason ? String(p.reason) : undefined })),
    questions: asArr<{ id?: unknown; label?: unknown }>(raw.questions)
      .filter((q) => q?.label)
      .slice(0, 4)
      .map((q, i) => ({ id: q.id ? String(q.id) : `q${i}`, label: String(q.label).slice(0, 60) })),
    actions: asArr<NaemaAction>(raw.actions)
      .filter((a) => a?.type && a?.label)
      .slice(0, 4)
      .map((a) => ({ type: a.type, label: String(a.label).slice(0, 40), value: a.value ? String(a.value) : undefined })),
    handoff: { required: Boolean(handoff.required), reason: handoff.reason ? String(handoff.reason) : undefined },
    sources: asArr<{ type?: unknown; recordId?: unknown }>(raw.sources)
      .filter((s) => s?.recordId)
      .map((s) => ({ type: s.type === "hub" ? "hub" : "catalog", recordId: String(s.recordId) })),
    analytics: { readiness, stage: intent === "quote" || intent === "order_support" ? "conversion" : "consideration" },
    grounded,
  };
}

/* ---------------- handler ---------------- */

export async function POST(request: Request): Promise<Response> {
  const started = Date.now();
  let body: NaemaRequest;
  try {
    body = (await request.json()) as NaemaRequest;
  } catch {
    return Response.json({ error: "Invalid request" }, { status: 400 });
  }

  const sessionId = String(body.sessionId ?? "").slice(0, 80) || "anon";
  const messages = Array.isArray(body.messages) ? body.messages.slice(-MAX_HISTORY).map(clampMsg) : [];
  if (!messages.length || !messages.some((m) => m.role === "user")) {
    return Response.json({ error: "No message" }, { status: 400 });
  }
  if (rateLimited(sessionId)) {
    return Response.json({ error: "Too many messages — please wait a moment." }, { status: 429 });
  }

  const req: NaemaRequest = { messages, sessionId, locale: body.locale, pageContext: body.pageContext };
  const toolsUsed: string[] = [];
  let reply: NaemaReply;
  let mode = AI_KEY ? "grok" : "fallback";

  try {
    reply = AI_KEY ? await runWithGrok(req, toolsUsed) : await runFallback(req, toolsUsed);
  } catch (err) {
    // Grok unreachable / errored → never fail the customer; ground a real answer.
    mode = "fallback";
    console.error("[naema] grok failed, using fallback:", err instanceof Error ? err.message : err);
    reply = await runFallback(req, toolsUsed);
  }

  console.log(
    JSON.stringify({
      t: "naema", mode, sessionId, intent: reply.intent, grounded: reply.grounded,
      tools: toolsUsed, products: reply.products.length, handoff: reply.handoff.required,
      ms: Date.now() - started,
    }),
  );

  return Response.json(reply);
}
