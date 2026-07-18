"use client";

import Link from "next/link";
import { useEffect } from "react";
import { usePathname } from "next/navigation";
import { useCart, FREE_DELIVERY_AT } from "@/lib/cart";
import { bySlug, formatKES } from "@/lib/products";
import { useCurrency } from "@/lib/currency";
import { Money } from "./Money";

/** oraimo-style slide-in cart with free-delivery progress. */
export default function CartDrawer() {
  const { items, subtotal, subtotalUsd, open, setOpen, setQty, remove } = useCart();
  const { currency } = useCurrency();
  const pathname = usePathname();

  useEffect(() => { setOpen(false); }, [pathname, setOpen]);

  useEffect(() => {
    document.body.style.overflow = open ? "hidden" : "";
    const onKey = (e: KeyboardEvent) => e.key === "Escape" && setOpen(false);
    window.addEventListener("keydown", onKey);
    return () => {
      document.body.style.overflow = "";
      window.removeEventListener("keydown", onKey);
    };
  }, [open, setOpen]);

  const toFree = Math.max(0, FREE_DELIVERY_AT - subtotal);
  const pct = Math.min(100, Math.round((subtotal / FREE_DELIVERY_AT) * 100));

  return (
    <div className={`cart-veil ${open ? "open" : ""}`} onClick={() => setOpen(false)}>
      <aside className="cart-panel" role="dialog" aria-modal="true" aria-label="Shopping cart" onClick={(e) => e.stopPropagation()}>
        <div className="cart-head">
          <span>Your Cart</span>
          <button aria-label="Close cart" onClick={() => setOpen(false)}>✕</button>
        </div>

        {items.length > 0 && currency === "KES" && (
          <div className="cart-free">
            {toFree > 0
              ? <>Add <b>{formatKES(toFree)}</b> more for <b>free Nairobi delivery</b></>
              : <>✓ You&apos;ve unlocked <b>free Nairobi delivery</b></>}
            <div className="bar"><i style={{ width: `${pct}%` }} /></div>
          </div>
        )}

        <div className="cart-items">
          {items.length === 0 && (
            <div className="cart-empty">
              <span className="big">🕊️</span>
              <p>Your cart is empty.</p>
              <Link className="pill pill-gold" href="/shop" onClick={() => setOpen(false)}>Start shopping</Link>
            </div>
          )}
          {items.map((i) => {
            const p = bySlug(i.slug);
            if (!p) return null;
            return (
              <div className="cart-item" key={i.key}>
                <Link className="im" href={`/product/${p.slug}`} onClick={() => setOpen(false)}>
                  <img src={p.img} alt="" />
                </Link>
                <div className="minw0" style={{ flex: 1 }}>
                  <b>{p.short}</b>
                  <span className="muted-cap"><Money kes={p.price} usd={p.priceUsd} /></span>
                  {i.measurements && (
                    <span className="mto-chip">✂ Made to order · measurements attached</span>
                  )}
                  {i.size && (
                    <span className="mto-chip ready">Size {i.size} · ready-made</span>
                  )}
                  <div className="qty sm">
                    <button aria-label="Decrease" onClick={() => setQty(i.key, i.qty - 1)}>‹</button>
                    <input value={i.qty} readOnly aria-label="Quantity" />
                    <button aria-label="Increase" onClick={() => setQty(i.key, i.qty + 1)}>›</button>
                  </div>
                </div>
                <div className="cart-right">
                  <b><Money kes={p.price * i.qty} usd={p.priceUsd * i.qty} /></b>
                  <button className="rm" onClick={() => remove(i.key)}>Remove</button>
                </div>
              </div>
            );
          })}
        </div>

        {items.length > 0 && (
          <div className="cart-foot">
            <div className="cart-sub"><span>Subtotal</span><span><Money kes={subtotal} usd={subtotalUsd} /></span></div>
            <Link className="pill pill-gold" style={{ width: "100%" }} href="/checkout" onClick={() => setOpen(false)}>
              Checkout
            </Link>
            <Link className="pill pill-ghost" style={{ width: "100%", marginTop: 10 }} href="/cart" onClick={() => setOpen(false)}>
              View full cart
            </Link>

          </div>
        )}
      </aside>
    </div>
  );
}
