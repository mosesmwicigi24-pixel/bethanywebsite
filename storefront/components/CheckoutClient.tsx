"use client";

import Link from "next/link";
import { useEffect, useMemo, useState } from "react";
import Crumbs from "./Crumbs";
import { Money } from "./Money";
import { useCart, FREE_DELIVERY_AT, DELIVERY_FEE } from "@/lib/cart";
import { countryForPhone } from "@/lib/currency";
import { useCurrency } from "@/lib/currency";
import { bySlug, formatMoney, type Currency } from "@/lib/products";
import { buildOnlineOrder, measurementsToNote, submitOnlineOrder } from "@/lib/hub";
import { saveOrder } from "@/lib/orders";
import { useRouter } from "next/navigation";
import { SITE } from "@/lib/site";

type Pay = "mpesa" | "card" | "cash_on_delivery";
type Delivery = "delivery" | "pickup";

const INTL_COUNTRIES = [
  ["US", "United States"], ["GB", "United Kingdom"], ["UG", "Uganda"], ["TZ", "Tanzania"],
  ["RW", "Rwanda"], ["ET", "Ethiopia"], ["NG", "Nigeria"], ["ZA", "South Africa"],
  ["DE", "Germany"], ["CA", "Canada"], ["AU", "Australia"], ["OTHER", "Other"],
] as const;

