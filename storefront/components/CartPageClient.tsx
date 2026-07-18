"use client";

import Link from "next/link";
import Crumbs from "./Crumbs";
import { Money } from "./Money";
import { useCart, FREE_DELIVERY_AT } from "@/lib/cart";
import { useCurrency } from "@/lib/currency";
import { bySlug, formatKES } from "@/lib/products";
import { measurementsToNote } from "@/lib/hub";

/** Full cart page — review every line (and its measurements) before checkout. */
export default function CartPageClient() {
  const { items, subtotal, subtotalUsd, setQty, remove, hydrated } = useCart();
  const { currency } = useCurrency();

  const toFree = Math.max(0, FREE_DELIVERY_AT - subtotal);
  const pct = Math.min(100, Math.round((subtotal / FREE_DELIVERY_AT) * 100));

  if (hydrated && items.length === 0) {
    return (
      <main className="wrap">
        <div className="confirm">
          <div className="tick">🕊️</div>
          <h1 className="serif">Your cart is empty.</h1>
          <p>Everything the altar calls for is one click away.</p>
          <div className="confirm-ctas">
            <Link className="pill pill-gold" href="/shop">Start shopping</Link>
          </div>
        </div>
      </main>
    );
  }

  return (
    <main className="wrap">
      <Crumbs items={[{ label: "Home", href: "/" }, { label: "Your Cart" }]} />
      <h1 className="serif cart-title">Your cart.</h1>

      <div className="cartpage">
        <div className="cp-lines">
          {items.map((i) => {
            const p = bySlug(i.slug);
            if (!p) return null;
            return (
              <article className="cp-line" key={i.key}>
                <Link className="im" href={`/product/${p.slug}`}><img src={p.img} alt={p.name} /></Link>
                <div className="minw0 cp-mid">
                  <Link href={`/product/${p.slug}`}><b>{p.name}</b></Link>
                  <span className="muted-cap"><Money kes={p.price} usd={p.priceUsd} /> each
                    {p.producible ? " · Made to order, 5–7 days" : " · In stock, ships today"}</span>
                  {i.measurements && (
                    <div className="co-meas">✂ Measurements: {measurementsToNote(i.measurements)}</div>
                  )}
                  <div className="cp-actions">
                    <div className="qty sm">
                      <button aria-label="Decrease" onClick={() => setQty(i.key, i.qty - 1)}>‹</button>
                      <input value={i.qty} readOnly aria-label="Quantity" />
                      <button aria-label="Increase" onClick={() => setQty(i.key, i.qty + 1)}>›</button>
                    </div>
                    <button className="cp-rm" onClick={() => remove(i.key)}>Remove</button>
                  </div>
                </div>
                <div className="cp-total"><Money kes={p.price * i.qty} usd={p.priceUsd * i.qty} /></div>
              </article>
            );
          })}
        </div>

        <aside className="co-summary">
          <h3>Order summary</h3>
          {currency === "KES" && (
            <div className="cart-free" style={{ borderRadius: 12, marginBottom: 14 }}>
              {toFree > 0
                ? <>Add <b>{formatKES(toFree)}</b> more for <b>free Nairobi delivery</b></>
                : <>✓ You&apos;ve unlocked <b>free Nairobi delivery</b></>}
              <div className="bar"><i style={{ width: `${pct}%` }} /></div>
            </div>
          )}
          <div className="co-line"><span>Subtotal</span><span><Money kes={subtotal} usd={subtotalUsd} /></span></div>
          <div className="co-line"><span>Delivery</span><span>Calculated at checkout</span></div>
          <div className="co-line co-total"><span>Total</span><span><Money kes={subtotal} usd={subtotalUsd} /></span></div>
          <Link className="pill pill-gold" style={{ width: "100%", marginTop: 16 }} href="/checkout">Proceed to checkout</Link>
          <Link className="pill pill-ghost" style={{ width: "100%", marginTop: 10 }} href="/shop">Continue shopping</Link>
          <p className="muted-cap" style={{ marginTop: 14 }}>M-Pesa, card &amp; cash on delivery · Free engraving on communion ware</p>
        </aside>
      </div>
    </main>
  );
}
