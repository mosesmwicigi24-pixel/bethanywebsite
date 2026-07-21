import type { Product } from "./products";

/* ============================================================
   Neema — shared contract + grounding helpers.

   This file is client-safe (types + pure functions only, no server
   imports). The gateway (app/api/neema/route.ts) uses the helpers to
   ground answers in the live catalog; the widget (components/Neema.tsx)
   uses the types to render structured replies as real UI.

   The response contract lets the frontend render product cards, quick
   replies and actions instead of treating every answer as chat text
   (see docs/AI_INTEGRATION_ADVISORY.md §3–4, Appendix A).
   ============================================================ */

export type NeemaIntent =
  | "greeting"
  | "product_inquiry"
  | "quote"
  | "shipping"
  | "order_support"
  | "measurement"
  | "other";

export type ChatRole = "user" | "assistant";
export interface ChatMessage {
  role: ChatRole;
  content: string;
}

export interface PageContext {
  path?: string;
  productSlug?: string;
  category?: string;
}

export interface NeemaRequest {
  messages: ChatMessage[];
  sessionId: string;
  locale?: string;
  pageContext?: PageContext;
}

/** A recommended product — `slug` resolves through the client catalog
    (useCatalog().bySlug) to render the same card the rest of the site uses. */
export interface NeemaProductRef {
  slug: string;
  reason?: string;
}

/** A follow-up the customer can answer with one tap. */
export interface NeemaQuestion {
  id: string;
  label: string;
}

export interface NeemaAction {
  type: "view_product" | "add_to_cart" | "whatsapp" | "request_quote" | "find_orders" | "shop";
  label: string;
  value?: string; // e.g. a slug for view_product
}

/** Grounding proof — which record backed a factual claim. */
export interface NeemaSource {
  type: "hub" | "catalog";
  recordId: string;
}

/** A single field in an in-chat lead-capture form. */
export interface NeemaCaptureField {
  id: string;
  label: string;
  type: "text" | "tel" | "email" | "select" | "textarea";
  required?: boolean;
  placeholder?: string;
  options?: string[];
}

/** A short structured form Neema shows to capture a qualified lead — the
    guided-quotation moment (advisory §3, §6). Submitted to /api/neema/lead. */
export interface NeemaCapture {
  title: string;
  fields: NeemaCaptureField[];
  submitLabel: string;
  intent: string;
}

export interface NeemaReply {
  intent: NeemaIntent;
  message: string;
  confidence: number; // 0..1 — below threshold ⇒ offer handoff
  products: NeemaProductRef[];
  questions: NeemaQuestion[];
  actions: NeemaAction[];
  handoff: { required: boolean; reason?: string };
  sources: NeemaSource[];
  analytics: { readiness: "low" | "medium" | "high"; stage: string };
  /** present when Neema wants to capture the customer's details next. */
  capture?: NeemaCapture;
  /** true when a model provider + tools produced it; false = deterministic fallback. */
  grounded: boolean;
}

/** The standard lead form. Kept deliberately short — ask only what staff
    need to follow up: who, how to reach them, where, and (for quotes) how many. */
export function leadCaptureFor(intent: NeemaIntent): NeemaCapture {
  const isQuote = intent === "quote";
  const isShipping = intent === "shipping";
  const fields: NeemaCaptureField[] = [
    { id: "name", label: "Your name", type: "text", placeholder: "Rev. / Ms. / Mr." },
    { id: "phone", label: "WhatsApp or phone", type: "tel", required: true, placeholder: "+254 7…" },
    { id: "country", label: "Country", type: "text", required: isShipping, placeholder: isShipping ? "Uganda" : "Kenya" },
    { id: "city", label: "City", type: "text", placeholder: isShipping ? "Kampala" : "Nairobi" },
  ];
  if (isQuote) fields.push({ id: "quantity", label: "Quantity", type: "text", placeholder: "e.g. 12" });
  fields.push({
    id: "note",
    label: isShipping ? "What would you like shipped?" : "Anything else?",
    type: "textarea",
    placeholder: isShipping ? "Items, quantity, timing…" : "Occasion, colours, sizes…",
  });
  return {
    title: isShipping ? "Get a shipping estimate" : isQuote ? "Start your quotation" : "Have our team follow up",
    fields,
    submitLabel: isShipping ? "Request estimate" : isQuote ? "Request quotation" : "Send my details",
    intent,
  };
}

/* ---------------- Intent classification (keyword, deterministic) ---------------- */

