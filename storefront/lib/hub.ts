import type { CartItem } from "./cart";
import { bySlug, type Currency } from "./products";

/* ============================================================
   Hub integration (bethany-house Laravel backend, /api/v1)

   Live flow the hub accepts (verified against backend source):
     1. POST /auth/register            -> Sanctum token
     2. POST /cart/items               -> server-side cart (variant ids)
     3. POST /checkout                 -> order_type='online', returns
                                          { order, payment_link: FRONTEND/pay/{token} }
     4. customer pays at /pay/{token}  -> M-Pesa STK / Paystack / COD

   Currency is resolved SERVER-SIDE from country_code
   (Order::resolveCurrency: 'KE' -> KES, else USD). We mirror it for
   display only; the hub's answer is authoritative.

   Made-to-order: there is no public endpoint that accepts structured
   measurements — they travel in the order notes in the staff-readable
   format below, and staff raise the production_order from the line
   (order_items.requires_production / production_order_id).
   ============================================================ */

const HUB = process.env.NEXT_PUBLIC_HUB_API; // e.g. https://hub.bethanyhouse.co.ke/api/v1
export const hubLive = () => Boolean(HUB);

export interface CheckoutPayload {
  delivery_method: "delivery" | "pickup";
  payment_method: "mpesa" | "card" | "cash_on_delivery";
  phone?: string;
  notes: string;
  country_code: string; // ISO-2 — drives KES/USD on the hub
}

export interface OnlineOrderDraft {
  order_type: "online";
  currency_code: Currency;
  country_code: string;
  customer: { first_name: string; last_name: string; phone: string; church?: string };
  items: Array<{
    slug: string;
    product_name: string;
    quantity: number;
    unit_price: number;
    requires_production: boolean;
    measurements?: Record<string, string>;
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
export function buildOrderNotes(items: CartItem[], church?: string): string {
  const lines: string[] = [];
  if (church?.trim()) lines.push(`Church/parish: ${church.trim()}`);
  for (const i of items) {
    if (!i.measurements) continue;
    const p = bySlug(i.slug);
    lines.push(`MADE TO ORDER — ${p?.name ?? i.slug} ×${i.qty} — measurements (in): ${measurementsToNote(i.measurements)}`);
  }
  return lines.join("\n");
}

/** Assemble the full draft of what goes to the hub for this order. */
export function buildOnlineOrder(
  items: CartItem[],
  opts: {
    currency: Currency; countryCode: string;
    firstName: string; lastName: string; phone: string; church?: string;
    deliveryMethod: "delivery" | "pickup";
    paymentMethod: "mpesa" | "card" | "cash_on_delivery";
  },
): OnlineOrderDraft {
  return {
    order_type: "online",
    currency_code: opts.currency,
    country_code: opts.countryCode,
    customer: { first_name: opts.firstName, last_name: opts.lastName, phone: opts.phone, church: opts.church },
    items: items.map((i) => {
      const p = bySlug(i.slug);
      return {
        slug: i.slug,
        product_name: p?.name ?? i.slug,
        quantity: i.qty,
        unit_price: p ? (opts.currency === "KES" ? p.price : p.priceUsd) : 0,
        requires_production: Boolean(i.measurements),
        measurements: i.measurements,
      };
    }),
    checkout: {
      delivery_method: opts.deliveryMethod,
      payment_method: opts.paymentMethod,
      phone: opts.phone,
      notes: buildOrderNotes(items, opts.church),
      country_code: opts.countryCode,
    },
  };
}

/** Best-effort live submission. Returns the hub's payment link when the
    hub is configured and reachable; null means "demo mode" (no hub URL
    set, or the call failed) — the storefront then confirms locally. */
export async function submitOnlineOrder(draft: OnlineOrderDraft): Promise<{ orderNumber: string; paymentLink?: string } | null> {
  if (!HUB) return null;
  try {
    // The hub's flow needs a customer token + server cart. A guest-checkout
    // bridge endpoint is the planned addition on the hub; until then we POST
    // the draft to the storefront-orders inbox if it exists.
    const r = await fetch(`${HUB}/storefront/orders`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(draft),
    });
    if (!r.ok) return null;
    const data = await r.json();
    return { orderNumber: data.order?.order_number ?? data.order_number, paymentLink: data.payment_link };
  } catch {
    return null;
  }
}