export default function CheckoutClient() {
  const { items, subtotal, subtotalUsd, clear, hydrated } = useCart();
  const { setCurrency } = useCurrency();
  const router = useRouter();

  const [phone, setPhone] = useState("");
  const [firstName, setFirstName] = useState("");
  const [lastName, setLastName] = useState("");
  const [church, setChurch] = useState("");
  const [county, setCounty] = useState("Nairobi");
  const [intlCountry, setIntlCountry] = useState("US");
  const [address, setAddress] = useState("");
  const [delivery, setDelivery] = useState<Delivery>("delivery");
  const [pay, setPay] = useState<Pay>("mpesa");

  // ── The hub's currency rule, read from the phone number ──────────────
  // +254 / 07xx / 01xx → Kenya → KES; other international prefix → USD.
  const phoneCountry = countryForPhone(phone);          // 'KE' | 'INTL' | null
  const isKE = phoneCountry !== "INTL";                 // default to Kenya until told otherwise
  const currency: Currency = isKE ? "KES" : "USD";
  const countryCode = isKE ? "KE" : intlCountry === "OTHER" ? "XX" : intlCountry;

  useEffect(() => { setCurrency(currency); }, [currency, setCurrency]);

  // International: no M-Pesa/COD; pickup unlikely but allowed
  useEffect(() => {
    if (!isKE && pay !== "card") setPay("card");
    if (isKE && pay === "card") setPay("mpesa");
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [isKE]);

  const sub = currency === "KES" ? subtotal : subtotalUsd;
  const deliveryFee = useMemo(() => {
    if (delivery === "pickup") return 0;
    if (!isKE) return 0; // international shipping quoted after order
    return subtotal >= FREE_DELIVERY_AT ? 0 : DELIVERY_FEE;
  }, [delivery, isKE, subtotal]);
  const total = sub + deliveryFee;
  const hasMto = items.some((i) => i.measurements);

  const placeOrder = async (e: React.FormEvent) => {
    e.preventDefault();
    const draft = buildOnlineOrder(items, {
      currency, countryCode, firstName, lastName, phone, church,
      deliveryMethod: delivery, paymentMethod: pay,
      address: delivery === "delivery" ? address : undefined,
      city: delivery === "delivery" ? (isKE ? county : undefined) : undefined,
    });
    const live = await submitOnlineOrder(draft);
    const ref = live?.orderNumber ?? `ORD-${Math.random().toString(36).slice(2, 10).toUpperCase()}`;
    saveOrder({
      ref,
      placedAt: new Date().toISOString(),
      currency,
      items: items.map((i) => {
        const pr = bySlug(i.slug);
        return {
          slug: i.slug,
          name: pr?.name ?? i.slug,
          qty: i.qty,
          unit: pr ? (currency === "KES" ? pr.price : pr.priceUsd) : 0,
          measurements: i.measurements,
        };
      }),
      subtotal: sub,
      delivery: deliveryFee,
      total,
      paymentMethod: pay,
      deliveryMethod: delivery,
      customer: { name: `${firstName} ${lastName}`.trim(), phone, church: church || undefined },
      address: delivery === "delivery" ? address : undefined,
      city: delivery === "delivery" && isKE ? county : undefined,
      countryCode,
      paymentLink: live?.paymentLink,
    });
    clear();
    router.push(`/order/${encodeURIComponent(ref)}`);
  };

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
          <h2 className="serif">Your details.</h2>
          <div className="f-row2">
            <div className="field"><label htmlFor="co-fn">First name</label>
              <input id="co-fn" required value={firstName} onChange={(e) => setFirstName(e.target.value)} placeholder="Jane" /></div>
            <div className="field"><label htmlFor="co-ln">Last name</label>
              <input id="co-ln" required value={lastName} onChange={(e) => setLastName(e.target.value)} placeholder="Mwangi" /></div>
          </div>
          <div className="f-row2">
            <div className="field">
              <label htmlFor="co-phone">Phone</label>
              <input id="co-phone" required type="tel" value={phone} onChange={(e) => setPhone(e.target.value)}
                placeholder="07XX XXX XXX or +1 555…" />
              <span className="muted-cap">
                {phoneCountry === "INTL"
                  ? "International number — prices shown in USD."
                  : "Kenyan number (+254) — prices shown in KES."}
              </span>
            </div>
            <div className="field"><label htmlFor="co-church">Church / parish <span className="muted-cap">(optional)</span></label>
              <input id="co-church" value={church} onChange={(e) => setChurch(e.target.value)} placeholder="St. Andrew's Cathedral" /></div>
          </div>

          <h2 className="serif" style={{ marginTop: 20 }}>Delivery.</h2>
          <div className="paycards" style={{ marginBottom: 18 }}>
            <label className={`paycard ${delivery === "delivery" ? "active" : ""}`}>
              <input type="radio" name="dm" checked={delivery === "delivery"} onChange={() => setDelivery("delivery")} />
              <span><b>Deliver to me</b>
                <span>{isKE ? "Same-day in Nairobi for orders before 2 PM." : "International shipping — we'll confirm the freight quote before dispatch."}</span></span>
            </label>
            <label className={`paycard ${delivery === "pickup" ? "active" : ""}`}>
              <input type="radio" name="dm" checked={delivery === "pickup"} onChange={() => setDelivery("pickup")} />
              <span><b>Pick up in store</b>
                <span>{SITE.address}, {SITE.city} · {SITE.hours}</span></span>
            </label>
          </div>

          {delivery === "delivery" && (
            <div className="f-row2">
              {isKE ? (
                <div className="field"><label htmlFor="co-county">County</label>
                  <select id="co-county" value={county} onChange={(e) => setCounty(e.target.value)}>
                    {["Nairobi", "Kiambu", "Machakos", "Nakuru", "Mombasa", "Kisumu", "Uasin Gishu", "Other"].map((c) => <option key={c}>{c}</option>)}
                  </select>
                </div>
              ) : (
                <div className="field"><label htmlFor="co-country">Country</label>
                  <select id="co-country" value={intlCountry} onChange={(e) => setIntlCountry(e.target.value)}>
                    {INTL_COUNTRIES.map(([code, name]) => <option key={code} value={code}>{name}</option>)}
                  </select>
                </div>
              )}
              <div className="field"><label htmlFor="co-addr">Delivery address</label>
                <input id="co-addr" required value={address} onChange={(e) => setAddress(e.target.value)}
                  placeholder={isKE ? "Street, building, landmark" : "Street, city, postal code"} /></div>
            </div>
          )}

          <h2 className="serif" style={{ marginTop: 20 }}>Payment.</h2>
          <div className="paycards">
            {isKE && (
              <label className={`paycard ${pay === "mpesa" ? "active" : ""}`}>
                <input type="radio" name="pay" checked={pay === "mpesa"} onChange={() => setPay("mpesa")} />
                <span><b>M-Pesa <em className="reco">Recommended</em></b>
                  <span>STK prompt to {phone || "your phone"} to complete payment.</span></span>
              </label>
            )}
            <label className={`paycard ${pay === "card" ? "active" : ""}`}>
              <input type="radio" name="pay" checked={pay === "card"} onChange={() => setPay("card")} />
              <span><b>Card {!isKE && <em className="reco">USD</em>}</b>
                <span>Visa or Mastercard — secure checkout{!isKE && " billed in US dollars"}.</span></span>
            </label>
            {isKE && (
              <label className={`paycard ${pay === "cash_on_delivery" ? "active" : ""}`}>
                <input type="radio" name="pay" checked={pay === "cash_on_delivery"} onChange={() => setPay("cash_on_delivery")} />
                <span><b>Cash on Delivery</b><span>Pay when your order arrives. Nairobi &amp; environs only.</span></span>
              </label>
            )}
          </div>
          <button className="pill pill-gold co-place" type="submit">
            Place order · {formatMoney(total, currency)}
          </button>
          <p className="muted-cap" style={{ marginTop: 12 }}>
            {hasMto && "Made-to-order items are sewn after payment confirmation. "}
            Parish accounts &amp; quantity quotes: call {SITE.phone}.
          </p>
        </div>

        <aside className="co-summary">
          <h3>Order summary</h3>
          {items.map((i) => {
            const p = bySlug(i.slug);
            if (!p) return null;
            return (
              <div key={i.key}>
                <div className="co-item">
                  <span className="im"><img src={p.img} alt="" /><i>{i.qty}</i></span>
                  <span className="minw0"><b>{p.short}</b>
                    {i.measurements && <span className="muted-cap">✂ Made to order</span>}
                  </span>
                  <span><Money kes={p.price * i.qty} usd={p.priceUsd * i.qty} /></span>
                </div>
                {i.measurements && (
                  <div className="co-meas">{measurementsToNote(i.measurements)}</div>
                )}
              </div>
            );
          })}
          <div className="co-line"><span>Subtotal</span><span>{formatMoney(sub, currency)}</span></div>
          <div className="co-line"><span>Delivery</span>
            <span>{delivery === "pickup" ? "Free (pickup)" : !isKE ? "Quoted after order" : deliveryFee === 0 ? "Free" : formatMoney(deliveryFee, "KES")}</span></div>
          <div className="co-line co-total"><span>Total</span><span>{formatMoney(total, currency)}</span></div>
          <p className="muted-cap" style={{ marginTop: 12 }}>
            {isKE
              ? deliveryFee === 0 && delivery === "delivery"
                ? "✓ Free Nairobi delivery unlocked."
                : `Free Nairobi delivery on orders over ${formatMoney(FREE_DELIVERY_AT, "KES")}.`
              : "International order — billed in USD, shipping confirmed before dispatch."}
          </p>
        </aside>
      </form>
    </main>
  );
}
