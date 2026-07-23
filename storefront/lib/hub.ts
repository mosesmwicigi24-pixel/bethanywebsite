import type { CartItem } from "./cart";
import type { Currency, Product } from "./products";

/* ============================================================
   Hub integration (bethany-house Laravel backend, /api/v1)

   Guest checkout bridge (bethany-house PR #141):
     POST /storefront/orders — one-shot online order with items, guest
     customer details and structured measurements. The hub resolves the
     currency (Order::resolveCurrency: 'KE' -> KES, else USD), creates
     the order as order_type='online', RAISES a draft production order
     per made-to-order line (measurements validated against the
     product's template), and returns { order, payment_link } — the
     customer completes payment on the hub's /pay/{token} page
     (M-Pesa STK / Paystack / COD).

   We mirror the currency rule client-side for display only; the hub's
   resolution from country_code is authoritative. Requests carry a
   client_request_id so retries are idempotent.
   ============================================================ */

const HUB = process.env.NEXT_PUBLIC_HUB_API; // e.g. https://hub.bethanyhouse.co.ke/api/v1
export const hubLive = () => Boolean(HUB);

export interface CheckoutPayload {
  delivery_method: "delivery" | "pickup";
  payment_method: "mpesa" | "card" | "cash_on_delivery";
  phone?: string;
  notes: string;
  country_code: string; // ISO-2 — drives KES/USD on the hub
  address?: string;
  city?: string;
}

export interface OnlineOrderDraft {
  order_type: "online";
  currency_code: Currency;
  country_code: string;
  customer: { first_name: string; last_name: string; phone: string; email?: string; church?: string };
  items: Array<{
    slug: string;        // hub product slug the order line resolves to
    variant_id?: number; // hub product_variants.id when a variant was chosen
    product_name: string;
    quantity: number;
    unit_price: number;
    requires_production: boolean;
    measurements?: Record<string, string>;
    size?: string;
  }>;
  checkout: CheckoutPayload;
}

/** Serialize measurements the way hub staff read them
    (canonical key order used across the hub's production screens). */
const MEASURE_ORDER = ["Neck", "Shoulders", "Sleeves", "Wrist", "Chest", "Stomach", "Waist", "Hips", "Shirt Length", "Full Length"];
export function measurementsToNote(m: Record<string, string>): string {
  const keys = Object.keys(m).filter((k) => m[k]?.trim());
  keys.sort((a, b) => {
    const ia = MEASURE_ORDER.indexOf(a), ib = MEASURE_ORDER.indexOf(b);
    return (ia === -1 ? 99 : ia) - (ib === -1 ? 99 : ib);
  });
  return keys.map((k) => `${k}: ${m[k].trim()}`).join(", ");
}

/** Build the notes block staff see on the online order. */
export function buildOrderNotes(items: CartItem[], resolve: (slug: string) => Product | undefined, church?: string): string {
  const lines: string[] = [];
  if (church?.trim()) lines.push(`Church/parish: ${church.trim()}`);
  for (const i of items) {
    const p = resolve(i.slug);
    if (i.measurements) {
      lines.push(`MADE TO ORDER — ${p?.name ?? i.slug} ×${i.qty} — measurements (in): ${measurementsToNote(i.measurements)}`);
    } else if (i.size) {
      lines.push(`READY-MADE — ${p?.name ?? i.slug} ×${i.qty} — Size ${i.size}`);
    }
  }
  return lines.join("\n");
}

