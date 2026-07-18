"use client";

import { ReactNode, createContext, useCallback, useContext, useEffect, useMemo, useState } from "react";
import { bySlug } from "./products";

export interface CartItem {
  slug: string;
  qty: number;
}

interface CartCtx {
  items: CartItem[];
  count: number;
  subtotal: number;
  open: boolean;
  hydrated: boolean;
  add: (slug: string, qty?: number) => void;
  setQty: (slug: string, qty: number) => void;
  remove: (slug: string) => void;
  clear: () => void;
  setOpen: (o: boolean) => void;
}

const Ctx = createContext<CartCtx | null>(null);

export function useCart(): CartCtx {
  const c = useContext(Ctx);
  if (!c) throw new Error("useCart must be used inside <CartProvider>");
  return c;
}

const KEY = "bh-cart-v1";

export function CartProvider({ children }: { children: ReactNode }) {
  const [items, setItems] = useState<CartItem[]>([]);
  const [open, setOpen] = useState(false);
  const [hydrated, setHydrated] = useState(false);

  useEffect(() => {
    try {
      const raw = localStorage.getItem(KEY);
      if (raw) setItems(JSON.parse(raw));
    } catch { /* corrupted storage — start fresh */ }
    setHydrated(true);
  }, []);

  useEffect(() => {
    if (hydrated) localStorage.setItem(KEY, JSON.stringify(items));
  }, [items, hydrated]);

  const add = useCallback((slug: string, qty = 1) => {
    setItems((prev) => {
      const found = prev.find((i) => i.slug === slug);
      return found
        ? prev.map((i) => (i.slug === slug ? { ...i, qty: i.qty + qty } : i))
        : [...prev, { slug, qty }];
    });
    setOpen(true);
  }, []);

  const setQty = useCallback((slug: string, qty: number) => {
    setItems((prev) =>
      qty <= 0 ? prev.filter((i) => i.slug !== slug)
        : prev.map((i) => (i.slug === slug ? { ...i, qty } : i)),
    );
  }, []);

  const remove = useCallback((slug: string) => {
    setItems((prev) => prev.filter((i) => i.slug !== slug));
  }, []);

  const clear = useCallback(() => setItems([]), []);

  const { count, subtotal } = useMemo(() => {
    let count = 0, subtotal = 0;
    for (const i of items) {
      const p = bySlug(i.slug);
      if (!p) continue;
      count += i.qty;
      subtotal += p.price * i.qty;
    }
    return { count, subtotal };
  }, [items]);

  return (
    <Ctx.Provider value={{ items, count, subtotal, open, hydrated, add, setQty, remove, clear, setOpen }}>
      {children}
    </Ctx.Provider>
  );
}

/** Free Nairobi delivery threshold (KES). */
export const FREE_DELIVERY_AT = 2000;
export const DELIVERY_FEE = 300;
