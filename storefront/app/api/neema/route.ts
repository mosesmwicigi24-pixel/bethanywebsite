import { getCatalog } from "@/lib/catalog";
import { fetchOrderStatus, createLead, estimateShipping } from "@/lib/hub";
import { SITE } from "@/lib/site";
import {
  classifyIntent,
  leadCaptureFor,
  scoreProducts,
  type ChatMessage,
  type NeemaAction,
  type NeemaCapture,
  type NeemaIntent,
  type NeemaReply,
  type NeemaRequest,
  type PageContext,
} from "@/lib/neema";
import type { Product } from "@/lib/products";
import { chatChain, providerChat, type LlmMessage, type LlmTool, type ProviderCfg } from "@/lib/llm";
import { getOrCreateVid } from "@/lib/vid";

/* ============================================================
   Neema AI gateway — the single server-side entry for every AI
   request (advisory §2–3). The browser never talks to the models or
   holds Hub write-credentials; it POSTs here, and this route:

     • enforces guardrails (size caps, per-session rate limit)
     • runs Neema through a provider fallback chain — Groq (primary,
       llama-3.3-70b-versatile) → Anthropic → OpenAI → Gemini — with
       function-calling tools grounding answers in the live catalog
     • falls back to a deterministic, catalog-grounded orchestrator
       when no provider is configured or all error — so the widget
       always works (mirrors lib/hub.ts / lib/lookup.ts "demo mode")
     • returns a validated NeemaReply the frontend renders as UI
     • logs one structured line per turn for observability (§9)

   Model providers are configured via server-only env (see lib/llm.ts):
     GROQ_API_KEY / GROQ_MODEL / GROQ_API_URL
     ANTHROPIC_API_KEY / ANTHROPIC_MODEL / ANTHROPIC_API_URL
     OPENAI_API_KEY / OPENAI_MODEL / OPENAI_API_URL
     GEMINI_API_KEY / GEMINI_MODEL / GEMINI_API_URL
   ============================================================ */

const MAX_MSG_CHARS = 2000;
const MAX_HISTORY = 12;
const RATE_LIMIT = 20; // requests
const RATE_WINDOW_MS = 60_000; // per minute, per session