/** Assemble the full draft of what goes to the hub for this order. */
export function buildOnlineOrder(
  items: CartItem[],
  opts: {
    currency: Currency; countryCode: string;
    firstName: string; lastName: string; phone: string; email?: string; church?: string;
    deliveryMethod: "delivery" | "pickup";
    paymentMethod: "mpesa" | "card" | "cash_on_delivery";
    address?: string;
    city?: string;
    resolve: (slug: string) => Product | undefined;
  },
): OnlineOrderDraft {
  return {
    order_type: "online",
    currency_code: opts.currency,
    country_code: opts.countryCode,
    customer: { first_name: opts.firstName, last_name: opts.lastName, phone: opts.phone, email: opts.email, church: opts.church },
    items: items.map((i) => {
      const p = opts.resolve(i.slug);
      return {
        slug: p?.baseSlug ?? i.slug,
        variant_id: p?.variantId,
        product_name: p?.name ?? i.slug,
        quantity: i.qty,
        unit_price: p ? (opts.currency === "KES" ? p.price : p.priceUsd) : 0,
        requires_production: Boolean(i.measurements),
        measurements: i.measurements,
        size: i.size,
      };
    }),
    checkout: {
      delivery_method: opts.deliveryMethod,
      payment_method: opts.paymentMethod,
      phone: opts.phone,
      notes: buildOrderNotes(items, opts.resolve, opts.church),
      country_code: opts.countryCode,
      address: opts.address,
      city: opts.city,
    },
  };
}

/** Best-effort live submission to the hub's guest-checkout bridge
    (POST /api/v1/storefront/orders — bethany-house PR #141). Returns the
    hub's order number + payment link when configured and reachable; null
    means demo mode (no hub URL set, or the call failed) and the
    storefront confirms locally instead. */
export async function submitOnlineOrder(draft: OnlineOrderDraft): Promise<{ orderNumber: string; paymentLink?: string; paymentToken?: string } | null> {
  if (!HUB) return null;
  try {
    const body = {
      client_request_id: crypto.randomUUID(),
      country_code: draft.country_code,
      customer: {
        first_name: draft.customer.first_name,
        last_name: draft.customer.last_name,
        phone: draft.customer.phone,
        email: draft.customer.email || undefined,
        church: draft.customer.church || undefined,
      },
      delivery: {
        method: draft.checkout.delivery_method,
        address: draft.checkout.address,
        city: draft.checkout.city,
      },
      payment_method: draft.checkout.payment_method,
      notes: draft.checkout.notes || undefined,
      items: draft.items.map((i) => ({
        slug: i.slug,
        variant_id: i.variant_id,
        quantity: i.quantity,
        measurements: i.measurements,
        size: i.size,
      })),
    };
    const r = await fetch(`${HUB}/storefront/orders`, {
      method: "POST",
      headers: { "Content-Type": "application/json", Accept: "application/json" },
      body: JSON.stringify(body),
    });
    if (!r.ok) return null;
    const data = await r.json();
    return { orderNumber: data.order?.order_number, paymentLink: data.payment_link ?? undefined, paymentToken: data.order?.payment_token ?? undefined };
  } catch {
    return null;
  }
}

export interface HubOrderStatus {
  order_number: string;
  status: string;
  payment_status: string;
  invoice_number?: string | null;
  payment_link?: string | null;
  shipment?: {
    status: string;
    carrier?: string | null;
    tracking_number?: string | null;
    tracking_url?: string | null;
    estimated_delivery_date?: string | null;
  } | null;
}

/** Live order state from the hub (GET /storefront/orders/{payment_token}).
    Powers the receipt page's live tracker; null when hub is unreachable. */
export async function fetchOrderStatus(paymentToken: string): Promise<HubOrderStatus | null> {
  if (!HUB) return null;
  try {
    const r = await fetch(`${HUB}/storefront/orders/${encodeURIComponent(paymentToken)}`, {
      headers: { Accept: "application/json" },
      cache: "no-store",
    });
    if (!r.ok) return null;
    return (await r.json()) as HubOrderStatus;
  } catch {
    return null;
  }
}

/* ============================================================
   Neema lead capture — Bethany Hub (LIVE on hub.bethanyhouse.co.ke).

     POST /api/v1/storefront/leads
     {
       client_request_id,            // idempotency (any string; replays return the same lead)
       intent,                       // "quote" | "product_inquiry" | ... (unknown -> stored as "other")
       readiness,                    // "low" | "medium" | "high" (quote/high notifies hub owners)
       customer: { name, phone, email?, church? },   // phone is the only required field
       location: { country_code?, city? },
       products: ["slug", ...],      // catalogue interest
       quantity?, message?,          // free text / summary
       source_path?                  // page the enquiry came from
     }
     → { lead: { id } }              // 201, or 200 on idempotent replay

   On any error (or before NEXT_PUBLIC_HUB_API is set) createLead() returns
   null and the gateway falls back to a WhatsApp handoff (see
   app/api/neema/lead) — so no qualified lead is ever dropped. Writes are
   server-side only (called from the AI gateway with least-privilege), never
   from the browser.
   ============================================================ */

