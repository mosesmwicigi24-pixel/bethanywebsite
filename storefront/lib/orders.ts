"use client";

import type { Currency } from "./products";

/* Local order history (localStorage) — the customer's copy of every
   order they place, powering /orders and the /order/[ref] receipt.
   The hub keeps the authoritative record; this is the customer-side
   mirror so a receipt is always one tap away. */

export interface OrderRecord {
  ref: string;
  placedAt: string; // ISO
  currency: Currency;
  items: Array<{
    slug: string;
    name: string;
    qty: number;
    unit: number;         // in order currency
    measurements?: Record<string, string>;
    size?: string;
  }>;
  subtotal: number;
  delivery: number;
  total: number;
  paymentMethod: "mpesa" | "card" | "cash_on_delivery";
  deliveryMethod: "delivery" | "pickup";
  customer: { name: string; phone: string; church?: string };
  address?: string;
  city?: string;
  countryCode: string;
  paymentLink?: string;
  paymentToken?: string;
  email?: string;
  // Populated when the order is synced from the hub (Find my orders):
  status?: string;         // hub order status (pending, confirmed, …)
  paymentStatus?: string;  // hub payment status (pending, paid, …)
  trackingUrl?: string;    // public shipment tracking page, once shipped
}

const KEY = "bh-orders-v1";

export function saveOrder(order: OrderRecord): void {
  try {
    const all = getOrders().filter((o) => o.ref !== order.ref);
    localStorage.setItem(KEY, JSON.stringify([order, ...all].slice(0, 50)));
  } catch { /* private mode etc. — receipt still shows via state */ }
}

/** Upsert a batch of orders (from the hub) into local history, newest first.
    Hub fields win, but any local-only fields on an existing record are kept. */
export function mergeOrders(incoming: OrderRecord[]): OrderRecord[] {
  try {
    const byRef = new Map<string, OrderRecord>();
    for (const o of getOrders()) byRef.set(o.ref, o);
    for (const o of incoming) byRef.set(o.ref, { ...byRef.get(o.ref), ...o });
    const merged = Array.from(byRef.values())
      .sort((a, b) => +new Date(b.placedAt) - +new Date(a.placedAt))
      .slice(0, 50);
    localStorage.setItem(KEY, JSON.stringify(merged));
    return merged;
  } catch {
    return getOrders();
  }
}

export function getOrders(): OrderRecord[] {
  try {
    const raw = localStorage.getItem(KEY);
    return raw ? (JSON.parse(raw) as OrderRecord[]) : [];
  } catch {
    return [];
  }
}

export function getOrder(ref: string): OrderRecord | null {
  return getOrders().find((o) => o.ref === ref) ?? null;
}

export const payLabel: Record<OrderRecord["paymentMethod"], string> = {
  mpesa: "M-Pesa",
  card: "Card (Visa / Mastercard)",
  cash_on_delivery: "Cash on Delivery",
};

const titleCase = (s: string): string =>
  s.replace(/[_-]+/g, " ").replace(/\b\w/g, (c) => c.toUpperCase());

export const statusFor = (o: OrderRecord): string => {
  // Synced-from-hub orders carry the authoritative status.
  if (o.paymentStatus === "paid") {
    return o.status && o.status !== "completed" ? `Paid · ${titleCase(o.status)}` : "Paid";
  }
  if (o.status && !["pending", "processing"].includes(o.status)) {
    return titleCase(o.status);
  }
  return o.paymentMethod === "cash_on_delivery"
    ? "Processing · pay on delivery"
    : "Processing · awaiting payment";
};