// WhatsApp deep-link to the shop line (E.164, no + or spaces).
const WA_NUMBER = SITE.phone.replace(/[^\d]/g, "");
// Coerce any WhatsApp value into a valid absolute wa.me URL, so a bare phone
// number can never render as a relative link (which 404s the page).
function coerceWa(value?: string): string {
  if (value && /^https?:\/\//i.test(value)) return value;
  const digits = (value || "").replace(/\D/g, "");
  return `https://wa.me/${digits.length >= 9 ? digits : WA_NUMBER}`;
}
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

async function execCreateLead(args: Record<string, unknown>, req: NeemaRequest) {
  const phone = String(args.phone ?? "").trim();
  if (!phone) return { created: false, note: "Ask the customer for a phone/WhatsApp number first." };
  const lead = await createLead({
    intent: "quote",
    readiness: (args.readiness as "low" | "medium" | "high") || "high",
    name: args.name ? String(args.name) : undefined,
    phone,
    city: args.city ? String(args.city) : undefined,
    countryCode: args.country ? String(args.country) : undefined,
    products: Array.isArray(args.products) ? (args.products as string[]) : undefined,
    quantity: args.quantity ? String(args.quantity) : undefined,
    message: args.message ? String(args.message) : undefined,
    sourcePath: req.pageContext?.path,
  });
  return lead
    ? { created: true, lead_id: lead.leadId }
    : { created: false, note: "Hub lead endpoint not ready — tell the customer our team will follow up on WhatsApp." };
}

async function execEstimateShipping(args: Record<string, unknown>) {
  const est = await estimateShipping({
    country: args.country ? String(args.country) : undefined,
    countryCode: args.country_code ? String(args.country_code) : undefined,
    city: args.city ? String(args.city) : undefined,
    items: Array.isArray(args.items) ? (args.items as string[]) : undefined,
  });
  return (
    est ?? {
      available: false,
      note: "No live rate yet — we ship worldwide from Nairobi; capture the destination and staff finalise the precise quote on WhatsApp.",
    }
  );
}

/* ---------------- tool registry (provider-agnostic) ---------------- */

const TOOLS: LlmTool[] = [
  {
    name: "search_products",
    description: "Search the live Bethany House catalog. Use for any product question. Returns canonical slugs, prices (KES/USD), category and stock. Only recommend products this returns, by their exact slug.",
    parameters: {
      type: "object",
      properties: { query: { type: "string", description: "What the customer is looking for, in their own words." } },
      required: ["query"],
    },
  },
  {
    name: "get_order_status",
    description: "Look up a live order by its payment token. If you don't have a token, do NOT guess — return the find_orders action instead.",
    parameters: {
      type: "object",
      properties: { payment_token: { type: "string" } },
      required: ["payment_token"],
    },
  },
  {
    name: "create_lead",
    description: "Capture a qualified lead once the customer wants a quotation, a bulk/parish order, or asks the team to follow up. Requires at least a phone/WhatsApp number; gather it first.",
    parameters: {
      type: "object",
      properties: {
        phone: { type: "string", description: "WhatsApp or phone — required" },
        name: { type: "string" },
        city: { type: "string" },
        country: { type: "string" },
        quantity: { type: "string" },
        products: { type: "array", items: { type: "string" }, description: "product slugs of interest" },
        message: { type: "string" },
        readiness: { type: "string", enum: ["low", "medium", "high"] },
      },
      required: ["phone"],
    },
  },
  {
    name: "estimate_shipping",
    description: "Estimate international shipping to a destination once the customer names a country (and city if known). Bethany House ships worldwide from Nairobi.",
    parameters: {
      type: "object",
      properties: {
        country: { type: "string" },
        city: { type: "string" },
        items: { type: "array", items: { type: "string" }, description: "product slugs to ship" },
      },
      required: ["country"],
    },
  },
];

function systemPrompt(ctx: PageContext | undefined, locale: string | undefined): string {
  const here = ctx?.productSlug ? `The customer is viewing product "${ctx.productSlug}". ` : ctx?.category ? `The customer is browsing "${ctx.category}". ` : "";
  return [
    `You are Neema, the warm, sharp sales assistant for ${SITE.name} — ${SITE.tagline}`,
    `Shop: ${SITE.address}, ${SITE.city}. Hours: ${SITE.hours}. Phone/WhatsApp: ${SITE.phone}. Payments: ${SITE.payments}. ${SITE.deliveryPromise}. We ship worldwide.`,
    here + (locale ? `Customer locale: ${locale}. ` : ""),
    "TALK LIKE A REAL PERSON ON WHATSAPP — never like a form:",
    "- Read the whole conversation and ALWAYS move it forward. NEVER repeat a message or re-describe a product you've already covered. If the customer says yes / ok / proceed, take the NEXT concrete step — do not restate the pitch.",
    "- Acknowledge what they just said, then add something new. Be concise and natural, ask at most one question, and vary your wording.",
    "GROUNDING:",
    "- Use search_products for every product, price and stock fact. NEVER invent prices, availability, totals or shipping figures. Recommend only products it returns, by exact slug. Prices are KES in Kenya, USD elsewhere.",
    "BUYING — you CANNOT take payment or place orders in this chat:",
    "- NEVER offer 'Pay with M-Pesa/Visa' or say an order is placed. Payment happens on the website checkout only.",
    "- To buy a normal item: send them to the product with a view_product action (e.g. label 'View & buy') — they add to cart and pay securely at checkout.",
    "- For bulk / parish / wholesale / a quotation (dozens or more, or the word 'quote'): collect their details for a real quote — ask for WhatsApp number, quantity and city, set readiness high and handoff.required=true; our team confirms on WhatsApp.",
    "- Made-to-order garments need measurements before production. When unsure, or they want a person, offer a whatsapp action.",
    "Respond with ONLY a JSON object (no prose, no code fences) matching:",
    `{"intent":"greeting|product_inquiry|quote|shipping|order_support|measurement|other","message":"warm, specific reply that advances the conversation","confidence":0..1,"products":[{"slug":"exact-slug","reason":"why"}],"questions":[{"id":"short_id","label":"one-tap reply, <=5 words"}],"actions":[{"type":"view_product|whatsapp|request_quote|find_orders|shop","label":"button text","value":"exact product-slug for view_product; leave blank for whatsapp"}],"handoff":{"required":false,"reason":null},"readiness":"low|medium|high"}`,
    "For view_product, value MUST be the exact product slug (never a URL). For whatsapp, leave value blank — the app fills the correct link.",
  ].join("\n");
}

/* ---------------- provider chain (Groq → Anthropic → Gemini) ---------------- */

async function runToolLoop(cfg: ProviderCfg, req: NeemaRequest, toolsUsed: string[]): Promise<NeemaReply> {
  const history: LlmMessage[] = [
    { role: "system", content: systemPrompt(req.pageContext, req.locale) },
    ...req.messages.map((m) => ({ role: m.role, content: m.content } as LlmMessage)),
  ];

  // Retrieval loop — let Neema call tools until it has what it needs.
  for (let i = 0; i < 4; i++) {
    const msg = await providerChat(cfg, history, TOOLS, false);
    if (!msg.toolCalls.length) {
      history.push({ role: "assistant", content: msg.content });
      break;
    }
    history.push({ role: "assistant", content: msg.content || null, toolCalls: msg.toolCalls });
    for (const call of msg.toolCalls) {
      toolsUsed.push(call.name);
      let result: unknown = {};
      try {
        const args = JSON.parse(call.arguments || "{}");
        if (call.name === "search_products") result = await execSearchProducts(String(args.query ?? ""));
        else if (call.name === "get_order_status") result = await execGetOrderStatus(String(args.payment_token ?? ""));
        else if (call.name === "create_lead") result = await execCreateLead(args, req);
        else if (call.name === "estimate_shipping") result = await execEstimateShipping(args);
      } catch {
        result = { error: "tool failed" };
      }
      history.push({ role: "tool", toolCallId: call.id, content: JSON.stringify(result) });
    }
  }

  // Compose the final structured answer (JSON mode, no tools).
  const finalMsg = await providerChat(cfg, [...history, { role: "user", content: "Now return ONLY the JSON reply object." }], null, true);
  const parsed = safeJson(finalMsg.content);
  if (!parsed) throw new Error("non-JSON final"); // let the chain try the next provider
  return normalize(parsed, true);
}

/* ---------------- neema-ai brain (Path A: one Neema everywhere) ----------------
   When NEEMA_AGENT_URL + NEEMA_AGENT_KEY are set, every web turn is proxied to
   the shared neema-ai agent — the SAME brain as WhatsApp/Messenger/IG, with its
   own memory, tools, live-hub grounding and human takeover. We send only the
   latest user message + a stable session_id; the agent threads the rest and
   shows the visitor in the dashboard inbox. On any error (or when unset) we fall
   through to the storefront's own gateway below, so the widget never breaks. */

const AGENT_URL = process.env.NEEMA_AGENT_URL;
const AGENT_KEY = process.env.NEEMA_AGENT_KEY;
const agentLive = () => Boolean(AGENT_URL && AGENT_KEY);

/* --- The closer: re-attach cards + "Add to cart" on a text-only agent reply ---
   Path A routes every turn to the neema-ai brain, which today answers in prose.
   That dropped the product cards and add-to-cart button that used to carry a
   customer to checkout. Until neema-ai returns products/actions itself (see
   docs/NEEMA_WEB_CHAT_CONTRACT.md), we rebuild them here — grounded in the live
   catalog, never guessed: only a product Neema actually named is surfaced. */

const NAME_STOP = new Set(["set", "the", "and", "for", "with", "from", "pieces", "piece"]);
const normText = (s: string) => ` ${s.toLowerCase().replace(/[^a-z0-9]+/g, " ").replace(/\s+/g, " ").trim()} `;
const nameTokens = (s: string) =>
  [...new Set(normText(s).trim().split(" "))].filter((t) => t.length >= 3 && !NAME_STOP.has(t) && !/^\d+$/.test(t));
/** Which catalog products is this conversation about? Matches product names — the
    primary segment before the em-dash — against the text: a full-name phrase, or
    ≥2 name words covering ≥60% of the name. Prefers what Neema recommended (the
    reply), falling back to what the customer asked. Returns up to 3 cards; each
    renders in the widget with its own one-tap "Add" closer. */
function deriveProducts(replyText: string, userText: string, all: Product[]): { slug: string }[] {
  const catalog = all.filter((p) => !p.variantId);
  const rank = (text: string) => {
    const hay = normText(text);
    return catalog
      .map((p) => {
        const primary = p.name.split(/[—–\-|&]/)[0];
        const toks = nameTokens(primary);
        if (!toks.length) return { p, score: 0 };
        const phrase = hay.includes(normText(primary)) ? 1 : 0;
        let hits = 0;
        for (const t of toks) if (hay.includes(` ${t}`)) hits += 1;
        const ok = phrase === 1 || (hits >= 2 && hits / toks.length >= 0.6) || (toks.length === 1 && hits === 1 && toks[0].length >= 5);
        return { p, score: ok ? phrase * 3 + hits + (p.badge === "best" ? 0.5 : 0) : 0 };
      })
      .filter((x) => x.score > 0)
      .sort((a, b) => b.score - a.score);
  };
  const fromReply = rank(replyText);
  const matches = fromReply.length ? fromReply : rank(userText);
  return matches.slice(0, 3).map((m) => ({ slug: m.p.slug }));
}

/* Keep the customer shopping here. neema-ai sometimes opens by pasting an
   off-site catalog link ("browse: https://neema.bethanyhouse.co.ke/catalog…"),
   which pulls people out of the chat — away from the cards + Add to cart. Drop
   the link (and its lead-in) and let the warm reply carry the moment. */
function cleanReply(text: string): string {
  if (!/https?:\/\//i.test(text)) return text; // no link → leave the reply untouched
  return text
    // an invite + link ("browse our range here: <url>") — drop the whole clause
    .replace(/\b(here'?s|browse|see|view|check out|explore|visit|find|shop)\b[^:.!?\n]{0,60}:\s*https?:\/\/\S+/gi, " ")
    .replace(/\bhttps?:\/\/\S+/gi, " ")
    // tidy a dangling opener left at the seam ("You can  What size…")
    .replace(/\b(you can|you may|please|kindly|feel free to|i'?ll|i can)\s+(?=[A-Z])/gi, "")
    .replace(/\s+([,.;:!?])/g, "$1")
    .replace(/[ \t]{2,}/g, " ")
    .replace(/[ \t]+\n/g, "\n")
    .trim();
}

/* The WhatsApp handoff. Lead with the purchase and let the team pick up the full
   thread from Neema (the AI already has it in the dashboard) — not a transcript of
   the chat. Deliberately a quiet, secondary path: Add to cart stays the close. */
const deslug = (s: string) => s.replace(/--v\d+$/i, "").replace(/-[a-z0-9]{8,}$/i, "").replace(/-/g, " ").trim();
/** Tidy a size token for display: "500PCS" → "500 pcs", "1,000 pieces" stays.
    Only touches spacing/case around pcs/pieces; leaves other units alone. */
const prettySize = (s: string) =>
  s.replace(/\s+/g, " ").trim().replace(/(\d)\s*(pcs?|pieces?)\b/i, (_m, d, u) => `${d} ${String(u).toLowerCase()}`);

/** A short, human product label for the handoff: the catalog name's primary
    segment, with a size/quantity hint in parentheses when the name carries one —
    however it's attached ("Chalice — Gold Set", "Wine — 750ml", "Bread -500PCS"). */
function itemLabel(slug: string, catalog: Product[]): string {
  const name = (catalog.find((c) => c.slug === slug)?.name ?? deslug(slug)).trim();
  let head = name;
  let tail = "";
  const em = name.split(/\s*[—–]\s*/); // em/en-dash → a descriptor or a size
  if (em.length > 1) {
    head = em[0].trim();
    tail = em.slice(1).join(" ").trim();
  } else {
    const hy = name.match(/^(.*\S)\s*-\s*(\d[\w.,\s]*)$/); // hyphen + a trailing size ("Bread -500PCS")
    if (hy) {
      head = hy[1].trim();
      tail = hy[2].trim();
    }
  }
  const hint = /\d/.test(tail) ? ` (${prettySize(tail)})` : "";
  return `${head}${hint}`.slice(0, 48);
}
function waHandoff(products: { slug: string }[], catalog: Product[], token?: string): string {
  const items = products.slice(0, 3).map((p) => itemLabel(p.slug, catalog)).filter(Boolean).join(", ");
  // Carry the cart token so Neema can load the exact same cart on WhatsApp.
  const ref = token ? ` (cart ${token})` : "";
  return items
    ? `Hello Bethany House! I want to buy ${items}.${ref} Can you pick up from Neema and process my order?`
    : `Hello Bethany House! I'd like to place an order${ref} — can you pick up my chat with Neema and help me finish?`;
}

async function runAgent(req: NeemaRequest, visitorId?: string): Promise<NeemaReply> {
  const lastUser = [...req.messages].reverse().find((m) => m.role === "user")?.content ?? "";
  const r = await fetch(AGENT_URL as string, {
    method: "POST",
    headers: { "Content-Type": "application/json", "X-Storefront-Key": AGENT_KEY as string },
    body: JSON.stringify({ session_id: req.sessionId || "anon", message: lastUser, visitor_id: visitorId }),
    signal: AbortSignal.timeout(30_000),
  });
  if (!r.ok) throw new Error(`agent ${r.status}`);
  const data = (await r.json()) as {
    reply?: string; handled_by?: string;
    products?: unknown; actions?: unknown; quick_replies?: unknown;
  };
  const rawReply = String(data.reply ?? "").trim();
  if (!rawReply) throw new Error("agent empty reply");
  // Strip off-site links so the chat keeps the customer here; never blank the message.
  const reply = cleanReply(rawReply) || "Welcome to Bethany House! Here are a few of our items — tell me exactly what you need and I'll help you order.";
  const human = data.handled_by === "human";

  // The agent's own product cards win when present (in production the neema-ai
  // brain already returns them). Text-only reply → we rebuild the cards ourselves,
  // grounded in the live catalog. Either way, every card carries its own one-tap
  // "Add" closer in the widget, so the customer can buy without leaving the chat.
  const rawActions = Array.isArray(data.actions) ? (data.actions as { type?: unknown }[]) : [];
  const agentProducts = Array.isArray(data.products) && data.products.length ? (data.products as { slug: string }[]) : null;
  const hasWa = rawActions.some((a) => a?.type === "whatsapp" || a?.type === "request_quote");

  // Load the catalog once (cached): used both to rebuild cards on a text-only
  // reply and to give the WhatsApp handoff real product names.
  let catalog: Product[] = [];
  try {
    catalog = await getCatalog();
  } catch {
    /* catalog slow/unavailable — the text reply still stands on its own */
  }
  let products: { slug: string }[] = agentProducts ?? [];
  if (!agentProducts) products = deriveProducts(reply, lastUser, catalog);

  const actions: unknown[] = [...rawActions];
  if (!hasWa) actions.push({ type: "whatsapp", label: "Chat on WhatsApp", value: waLink(waHandoff(products, catalog, req.cartToken)) });

  return normalize(
    {
      intent: "other",
      message: reply,
      confidence: 0.95,
      products,
      questions: Array.isArray(data.quick_replies) ? data.quick_replies : [],
      actions,
      handoff: { required: human, reason: human ? "A team member is replying" : undefined },
    },
    true,
  );
}

/** Try each configured provider in order (Groq → Anthropic → Gemini); on error
    move to the next. Returns the provider that answered, or "fallback" when all
    fail or none are configured. */
async function runChain(req: NeemaRequest, toolsUsed: string[]): Promise<{ reply: NeemaReply; mode: string }> {
  for (const cfg of chatChain()) {
    const used: string[] = [];
    try {
      const reply = await runToolLoop(cfg, req, used);
      toolsUsed.push(...used);
      return { reply, mode: cfg.name };
    } catch (err) {
      console.error(`[neema] provider ${cfg.name} failed:`, err instanceof Error ? err.message : err);
    }
  }
  return { reply: await runFallback(req, toolsUsed), mode: "fallback" };
}

/* ---------------- deterministic fallback (always works) ---------------- */

function actionsFor(intent: NeemaIntent, products: Product[], ctx?: PageContext): NeemaAction[] {
  const out: NeemaAction[] = [];
  if (products[0]) out.push({ type: "view_product", label: `View ${products[0].short || products[0].name}`, value: products[0].slug });
  if (intent === "order_support") out.push({ type: "find_orders", label: "Find my orders", value: "/orders" });
  if (intent === "quote") out.push({ type: "request_quote", label: "Request a quote on WhatsApp", value: waLink("Hello Bethany House, I'd like a quotation for our church.") });
  out.push({ type: "whatsapp", label: "Chat on WhatsApp", value: waLink(`Hello Bethany House${ctx?.productSlug ? ` — I'm interested in ${ctx.productSlug}` : ""}.`) });
  return out;
}

// whether the message clearly signals a non-product intent (so a product-page
// visitor asking "how do I ship this?" isn't forced into product_inquiry)
const classifyMatch = (t: string) => classifyIntent(t) !== "product_inquiry";

function shapeText(intent: NeemaIntent, q: string, ctx?: PageContext) {
  switch (intent) {
    case "greeting":
      return {
        intent, confidence: 0.9,
        message: `Hello! I'm Neema, here to help you find the right communion elements, clergy apparel or church gifts — and get them to your church anywhere in the world. What are you looking for today?`,
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
        intent: "product_inquiry" as NeemaIntent, confidence: 0.6,
        message: `Here's what I'd recommend from our catalogue. Tap any item for details and pricing, or tell me more about the occasion, denomination or budget and I'll narrow it down.`,
        questions: [{ id: "budget", label: "Set a budget?" }, { id: "occasion", label: "For an occasion?" }],
        query: q || ctx?.category || "", readiness: "medium" as const, handoff: { required: false },
      };
  }
}

async function runFallback(req: NeemaRequest, toolsUsed: string[]): Promise<NeemaReply> {
  const lastUser = [...req.messages].reverse().find((m) => m.role === "user")?.content ?? "";
  const intent: NeemaIntent = req.pageContext?.productSlug && !classifyMatch(lastUser) ? "product_inquiry" : classifyIntent(lastUser);
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

  const reply = normalize(
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

  // The lead moment: on a quote, a shipping enquiry, or any forced handoff,
  // collect details in chat instead of bouncing straight to WhatsApp. The
  // shipping form captures the destination staff need to quote a rate.
  if (shaped.intent === "quote" || shaped.intent === "shipping" || shaped.handoff.required) {
    reply.capture = leadCaptureFor(shaped.intent);
  }
  return reply;
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

const INTENTS: NeemaIntent[] = ["greeting", "product_inquiry", "quote", "shipping", "order_support", "measurement", "other"];

function isCapture(v: unknown): v is NeemaCapture {
  return Boolean(v && typeof v === "object" && Array.isArray((v as NeemaCapture).fields) && (v as NeemaCapture).fields.length > 0);
}

function normalize(raw: Record<string, unknown>, grounded: boolean): NeemaReply {
  const asArr = <T,>(v: unknown): T[] => (Array.isArray(v) ? (v as T[]) : []);
  const intent = INTENTS.includes(raw.intent as NeemaIntent) ? (raw.intent as NeemaIntent) : "other";
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
    actions: asArr<NeemaAction>(raw.actions)
      .filter((a) => a?.type && a?.label)
      .slice(0, 4)
      .map((a) => {
        const value = a.value ? String(a.value) : undefined;
        return { type: a.type, label: String(a.label).slice(0, 40), value: a.type === "whatsapp" || a.type === "request_quote" ? coerceWa(value) : value };
      }),
    handoff: { required: Boolean(handoff.required), reason: handoff.reason ? String(handoff.reason) : undefined },
    sources: asArr<{ type?: unknown; recordId?: unknown }>(raw.sources)
      .filter((s) => s?.recordId)
      .map((s) => ({ type: s.type === "hub" ? "hub" : "catalog", recordId: String(s.recordId) })),
    analytics: { readiness, stage: intent === "quote" || intent === "order_support" ? "conversion" : "consideration" },
    capture: isCapture(raw.capture) ? (raw.capture as unknown as NeemaCapture) : undefined,
    grounded,
  };
}

/* ---------------- handler ---------------- */

export async function POST(request: Request): Promise<Response> {
  const started = Date.now();
  let body: NeemaRequest;
  try {
    body = (await request.json()) as NeemaRequest;
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

  const req: NeemaRequest = {
    messages, sessionId, locale: body.locale, pageContext: body.pageContext,
    cartToken: typeof body.cartToken === "string" ? body.cartToken.slice(0, 40) : undefined,
  };
  // Set/refresh the durable visitor cookie now (from the first chat, before any
  // add-to-cart) and pass it to the agent so the whole visit shares one anchor.
  const visitorId = await getOrCreateVid();
  const toolsUsed: string[] = [];
  let reply: NeemaReply;
  let mode: string;

  try {
    if (agentLive()) {
      try {
        reply = await runAgent(req, visitorId);
        mode = "agent";
      } catch (err) {
        console.error("[neema] agent failed, falling back to storefront gateway:", err instanceof Error ? err.message : err);
        const res = await runChain(req, toolsUsed);
        reply = res.reply;
        mode = res.mode;
      }
    } else {
      const res = await runChain(req, toolsUsed);
      reply = res.reply;
      mode = res.mode;
    }
  } catch (err) {
    // Even the deterministic fallback failed — never 500 the customer.
    console.error("[neema] chain failed hard:", err instanceof Error ? err.message : err);
    mode = "error";
    reply = normalize(
      {
        message: "I'm having a little trouble right now — reach us on WhatsApp and we'll help straight away.",
        actions: [{ type: "whatsapp", label: "Chat on WhatsApp", value: waLink("Hello Bethany House") }],
      },
      false,
    );
  }

  console.log(
    JSON.stringify({
      t: "neema", mode, sessionId, intent: reply.intent, grounded: reply.grounded,
      tools: toolsUsed, products: reply.products.length, handoff: reply.handoff.required,
      ms: Date.now() - started,
    }),
  );

  return Response.json(reply);
}
