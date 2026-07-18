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
}

const KEY = "bh-orders-v1";

export function saveOrder(order: OrderRecord): void {
  try {
    const all = getOrders();
    localStorage.setItem(KEY, JSON.stringify([order, ...all].slice(0, 50)));
  } catch { /* private mode etc. — receipt still shows via state */ }
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

export const statusFor = (o: OrderRecord): string =>
  o.paymentMethod === "cash_on_delivery"
    ? "Processing · pay on delivery"
    : "Processing · awaiting payment";