export interface LeadDraft {
  /** Stable idempotency key for this submission — reused across retries so a
      double-submit dedupes to one lead at the hub. Defaults to a fresh UUID. */
  clientRequestId?: string;
  intent: string;
  readiness?: "low" | "medium" | "high";
  name?: string;
  phone: string;
  email?: string;
  church?: string;
  countryCode?: string;
  city?: string;
  products?: string[];
  quantity?: string;
  message?: string;
  sourcePath?: string;
}

/** Best-effort lead submission to the hub. Returns the created lead id when
    configured and reachable; null means the hub isn't ready (or the call
    failed) and the caller should hand off to staff instead. */
export async function createLead(draft: LeadDraft): Promise<{ leadId: string } | null> {
  if (!HUB) return null;
  try {
    const body = {
      client_request_id: draft.clientRequestId || crypto.randomUUID(),
      intent: draft.intent,
      readiness: draft.readiness,
      customer: {
        name: draft.name || undefined,
        phone: draft.phone,
        email: draft.email || undefined,
        church: draft.church || undefined,
      },
      location: { country_code: draft.countryCode || undefined, city: draft.city || undefined },
      products: draft.products?.length ? draft.products : undefined,
      quantity: draft.quantity || undefined,
      message: draft.message || undefined,
      source_path: draft.sourcePath || undefined,
    };
    const r = await fetch(`${HUB}/storefront/leads`, {
      method: "POST",
      headers: { "Content-Type": "application/json", Accept: "application/json" },
      body: JSON.stringify(body),
    });
    if (!r.ok) return null;
    const data = await r.json().catch(() => ({}));
    const leadId = data.lead?.id ?? data.id;
    return leadId ? { leadId: String(leadId) } : null;
  } catch {
    return null;
  }
}

/* ============================================================
   Shipping estimate — Bethany Hub (LIVE on hub.bethanyhouse.co.ke).

     GET /api/v1/storefront/shipping/estimate
       ?country_code=UG&city=Kampala&items=slug1,slug2
     → {
         destination: "Kampala, Uganda",
         options: [{ service, range, cost? }, ...],   // always an array; KES for Kenya, USD elsewhere
         note?: string                                // customs/duties note internationally
       }

   Driven from the hub's real country shipping data. Unknown / unrated /
   shipping-disabled destinations return options: [] with a note, so we still
   show the destination and route to staff. On any error (or before
   NEXT_PUBLIC_HUB_API is set) estimateShipping() returns null and Neema
   explains worldwide shipping and captures the destination for a staff quote
   instead — so the customer always gets a next step (advisory §3, §5.5).
   ============================================================ */

export interface ShippingQuery {
  countryCode?: string;
  country?: string;
  city?: string;
  items?: string[];
}

export interface ShippingEstimate {
  destination: string;
  options: { service: string; range: string; cost?: string }[];
  note?: string;
}

/** Best-effort international shipping estimate. Null when the hub isn't
    ready (or the call failed) — the gateway then captures the destination
    and hands off to staff for a precise quote. */
export async function estimateShipping(q: ShippingQuery): Promise<ShippingEstimate | null> {
  if (!HUB) return null;
  try {
    const params = new URLSearchParams();
    if (q.countryCode) params.set("country_code", q.countryCode);
    if (q.country) params.set("country", q.country);
    if (q.city) params.set("city", q.city);
    if (q.items?.length) params.set("items", q.items.join(","));
    const r = await fetch(`${HUB}/storefront/shipping/estimate?${params.toString()}`, {
      headers: { Accept: "application/json" },
      cache: "no-store",
    });
    if (!r.ok) return null;
    const data = (await r.json()) as ShippingEstimate;
    return data && Array.isArray(data.options) ? data : null;
  } catch {
    return null;
  }
}

