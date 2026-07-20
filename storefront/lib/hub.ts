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
   Naema lead capture (Bethany Hub — endpoint to be added).

   Proposed contract, mirroring the guest-checkout bridge above:

     POST /api/v1/storefront/leads
     {
       client_request_id,            // idempotency for retries
       intent,                       // "quote" | "product_inquiry" | ...
       readiness,                    // "low" | "medium" | "high"
       customer: { name, phone, email?, church? },
       location: { country_code?, city? },
       products: ["slug", ...],      // catalogue interest
       quantity?, message?,          // free text / summary
       source_path?                  // page the enquiry came from
     }
     → { lead: { id } }

   Until the hub ships this endpoint, createLead() returns null and the
   gateway falls back to a WhatsApp handoff (see app/api/naema/lead) — so
   no qualified lead is ever dropped. Writes are server-side only (called
   from the AI gateway with least-privilege), never from the browser.
   ============================================================ */

export interface LeadDraft {
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
      client_request_id: crypto.randomUUID(),
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