const INTENT_RULES: [NeemaIntent, RegExp][] = [
  ["order_support", /\b(order|track(ing)?|delivery status|where.*(my|is).*(order|parcel)|receipt|refund|dispatch|shipped)\b/i],
  ["shipping", /\b(ship(ping)?|deliver(y|ed)?|courier|export|customs|international|abroad|overseas|worldwide|how long|arrive|country)\b/i],
  ["measurement", /\b(measure(ment)?s?|size|sizing|fit|neck|chest|shoulders|sleeve|length|how.*measure)\b/i],
  // Explicit quote language, OR a quantity followed by an item — so "gold
  // chalice for our parish" stays a product inquiry, while "12 cassocks" or
  // "quotation for our choir" is a quote.
  ["quote", /\b(quotation|quote|bulk|wholesale|invoice|discount|price list|how much for)\b|\b\d+\s*(cassocks?|robes?|gowns?|chasubles?|stoles?|sets?|pieces?|servers?|dozen)\b/i],
  ["greeting", /^\s*(hi|hey|hello|habari|sasa|good (morning|afternoon|evening)|greetings)\b/i],
];

export function classifyIntent(text: string): NeemaIntent {
  for (const [intent, re] of INTENT_RULES) if (re.test(text)) return intent;
  return "product_inquiry";
}

/* ---------------- Catalog search (grounds product recommendations) ----------------
   Backs both the deterministic fallback and the provider search_products tool, so the
   two paths recommend from the same source of truth. A light synonym layer maps
   the many names customers use for the same item — a first cut at the
   denomination-vocabulary idea in the advisory (§5.3). */

const SYNONYMS: Record<string, string[]> = {
  chalice: ["cup", "goblet", "communion cup"],
  paten: ["plate", "communion plate"],
  host: ["hosts", "wafer", "wafers", "altar bread", "communion bread"],
  wine: ["altar wine", "communion wine"],
  cassock: ["soutane", "robe", "clergy robe"],
  chasuble: ["vestment", "vestments"],
  stole: ["tippet", "scarf"],
  alb: ["surplice", "gown"],
  tallit: ["prayer shawl", "prayer wear"],
  cross: ["crucifix"],
  bell: ["altar bell", "sanctus bell"],
  bible: ["scripture", "holy bible"],
};

const STOP = new Set([
  "the", "a", "an", "for", "our", "your", "we", "i", "to", "of", "and", "with", "do", "you",
  "have", "has", "is", "are", "want", "need", "looking", "buy", "get", "me", "my", "please",
  "some", "any", "can", "would", "like", "church", "how", "much", "where", "what",
]);

function expand(tokens: string[]): string[] {
  const out = new Set<string>();
  for (const t of tokens) {
    out.add(t);
    if (t.length > 3 && t.endsWith("s")) out.add(t.slice(0, -1)); // simple plural → singular
  }
  for (const t of [...out]) {
    if (SYNONYMS[t]) SYNONYMS[t].forEach((s) => out.add(s));
    for (const [key, vals] of Object.entries(SYNONYMS)) {
      if (vals.includes(t)) out.add(key);
    }
  }
  return [...out];
}

function tokenize(q: string): string[] {
  return q
    .toLowerCase()
    .split(/[^a-z0-9]+/)
    .filter((t) => t.length > 1 && !STOP.has(t));
}

const haystack = (p: Product): string =>
  [p.name, p.short, p.category, p.tagline ?? "", ...(p.chips ?? []).map((c) => c.text)]
    .join(" ")
    .toLowerCase();

/** Rank catalog products against a free-text query. Parents/simple products
    only (skips expanded variant rows). Falls back to featured items when a
    query matches nothing, so a recommendation is always available. */
export function scoreProducts(all: Product[], query: string, limit = 4): Product[] {
  const base = all.filter((p) => !p.variantId);
  const tokens = expand(tokenize(query));

  if (!tokens.length) {
    return base.filter((p) => p.badge === "best" || p.badge === "new").slice(0, limit);
  }

  const scored = base
    .map((p) => {
      const hay = haystack(p);
      const name = p.name.toLowerCase();
      let score = 0;
      for (const t of tokens) {
        if (name.includes(t)) score += 3;
        else if (hay.includes(t)) score += 1;
      }
      if (p.badge === "best") score += 0.5; // gentle tiebreak toward proven sellers
      return { p, score };
    })
    .filter((x) => x.score > 0)
    .sort((a, b) => b.score - a.score);

  if (!scored.length) {
    return base.filter((p) => p.badge === "best" || p.badge === "new").slice(0, limit);
  }
  return scored.slice(0, limit).map((x) => x.p);
}

/* ---------------- Measurement copilot (vision) ----------------
   Neema estimates garment measurements from a customer photo, keyed to the
   product's own measurement template, so the customer reviews and confirms
   each value before it reaches production (advisory §5.1). Estimates, never
   commitments — the confirm step is the customer editing the prefilled form. */

export interface MeasurementEstimate {
  name: string; // must match a template field name (Neck, Chest, …)
  value: string; // numeric string, inches
  confidence?: "low" | "medium" | "high";
}

export interface MeasureResult {
  available: boolean; // false when vision isn't configured or the photo was unusable
  estimates: MeasurementEstimate[];
  notes?: string; // caveats to show with the estimates
  guidance?: string; // self-measure help shown when available === false
}