/* ============================================================
   Interest ledger — the cross-channel cart (durable, never deleted).

   Every cart is an *expression of interest* by a customer, keyed to a
   short cross-channel token (BH-XXXX). We upsert it to the Hub as items
   change, and mark its outcome when it resolves — online_order (paid on
   the web), whatsapp_order (finished on WhatsApp), or abandoned. Because
   the row is never deleted, the ledger traces a customer's interest over
   time and lets Neema resume the same cart when a customer moves between
   the website, WhatsApp, Messenger and Instagram.

   Server-side only, like createLead(). All best-effort: until the Hub
   endpoints exist (or NEXT_PUBLIC_HUB_API is set) these return null/false
   and the cart still works exactly as before — nothing is dropped.

   Hub-side (see docs/HUB_CONTRACT.md §7): the row is keyed/upserted on
   `token`; POST upserts the active cart, PATCH transitions its status.
   ============================================================ */

/** Shared headers for the server-to-server Hub calls — carries the
    X-Storefront-Key when HUB_STOREFRONT_KEY is set (dormant until then). */
function hubHeaders(): Record<string, string> {
  const h: Record<string, string> = { "Content-Type": "application/json", Accept: "application/json" };
  const key = process.env.HUB_STOREFRONT_KEY;
  if (key) h["X-Storefront-Key"] = key;
  return h;
}

export type InterestChannel = "web" | "whatsapp" | "messenger" | "instagram" | "facebook";
export type InterestStatus = "active_cart" | "checkout_started";
export type InterestOutcome = "online_order" | "whatsapp_order" | "abandoned";

export interface InterestItem {
  slug: string;
  quantity: number;
  measurements?: Record<string, string>;
  size?: string;
}

export interface InterestCartPayload {
  token: string;                 // BH-XXXX — the cross-channel handle (Hub upserts on this)
  channel: InterestChannel;
  visitorId?: string;            // bh_vid — durable per-visitor anchor (groups a visitor's carts)
  sessionId?: string;            // bh-neema-sid — the chat session (links cart ↔ chat)
  status?: InterestStatus;       // default active_cart
  items: InterestItem[];
  subtotal?: number;
  currency?: string;
  customer?: { name?: string; phone?: string; church?: string };
  sourcePath?: string;
}

/** Upsert the live cart into the Hub interest ledger (POST /storefront/interest-carts).
    Keyed on `token`; last write wins. Returns the token on success, null otherwise. */
export async function upsertInterestCart(p: InterestCartPayload): Promise<{ token: string } | null> {
  if (!HUB || !p.token || !p.items?.length) return null;
  const cust = p.customer && (p.customer.phone || p.customer.name || p.customer.church) ? p.customer : undefined;
  try {
    const r = await fetch(`${HUB}/storefront/interest-carts`, {
      method: "POST",
      headers: hubHeaders(),
      body: JSON.stringify({
        client_request_id: crypto.randomUUID(),
        token: p.token,
        channel: p.channel,
        visitor_id: p.visitorId || undefined,
        session_id: p.sessionId || undefined,
        status: p.status || "active_cart",
        customer: cust,
        items: p.items,
        subtotal: typeof p.subtotal === "number" ? p.subtotal : undefined,
        currency: p.currency || undefined,
        source_path: p.sourcePath || undefined,
      }),
    });
    if (!r.ok) return null;
    return { token: p.token };
  } catch {
    return null;
  }
}

/** Transition an interest cart's outcome (PATCH /storefront/interest-carts/{token}) —
    online_order / whatsapp_order / abandoned, optionally linking the order ref and
    attaching the customer once known. Best-effort; true when the Hub accepted it. */
export async function markInterestOutcome(
  token: string,
  status: InterestOutcome,
  opts?: { orderRef?: string; customer?: { name?: string; phone?: string; church?: string } },
): Promise<boolean> {
  if (!HUB || !token) return false;
  const cust = opts?.customer && (opts.customer.phone || opts.customer.name || opts.customer.church) ? opts.customer : undefined;
  try {
    const r = await fetch(`${HUB}/storefront/interest-carts/${encodeURIComponent(token)}`, {
      method: "PATCH",
      headers: hubHeaders(),
      body: JSON.stringify({ status, order_ref: opts?.orderRef || undefined, customer: cust }),
    });
    return r.ok;
  } catch {
    return false;
  }
}
