"use client";

import { mergeOrders, type OrderRecord } from "./orders";
import type { Currency } from "./products";

/* ============================================================
   Passwordless "Find my orders" (bethany-house StorefrontLookupController)

     POST /storefront/otp/request  { contact }         → send a code
     POST /storefront/otp/verify   { contact, code }   → { token, orders }
     GET  /storefront/my-orders    (X-BH-Session)      → { orders }

   A guest checks out with no account; this lets them pull their full
   order history onto any device by proving control of the phone/email on
   the order. The verified session token is kept in localStorage and used
   to silently refresh on return. Never gates checkout.
   ============================================================ */

const HUB = process.env.NEXT_PUBLIC_HUB_API;
export const lookupLive = () => Boolean(HUB);

const SESSION_KEY = "bh-lookup-v1";

export interface LookupSession {
  token: string;
  hint: string;    // masked destination, e.g. "+2547•••••678"
  contact: string; // what the customer typed (to re-verify if needed)
}

export function getSession(): LookupSession | null {
  try {
    const raw = localStorage.getItem(SESSION_KEY);
    return raw ? (JSON.parse(raw) as LookupSession) : null;
  } catch {
    return null;
  }
}

export function saveSession(s: LookupSession): void {
  try { localStorage.setItem(SESSION_KEY, JSON.stringify(s)); } catch { /* ignore */ }
}

export function clearSession(): void {
  try { localStorage.removeItem(SESSION_KEY); } catch { /* ignore */ }
}

interface HubOrder {
  order_number: string;
  created_at: string;
  status: string;
  payment_status: string;
  payment_method: string;
  currency_code: string;
  subtotal: number;
  shipping_amount: number;
  total_amount: number;
  delivery_type: string;
  payment_token?: string | null;
  payment_link?: string | null;
  customer: { first_name?: string; last_name?: string; phone?: string; email?: string };
  shipping: { address?: string | null; city?: string | null; country?: string | null };
  items: Array<{ name: string; variant_name?: string | null; quantity: number; unit_price: number; total_price: number; notes?: string | null }>;
  shipment?: { tracking_url?: string | null } | null;
}

const PAY_METHODS = ["mpesa", "card", "cash_on_delivery"] as const;

/** Recover structured measurements / size from a hub order-item note. */
function parseNotes(notes?: string | null): { measurements?: Record<string, string>; size?: string } {
  if (!notes) return {};
  const m = notes.match(/Measurements:\s*(.+)$/i);
  if (m) {
    const measurements: Record<string, string> = {};
    for (const pair of m[1].split(",")) {
      const [k, ...v] = pair.split(":");
      if (k && v.length) measurements[k.trim()] = v.join(":").trim();
    }
    return { measurements };
  }
  const s = notes.match(/Size\s+(.+)$/i);
  return s ? { size: s[1].trim() } : {};
}

function hubToRecord(o: HubOrder): OrderRecord {
  const method = (PAY_METHODS as readonly string[]).includes(o.payment_method)
    ? (o.payment_method as OrderRecord["paymentMethod"])
    : "mpesa";
  return {
    ref: o.order_number,
    placedAt: o.created_at,
    currency: (o.currency_code === "USD" ? "USD" : "KES") as Currency,
    items: o.items.map((i) => ({ slug: "", name: i.name, qty: i.quantity, unit: i.unit_price, ...parseNotes(i.notes) })),
    subtotal: o.subtotal,
    delivery: o.shipping_amount,
    total: o.total_amount,
    paymentMethod: method,
    deliveryMethod: o.delivery_type === "pickup" ? "pickup" : "delivery",
    customer: { name: `${o.customer.first_name ?? ""} ${o.customer.last_name ?? ""}`.trim(), phone: o.customer.phone ?? "" },
    address: o.shipping?.address ?? undefined,
    city: o.shipping?.city ?? undefined,
    countryCode: o.shipping?.country ?? "KE",
    paymentLink: o.payment_link ?? undefined,
    paymentToken: o.payment_token ?? undefined,
    email: o.customer.email ?? undefined,
    status: o.status,
    paymentStatus: o.payment_status,
    trackingUrl: o.shipment?.tracking_url ?? undefined,
  };
}

export interface RequestResult { ok: boolean; status: number; destination?: string; channels?: string[]; message?: string }

export async function requestCode(contact: string): Promise<RequestResult> {
  if (!HUB) return { ok: false, status: 0, message: "Order lookup isn't available right now." };
  try {
    const r = await fetch(`${HUB}/storefront/otp/request`, {
      method: "POST",
      headers: { "Content-Type": "application/json", Accept: "application/json" },
      body: JSON.stringify({ contact }),
    });
    const data = await r.json().catch(() => ({}));
    return { ok: r.ok, status: r.status, destination: data.destination, channels: data.channels, message: data.message };
  } catch {
    return { ok: false, status: 0, message: "Network error — please try again." };
  }
}

export interface VerifyResult { ok: boolean; token?: string; orders?: OrderRecord[]; message?: string }

export async function verifyCode(contact: string, code: string): Promise<VerifyResult> {
  if (!HUB) return { ok: false, message: "Order lookup isn't available right now." };
  try {
    const r = await fetch(`${HUB}/storefront/otp/verify`, {
      method: "POST",
      headers: { "Content-Type": "application/json", Accept: "application/json" },
      body: JSON.stringify({ contact, code }),
    });
    const data = await r.json().catch(() => ({}));
    if (!r.ok) return { ok: false, message: data.message ?? "That code is invalid or has expired." };
    return { ok: true, token: data.token, orders: (data.orders as HubOrder[]).map(hubToRecord) };
  } catch {
    return { ok: false, message: "Network error — please try again." };
  }
}

/** Silent refresh with a stored session token; null if the session is gone. */
export async function refreshMyOrders(token: string): Promise<OrderRecord[] | null> {
  if (!HUB) return null;
  try {
    const r = await fetch(`${HUB}/storefront/my-orders`, {
      headers: { "X-BH-Session": token, Accept: "application/json" },
      cache: "no-store",
    });
    if (!r.ok) return null;
    const data = await r.json();
    return (data.orders as HubOrder[]).map(hubToRecord);
  } catch {
    return null;
  }
}

/** Merge hub orders into local history and return the fresh list. */
export function syncOrders(records: OrderRecord[]): OrderRecord[] {
  return mergeOrders(records);
}
