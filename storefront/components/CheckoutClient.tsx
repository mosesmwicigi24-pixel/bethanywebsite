"use client";

import Link from "next/link";
import { useState } from "react";
import Crumbs from "./Crumbs";
import { useCart, FREE_DELIVERY_AT, DELIVERY_FEE } from "@/lib/cart";
import { bySlug, formatKES } from "@/lib/products";
import { SITE } from "@/lib/site";

type Pay = "mpesa" | "card" | "cod";

export default function CheckoutClient() {
  const { items, subtotal, clear, hydrated } = useCart();
  const [pay, setPay] = useState<Pay>("mpesa");
  const [placed, setPlaced] = useState<string | null>(null);

  const delivery = subtotal >= FREE_DELIVERY_AT ? 0 : DELIVERY_FEE;
  const total = subtotal + delivery;

  const placeOrder = (e: React.FormEvent) => {
    e.preventDefault();
    const ref = `BH-${String(Math.floor(100000 + (subtotal % 900000)))}`;
    setPlaced(ref);
    clear();
    window.scrollTo({ top: 0 });
  };

  if (placed) {
    return (
      <main className="wrap">
        <div className="confirm">
          <div className="tick">✓</div>
          <h1 className="serif">Asante — order received.</h1>
          <p>Your order <b>{placed}</b> is confirmed. We&apos;ll call you within the hour to confirm delivery details{pay === "mpesa" ? " and send the M-Pesa prompt" : ""}. Questions? <b>{SITE.phone}</b>.</p>
          <div className="confirm-ctas">
            <Link className="pill pill-gold" href="/shop">Continue shopping</Link>
            <Link className="pill pill-ghost" href="/">Back home</Link>
          </div>
        </div>
      </main>
    );
  }

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
      <Crumbs items={[{ label: "Home", href: "/" }, { label: "Cart" }, { label: "Checkout" }]} />
      <form className="checkout" onSubmit={placeOrder}>
        <div className="co-form">
          <h2 className="serif">Delivery details.</h2>
          <div className="f-row2">
            <div className="field"><label htmlFor="co-name">Full name</label><input id="co-name" required placeholder="Rev. Jane Mwangi" /></div>
            <div className="field"><label htmlFor="co-phone">Phone (M-Pesa)</label><input id="co-phone" required type="tel" placeholder="07XX XXX XXX" /></div>
          </div>
          <div className="field"><label htmlFor="co-church">Church / parish <span className="muted-cap">(optional)</span></label><input id="co-church" placeholder="St. Andrew's Cathedral" /></div>
          <div className="f-row2">
            <div className="field"><label htmlFor="co-county">County</label>
              <select id="co-county" defaultValue="Nairobi">
                {["Nairobi", "Kiambu", "Machakos", "Nakuru", "Mombasa", "Kisumu", "Eldoret (Uasin Gishu)", "Other / East Africa"].map((c) => <option key={c}>{c}</option>)}
              </select>
            </div>
            <div className="field"><label htmlFor="co-addr">Delivery address</label><input id="co-addr" required placeholder="Street, building, landmark" /></div>
          </div>

          <h2 className="serif" style={{ marginTop: 26 }}>Payment.</h2>
          <div className="paycards">
            <label className={`paycard ${pay === "mpesa" ? "active" : ""}`}>
              <input type="radio" name="pay" checked={pay === "mpesa"} onChange={() => setPay("mpesa")} />
              <span><b>M-Pesa <em className="reco">Recommended</em></b>
                <span>You&apos;ll receive an STK prompt on your phone to complete payment.</span></span>
            </label>
            <label className={`paycard ${pay === "card" ? "active" : ""}`}>
              <input type="radio" name="pay" checked={pay === "card"} onChange={() => setPay("card")} />
              <span><b>Card</b><span>Visa or Mastercard — secure checkout.</span></span>
            </label>
            <label className={`paycard ${pay === "cod" ? "active" : ""}`}>
              <input type="radio" name="pay" checked={pay === "cod"} onChange={() => setPay("cod")} />
              <span><b>Cash on Delivery</b><span>Pay when your order arrives. Nairobi &amp; environs only.</span></span>
            </label>
          </div>
          <button className="pill pill-gold co-place" type="submit">Place order · {formatKES(total)}</button>
          <p className="muted-cap" style={{ marginTop: 12 }}>Orders before 2 PM deliver same-day within Nairobi. Parish accounts: call {SITE.phone}.</p>
        </div>

        <aside className="co-summary">
          <h3>Order summary</h3>
          {items.map((i) => {
            const p = bySlug(i.slug);
            if (!p) return null;
            return (
              <div className="co-item" key={i.slug}>
                <span className="im"><img src={p.img} alt="" /><i>{i.qty}</i></span>
                <span className="minw0"><b>{p.short}</b></span>
                <span>{formatKES(p.price * i.qty)}</span>
              </div>
            );
          })}
          <div className="co-line"><span>Subtotal</span><span>{formatKES(subtotal)}</span></div>
          <div className="co-line"><span>Delivery</span><span>{delivery === 0 ? "Free" : formatKES(delivery)}</span></div>
          <div className="co-line co-total"><span>Total</span><span>{formatKES(total)}</span></div>
          <p className="muted-cap" style={{ marginTop: 12 }}>
            {delivery === 0 ? "✓ Free Nairobi delivery unlocked." : `Free Nairobi delivery on orders over ${formatKES(FREE_DELIVERY_AT)}.`}
          </p>
        </aside>
      </form>
    </main>
  );
}
