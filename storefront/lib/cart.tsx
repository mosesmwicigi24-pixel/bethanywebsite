"use client";

import { ReactNode, createContext, useCallback, useContext, useEffect, useMemo, useState } from "react";
import { useCatalog } from "./catalogClient";
import { cartToken, postInterest, neemaSession } from "./interest";

export interface CartItem {
  /** unique line key — producible items with measurements get their own line */
  key: string;
  slug: string;
  qty: number;
  /** hub: production order measurements captured from the customer */
  measurements?: Record<string, string>;
  /** ready-made standard size (stocked line, no production) */
  size?: string;
}

interface CartCtx {
  items: CartItem[];
  count: number;
  subtotal: number;      // KES
  subtotalUsd: number;   // USD
  open: boolean;
  hydrated: boolean;
  /** cross-channel interest token (BH-XXXX) for this browser's cart */
  token: string;
  add: (slug: string, qty?: number, measurements?: Record<string, string>, size?: string) => void;
  setQty: (key: string, qty: number) => void;
  remove: (key: string) => void;
  clear: () => void;
  setOpen: (o: boolean) => void;
}

const Ctx = createContext<CartCtx | null>(null);

export function useCart(): CartCtx {
  const c = useContext(Ctx);
  if (!c) throw new Error("useCart must be used inside <CartProvider>");
  return c;
}

const KEY = "bh-cart-v2";

export function CartProvider({ children }: { children: ReactNode }) {
  const [items, setItems] = useState<CartItem[]>([]);
  const [open, setOpen] = useState(false);
  const [hydrated, setHydrated] = useState(false);
  const [token, setToken] = useState("");

  useEffect(() => {
    try {
      const raw = localStorage.getItem(KEY);
      if (raw) setItems(JSON.parse(raw));
    } catch { /* corrupted storage — start fresh */ }
    setToken(cartToken());
    setHydrated(true);
  }, []);

  useEffect(() => {
    if (hydrated) localStorage.setItem(KEY, JSON.stringify(items));
  }, [items, hydrated]);

  const add = useCallback((slug: string, qty = 1, measurements?: Record<string, string>, size?: string) => {
    setItems((prev) => {
      if (measurements && Object.keys(measurements).length > 0) {
        // each measured item is its own production line
        return [...prev, { key: `${slug}#${prev.length}-${Math.random().toString(36).slice(2, 7)}`, slug, qty, measurements }];
      }
      // ready-made lines merge per slug+size
      const key = size ? `${slug}@${size}` : slug;
      const found = prev.find((i) => i.key === key);
      return found
        ? prev.map((i) => (i === found ? { ...i, qty: i.qty + qty } : i))
        : [...prev, { key, slug, qty, size }];
    });
    setOpen(true);
  }, []);

  const setQty = useCallback((key: string, qty: number) => {
    setItems((prev) =>
      qty <= 0 ? prev.filter((i) => i.key !== key)
        : prev.map((i) => (i.key === key ? { ...i, qty } : i)),
    );
  }, []);

  const remove = useCallback((key: string) => {
    setItems((prev) => prev.filter((i) => i.key !== key));
  }, []);

  const clear = useCallback(() => setItems([]), []);

  const { bySlug } = useCatalog();
  const { count, subtotal, subtotalUsd } = useMemo(() => {
    let count = 0, subtotal = 0, subtotalUsd = 0;
    for (const i of items) {
      const p = bySlug(i.slug);
      if (!p) continue;
      count += i.qty;
      subtotal += p.price * i.qty;
      subtotalUsd += p.priceUsd * i.qty;
    }
    return { count, subtotal, subtotalUsd };
  }, [items, bySlug]);

  // Mirror the cart to the Hub interest ledger (server-side, via /api/cart),
  // debounced so a burst of edits sends one write. Keyed by the cross-channel
  // token so WhatsApp/Messenger can resume the same cart. Best-effort — a
  // failure just means this beat isn't mirrored; the cart still works locally.
  useEffect(() => {
    if (!hydrated || !token || items.length === 0) return;
    const id = setTimeout(() => {
      postInterest({
        token,
        sessionId: neemaSession(),
        items: items.map((i) => ({ slug: i.slug, quantity: i.qty, measurements: i.measurements, size: i.size })),
        subtotal,
        currency: "KES",
        sourcePath: typeof location !== "undefined" ? location.pathname : undefined,
      });
    }, 1200);
    return () => clearTimeout(id);
  }, [items, hydrated, token, subtotal]);

  return (
    <Ctx.Provider value={{ items, count, subtotal, subtotalUsd, open, hydrated, token, add, setQty, remove, clear, setOpen }}>
      {children}
    </Ctx.Provider>
  );
}

/** Free Nairobi CBD delivery threshold (KES). */
export const FREE_DELIVERY_AT = 10000;
export const DELIVERY_FEE = 300;
