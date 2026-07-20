# Bethany House — AI Integration Advisory

**A code-grounded companion to *"AI-Powered Website Strategy & Implementation Roadmap."***

Prepared for: Bethany House Creations Limited
Systems in scope: `storefront/` (Next.js/React), Bethany Hub (`hub.bethanyhouse.co.ke/api/v1`), Neema (AI agent), and the model providers behind it — a server-side fallback chain of Groq (primary, `llama-3.3-70b-versatile`) → Anthropic → Gemini
Date: 20 July 2026

---

## 0. How to read this document

You already have an excellent **strategy** document. It is comprehensive, correct in its principles, and rightly refuses to treat AI as "a decorative chatbot." This advisory does something the strategy document could not: it was written **after reading your actual code**. Its job is to tell you three things the strategy alone cannot:

1. **What you have already built** (so you don't pay to build it twice).
2. **Exactly where each AI piece plugs in** — by file, by function, by route.
3. **Where the real, cheap, high-leverage wins are hiding** right now.

The headline finding: **the strategy document's single biggest infrastructure recommendation — "upgrade the React website to server-rendered / pre-rendered pages" — is already done.** Your `storefront/` is Next.js 16 with server components and ISR, already pulling live data from Bethany Hub. That changes the whole sequencing. You are not at the starting line; you are further along than the roadmap assumes.

---

## 1. What you already have (the reality check)

| Strategy doc treats as *future work* | Status in your code | Evidence |
|---|---|---|
| SSR / pre-rendering for public pages | ✅ **Done** | `storefront/` is Next.js 16 App Router; product & shop pages are server components with `export const revalidate = 300` (ISR) |
| Bethany Hub as source of truth via APIs | ✅ **Done (read + one write)** | `lib/catalog.ts` → `GET /api/v1/products`; `lib/hub.ts` → `POST /storefront/orders`, `GET /storefront/orders/{token}` |
| Live product, variant, price, stock data | ✅ **Done** | `getCatalog()` expands variants, resolves KES/USD, merges a curated design overlay by slug |
| Guest checkout → order → payment link | ✅ **Done** | `submitOnlineOrder()` returns `{ orderNumber, paymentLink }`; hub raises the production order |
| Live order tracking | ✅ **Done** | `fetchOrderStatus()` powers the receipt page's live tracker |
| Multi-currency / global pricing | ✅ **Partial** | KES/USD resolved from `country_code`; single source of business facts in `lib/site.ts` |
| Docker deployment behind nginx | ✅ **Done** | `docker-compose.yml` — storefront container on `127.0.0.1:8097`, legacy CI3 app on `8096` |

**Translation:** the expensive foundation is poured. The strategy's Phases 1 and 4 ("Foundation" and "Search/content engine — SSR completion") are largely behind you. You can skip straight to the parts that actually create the AI experience.

### What is genuinely missing (this is your real backlog)

| Gap | Where it should live | Effort | Impact |
|---|---|---|---|
| **No AI gateway** — there are zero server routes (`storefront/app/api/` does not exist) | new `app/api/neema/route.ts` | Medium | **Unblocks everything** |
| **Neema's entry point is a dead button** | `components/chrome.tsx` → `ChatFab()` renders a button that does nothing | Low | High |
| **No structured data (JSON-LD)** anywhere — no Product, Offer, Organization, Breadcrumb, FAQ, LocalBusiness | product/shop/layout pages | Low | **High (SEO + AI search)** |
| **Thin metadata** — `generateMetadata` sets only `title`; no description, canonical, OpenGraph, or `metadataBase` | `app/layout.tsx`, `app/product/[slug]/page.tsx` | Low | High |
| **No `sitemap.ts` / `robots.ts`** — Next.js has built-in conventions for both | `app/sitemap.ts`, `app/robots.ts` | Low | High |
| **Fabricated reviews** hardcoded in the PDP ("Rev. Canon Mwangi…") | `app/product/[slug]/page.tsx` | Low | **Trust/legal risk** — see §7 |
| **Writes run from the browser** — `submitOnlineOrder()` calls the hub directly from client code | route through the gateway before AI can trigger writes | Medium | Security (see §7) |

Everything below is organized around closing these gaps in the order that compounds value fastest.

---

## 2. The one architectural decision that matters most

The strategy document's "Critical architecture rule" is correct and worth restating in your terms:

> **Do not put model API keys in the browser. Every AI request goes through a server-side gateway.**

You are perfectly positioned for this because your storefront **already runs a Node server** (`output: "standalone"`, `node server.js` in the Dockerfile). The AI gateway is not a new service to stand up — it is a **Route Handler inside the app you already deploy.**

```
Browser (ChatFab panel)
      │  POST /api/neema   { message, sessionId, locale, pageContext }
      ▼
storefront  app/api/neema/route.ts   ← THE GATEWAY (auth, rate-limit, log, stream)
      │
      ├──►  Neema orchestrator  ──►  provider chain: Groq → Anthropic → Gemini (function calling)
      │
      └──►  Tool registry (server-side):
               search_products     → getCatalog()        [lib/catalog.ts, exists]
               get_order_status    → fetchOrderStatus()  [lib/hub.ts, exists]
               create_order        → submitOnlineOrder() [lib/hub.ts, exists]
               create_lead         → NEW hub endpoint
               estimate_shipping   → NEW hub endpoint
```

**Why this is the whole ballgame:** three of Neema's most valuable tools — product search, order status, and order creation — **already exist as tested functions in your `lib/`**. Wrapping them as tools the model can call is a day of work, not a quarter. The gateway is where you add the things the strategy rightly demands: authentication, rate limits, prompt-injection isolation, structured-output validation, caching, and an audit log. None of that belongs in React.

The gateway calls the model providers server-side (Groq/Gemini over their OpenAI-compatible endpoints, Anthropic over its Messages API) — the provider API keys and the hub's write credentials **never reach the browser.** Note the current `NEXT_PUBLIC_HUB_API` is inlined at build time and is fine for *public reads*; any AI-*initiated write* must go through the gateway with server-only credentials.

---

## 3. Wiring Neema in, layer by layer (mapped to your files)

### Layer 1 — Entry point (turn the dead button into Neema)

`components/chrome.tsx` already renders a `ChatFab` in `app/layout.tsx`. Today it is a button with no behavior. This is your lowest-risk first integration: make it open a chat panel that talks to `/api/neema`.

```tsx
// components/chrome.tsx — evolve ChatFab from a dead button into Neema's launcher
export function ChatFab() {
  const [open, setOpen] = useState(false);
  return (
    <>
      <button className="chat-fab" aria-label="Ask Neema" onClick={() => setOpen(true)}>
        <img src="/brand/mark-gold.png" alt="" />
      </button>
      {open && <NeemaPanel onClose={() => setOpen(false)} />}
    </>
  );
}
```

Because `ChatFab` is mounted once in the root layout, Neema is instantly available on **every** page, and it can read `pageContext` (which product, which category) to open with intent — exactly the "intent-aware welcome" the strategy calls for.

### Layer 2 — The gateway (new file, the heart of the system)

```ts
// storefront/app/api/neema/route.ts  (NEW — the AI Experience Layer)
import { getCatalog } from "@/lib/catalog";
import { fetchOrderStatus } from "@/lib/hub";

export async function POST(req: Request) {
  const { message, sessionId, locale, pageContext } = await req.json();

  // 1. guardrails: rate-limit by sessionId, size-cap the message, strip/ignore
  //    injected instructions from any uploaded content (§7).
  // 2. call the model provider with the tool schema below; the model decides which tool.
  // 3. execute the chosen tool SERVER-SIDE using your existing lib functions.
  // 4. validate the model's reply against the Neema Response Contract (§4).
  // 5. log { sessionId, intent, tools, latency, cost, outcome } for §9 analytics.
  // 6. stream the structured result back to the panel.
}
```

**Tool registry — most of it already exists:**

| Tool the model calls | Backed by | File | Status |
|---|---|---|---|
| `search_products(query, category, budget)` | `getCatalog()` + filter | `lib/catalog.ts` | ✅ exists |
| `get_product(slug)` | `getProductBySlug()` | `lib/catalog.ts` | ✅ exists |
| `get_order_status(token)` | `fetchOrderStatus()` | `lib/hub.ts` | ✅ exists |
| `create_order(draft)` | `submitOnlineOrder()` | `lib/hub.ts` | ✅ exists (move behind gateway) |
| `create_lead(intent, products, city, country)` | new hub endpoint | Bethany Hub | ➕ build |
| `estimate_shipping(destination, items)` | new hub endpoint | Bethany Hub | ➕ build |
| `handoff_to_staff(summary)` | new hub endpoint / WhatsApp | Bethany Hub | ➕ build |

The strategy document's §4 data-contract table is your build list for the hub side. You already proved the pattern with `POST /storefront/orders`; `create_lead` and `estimate_shipping` are the same shape.

### Layer 3 — Render components, not chat bubbles

Your storefront's biggest advantage over WhatsApp is that it can render **real UI** from Neema's answer. You already have the components: `ProductRail`, `cards.tsx`, `Money`, `MeasurementForm`, `ProductStudio`. When Neema recommends a chalice, don't print a paragraph — render the product card the rest of the site uses. This is the strategy's "The website renders rich UI components rather than treating every response as plain chat text," and you're one mapping function away from it.

### Layer 4 — The response contract (make it match your types)

The strategy's Appendix A contract is good. Tighten it to **your** `Product` model (`lib/products.ts`) so the frontend renders with zero translation:

```ts
// storefront/lib/neema.ts  (NEW) — the validated shape the gateway returns
export interface NeemaReply {
  intent: "product_inquiry" | "quote" | "shipping" | "order_support" | "measurement" | "other";
  message: string;                 // customer-facing prose
  confidence: number;              // 0..1 — below threshold ⇒ offer handoff
  products: { slug: string; reason: string }[];   // slug resolves via getProductBySlug()
  questions: { id: string; label: string; type: "text" | "choice"; options?: string[] }[];
  actions: { type: "create_quote" | "add_to_cart" | "whatsapp" | "book_callback"; label: string }[];
  handoff: { required: boolean; reason: string | null };
  sources: { type: "hub"; recordId: string; updatedAt: string }[];   // grounding proof
  analytics: { readiness: "low" | "medium" | "high"; stage: string };
}
```

`products[].slug` is the key that ties Neema back into `getProductBySlug()` → your existing card components. `sources[]` is what makes answers **auditable** — every price or stock claim carries the hub record it came from.

---

## 4. The SEO / AI-discovery wins you are leaving on the table

This is where you get closest to "top of the world" **fastest and cheapest**, and it is almost entirely missing today. Google's own guidance (your Appendix B) is blunt: strong foundational SEO is the basis for visibility in generative/AI search too. Your pages are server-rendered — crawlers can see them — but they carry **no structured data and thin metadata**, so neither Google's rich results nor AI answer engines (ChatGPT, Gemini, Perplexity) can confidently understand or cite them.

### Quick win 1 — Product & Offer JSON-LD (highest ROI on the site)

Add a `<script type="application/ld+json">` to the product page. You already have every field it needs on the `Product` object.

```tsx
// in app/product/[slug]/page.tsx — emit alongside the existing markup
const jsonLd = {
  "@context": "https://schema.org",
  "@type": "Product",
  name: parent.name,
  image: parent.gallery,
  description: parent.short,
  category: parent.category,
  brand: { "@type": "Brand", name: "Bethany House" },
  sku,
  offers: {
    "@type": "Offer",
    priceCurrency: "KES",
    price: parent.price,
    availability: parent.inStock === false
      ? "https://schema.org/OutOfStock"
      : "https://schema.org/InStock",
    url: `https://bethanyhouse.co.ke/product/${parent.slug}`,
  },
  // real aggregate rating ONLY when you have real reviews (see §7)
};
```
For your variable products (cassocks, chasubles), use `ProductGroup` + variant `Product`s — your catalog already models exactly this relationship (`parent.variants`), so the markup falls straight out of the data.

### Quick win 2 — `sitemap.ts` and `robots.ts` (Next.js built-ins)

```ts
// storefront/app/sitemap.ts  (NEW) — generated from the live hub catalog
import { getCatalog } from "@/lib/catalog";
export default async function sitemap() {
  const products = await getCatalog();
  const base = "https://bethanyhouse.co.ke";
  return [
    { url: base, priority: 1 },
    { url: `${base}/shop`, priority: 0.9 },
    ...products.filter(p => !p.variantId).map(p => ({
      url: `${base}/product/${p.slug}`,
      changeFrequency: "weekly" as const,
      priority: 0.8,
    })),
  ];
}
```
A `robots.ts` that points to this sitemap is ~5 lines. Together they are the difference between "Google finds pages by luck" and "Google is handed a fresh, complete map every crawl."

### Quick win 3 — real metadata

`generateMetadata` currently returns only `title`. Add `description`, `openGraph` (with the product image), `alternates.canonical`, and set `metadataBase` once in `layout.tsx`. This is what produces a proper preview when a customer shares a product on WhatsApp or Facebook — directly relevant to a business that sells through those channels.

### Quick win 4 — Organization + LocalBusiness JSON-LD (site-wide)

Emit once in `layout.tsx` from `lib/site.ts` (you already have the name, phone, address, hours, payments). This is what lets Google show your knowledge panel and lets AI assistants answer "where can I buy communion supplies in Nairobi?" **with your shop.**

> These four wins touch ~5 files, ship independently of any AI work, and are the most defensible ranking investment you can make. **Do them first — they don't wait on Neema.**

---

## 5. Think outside the box — ideas specific to *your* niche

Generic AI advice says "add a chatbot." Your business is not generic: you sell **made-to-measure sacred garments and consecrated-use items to churches across the world.** That specificity is your moat. Here are ideas that exploit it.

### 5.1 The Measurement Copilot (kills your #1 conversion killer)
Your made-to-order flow (`MeasurementForm`, the `MEASURE_ORDER` in `lib/hub.ts`) asks customers for Neck, Shoulders, Sleeves, Chest… Most customers do not know how to measure a cassock, so they abandon. Let a **vision** model (Gemini → Anthropic — the primary Groq/Llama model is text-only) turn a phone photo (person against a door, holding a reference card) into a measurement estimate, then have Neema confirm each value with the customer before it ever reaches production. This directly attacks the biggest drop-off in your funnel and is something no competitor in your category offers.

### 5.2 The Liturgical Concierge (proactive, seasonal, denomination-aware)
Vestment colors follow the church calendar — purple in Advent and Lent, white at Easter, red at Pentecost, green in Ordinary Time. Neema should **know the season** and merchandise to it: in November, surface purple stoles and Advent sets; before an ordination season, surface the ordination range you already photograph (`live-ORDINATION2.png`, `live-ORDINATION6.png`). This is "proactive-not-intrusive selling" grounded in something real and useful, not a popup.

### 5.3 Denomination & vocabulary intelligence
A "cassock" to an Anglican is a "soutane" to a Catholic; "communion wafers," "altar bread," and "hosts" are the same product; a "chasuble," "chatement," "tallit," and "prayer shawl" serve different traditions. Build a **synonym/entity layer** (in the hub, per §4's "synonyms" note) so that search, Neema, and your JSON-LD all resolve the many names customers actually type. This is simultaneously a UX win, an SEO win (you rank for every variant term), and an AI-search win (answer engines match more queries to you).

### 5.4 Answer Engine Optimization (AEO) — become the *cited source*
The next frontier past Google ranking is being **the source AI assistants quote.** When someone asks ChatGPT or Gemini "how do I measure for a clergy cassock?" or "what's the difference between a chasuble and a dalmatic?", the site with clear, structured, genuinely expert answers gets cited. You have the raw expertise (your flagship PDP already contains real craftsmanship detail). Mine **real Neema conversations** for the questions customers actually ask, have a human approve the answers, publish them as `Article`/`FAQPage` structured content, and you become the reference. This is the strategy's "content moat" made concrete and pointed at AI search specifically.

### 5.5 One Neema across WhatsApp and web (shared memory)
You already sell on WhatsApp and your `submitOnlineOrder` supports guest details. Give web-Neema and WhatsApp-Neema **one brain and one memory keyed to phone number** (with consent). A customer who asks about a chasuble on the website and continues on WhatsApp should not repeat themselves. This is the strategy's "One customer, one context" — and it's realistic because both channels already point at the same hub.

### 5.6 Visual "find this vestment" search
Let a visitor upload a photo of a vestment they admired at a service; a vision model (Gemini or Anthropic — the same multimodal chain the measurement copilot already uses) classifies it (chasuble? cope? color? orphrey style?) and Neema links the closest catalogue matches. Your product imagery is already rich and consistently named — good training/matching material.

### 5.7 Parish & Diocese bulk-quote agent
Churches buy in sets and in bulk (40-cup communion trays, robes for a whole choir, ordination classes). A Neema workflow that captures "we're robing 12 servers, here are the sizes" and produces a **structured draft quotation** for staff approval (the strategy's `create_quote_draft`) is a high-value B2B lane your consumer flow doesn't serve today.

### 5.8 Trust engine from *real* orders (see §7)
Replace the fabricated testimonials with a proof engine that surfaces **verified, consented** signals from the hub: "shipped to 14 countries," "312 chalices delivered," real reviews tied to real `order_number`s. For international buyers spending on sacred items sight-unseen, verifiable trust is the conversion lever — and it's honest.

### 5.9 Live-search → content radar
Use a live-search-capable model or search API internally (never auto-publishing) to spot rising demand — a new ordination season, a diocese's jubilee, a trending liturgical topic — and generate **content briefs** for human editors. Demand intelligence feeding the content engine, exactly as the strategy's §7 "SEO command centre" describes.

### 5.10 Abandoned-quote recovery, consented and gentle
When a customer builds a measurement/quote and leaves (your `buildOnlineOrder` already assembles the full draft), save it and — with consent — let Neema follow up on WhatsApp with the exact saved configuration. Recovering made-to-order intent is worth far more than recovering a cart of stock items.

---

## 6. Reconciling the strategy's 6-phase roadmap with where you actually are

| Strategy phase | Original framing | **Revised for your codebase** |
|---|---|---|
| Phase 1 — Foundation | Build SSR, hub APIs, gateway | SSR **done**; hub reads **done**. → **Build only the gateway + analytics baseline.** |
| Phase 2 — Neema MVP | Chat, product finder, leads, handoff | Wire `ChatFab` → `/api/neema`; reuse `getCatalog`/`fetchOrderStatus`; add `create_lead`. **This is your true starting point.** |
| Phase 3 — Commercial workflows | Quotes, variants, measurements, image enquiry | Variants/measurements **already modeled**; add quote draft + Measurement Copilot (§5.1). |
| Phase 4 — Search & content | SSR completion, schema, sitemaps | SSR done. → **Just add JSON-LD + sitemap + metadata (§4). Do this in parallel, now.** |
| Phase 5 — Personalization | Returning-visitor memory, localization | Currency **done**; add consented memory + liturgical concierge (§5.2). |
| Phase 6 — Optimization & autonomy | Model routing, experiments, controlled actions | Gateway makes model routing a config change; graduate autonomy per §7 tiers. |

**Net effect: you can compress the roadmap.** The SEO quick wins (§4) and the Neema MVP (§3) can run **in parallel from week one**, because one is pure frontend/SEO and the other is pure backend/gateway — different files, no collision.

---

## 7. Governance guardrails that apply to *your* code specifically

The strategy's §8 is sound. Three items are urgent because they touch code that exists today:

1. **Fabricated reviews are a live trust/legal risk.** `app/product/[slug]/page.tsx` hardcodes named testimonials ("Rev. Canon Mwangi," "Pastor Achieng O.") and a rating distribution. Presenting invented reviews as real is a consumer-protection problem in many of the countries you ship to, it undermines the trust you're trying to build, and it will poison any `AggregateRating` JSON-LD (which can get you a Google manual penalty). **Action:** either drive reviews from real, verified hub orders, or clearly mark sample content and omit rating schema until real reviews exist. Honesty here is also a conversion asset (§5.8).

2. **AI must never invent operational facts.** Prices, stock, and shipping in Neema's answers must come from `getCatalog()` / the hub, carried in `NeemaReply.sources[]` — never from the model's own text. Set a confidence threshold below which Neema asks a question or hands off rather than guessing. This is the strategy's "Accuracy before eloquence," enforced at the gateway's validation step.

3. **Keep writes server-side.** `submitOnlineOrder()` currently runs in the browser calling the hub directly. That's acceptable for a human clicking "pay," but **no AI-initiated write** (create order, create lead, book callback) should ever originate client-side — route them through `/api/neema` with server-only credentials and the autonomy tiers below.

**Autonomy ladder (start Neema at the bottom, earn the way up):**

| Level | Neema may… | Example |
|---|---|---|
| 0 Inform | answer from approved knowledge only | "Chasubles come in 5 liturgical colors." |
| 1 Recommend | suggest products/actions for the human to confirm | "Shall I add the matching stole?" |
| 2 Prepare | create drafts, commit nothing | draft quote, draft lead |
| 3 Execute (controlled) | reversible, low-risk actions within limits | save basket, book callback, send approved WhatsApp |
| 4 High autonomy | mature, evaluated workflows only | routine seasonal updates, monitored |

---

## 8. Prioritized action plan

### Do now (Weeks 1–2) — parallel tracks, no dependency between them
- **SEO track (frontend only):** Product/Offer JSON-LD, Organization + LocalBusiness JSON-LD, `sitemap.ts`, `robots.ts`, real `generateMetadata` + `metadataBase`. *(§4 — ~5 files, ships independently.)*
- **AI track (backend only):** stand up `app/api/neema/route.ts` as the gateway; wrap `getCatalog`, `getProductBySlug`, `fetchOrderStatus` as tools; log every call. *(§3.)*
- **Trust track:** decide the reviews question (§7.1) before adding rating schema.

### Weeks 3–6 — Neema MVP
- Turn `ChatFab` into a real panel talking to `/api/neema` (§3.1).
- Render `NeemaReply.products[]` with your existing card components (§3.3).
- Build `create_lead` + `handoff_to_staff` on the hub; verify leads reach staff with full context.
- Ship the "Help me choose and request a quote" journey end-to-end (the strategy's recommended first use case).

### Months 2–4 — Commercial depth
- Measurement Copilot (§5.1) and quote-draft workflow.
- Liturgical Concierge seasonal merchandising (§5.2); denomination synonym layer (§5.3).
- Analytics dashboard: intent, readiness, tool success, assisted conversion, cost-per-lead (strategy §9).

### Months 4–9 — Reach & retention
- AEO content program from real Neema questions (§5.4); shared WhatsApp/web identity (§5.5).
- Consented memory, abandoned-quote recovery (§5.10), model routing + evaluation harness.

---

## 9. Decisions only you can make (narrowed to what the code forces)

1. **Where does the gateway live?** Recommended: inside the storefront app (`app/api/neema`) — you already ship a Node server, so it's free. Alternative: a separate Docker service if you want it shared with WhatsApp from day one.
2. **What may Neema execute vs. only prepare?** Pick the starting autonomy level (§7). Recommend starting at **2 (Prepare)** — draft everything, commit nothing — until evaluations are green.
3. **Reviews:** real-and-verified, or clearly-labeled-samples with no rating schema? (Blocks safe Product JSON-LD.)
4. **Which model, when?** The gateway makes this a routing policy, not an architecture decision. The storefront now ships a fallback chain — Groq (primary, `llama-3.3-70b-versatile`) → Anthropic → Gemini — with vision routed to Gemini → Anthropic; tune the order, models and per-intent routing as you learn.
5. **Consent & identity:** how web and WhatsApp identities reconcile (phone-number keyed, opt-in) — needed before §5.5 and §5.10.

---

## 10. What "top of the world" should actually mean for Bethany House

Not "the most AI features." For a specialist supplier of sacred goods selling internationally, world-class means:

- A priest in Lagos or a parish secretary in Manila can understand the product, get measured correctly, and reach a real next step **without confusion or a phone call.**
- Neema answers **accurately and commercially**, cites the hub for every fact, and hands off to a person the moment it should.
- Google and AI answer engines can crawl, understand, and **cite** your genuinely expert content — so when the world asks an AI "where do I buy a chalice / how do I measure a cassock," the answer is you.
- Your team gets **better leads and less repetitive work**, because the website did the qualifying.

**Final recommendation (unchanged from your strategy, sharpened by the code):** build the gateway and the four SEO wins first — both are small, both compound, and both are unblocked today. Prove one complete "choose → quote → handoff" journey. *Then* expand Neema across discovery, quotation, service, and content. Do not sprinkle isolated AI widgets onto the pages; build the one experience layer well, measure that it sells, and grow it.

---

### Appendix — file-level starter map

| To build | Create/edit | Reuses |
|---|---|---|
| AI gateway | `storefront/app/api/neema/route.ts` *(new)* | `lib/catalog.ts`, `lib/hub.ts` |
| Response contract | `storefront/lib/neema.ts` *(new)* | `lib/products.ts` (`Product`) |
| Neema panel UI | `components/chrome.tsx` (`ChatFab`) + new `NeemaPanel` | `ProductRail`, `cards.tsx`, `Money` |
| Product JSON-LD | `app/product/[slug]/page.tsx` | `Product`, `sku`, `SITE` |
| Org/LocalBusiness JSON-LD | `app/layout.tsx` | `lib/site.ts` (`SITE`) |
| Sitemap | `storefront/app/sitemap.ts` *(new)* | `getCatalog()` |
| Robots | `storefront/app/robots.ts` *(new)* | — |
| Metadata upgrade | `app/layout.tsx`, `app/product/[slug]/page.tsx` | `Product`, `SITE` |
| New hub tools | Bethany Hub `/api/v1` | pattern of existing `POST /storefront/orders` |

*This advisory is grounded in a read of the `storefront/` codebase as of 20 July 2026 and is meant to be read alongside the full strategy document, whose principles it endorses and whose sequencing it revises to match what you have already built.*
