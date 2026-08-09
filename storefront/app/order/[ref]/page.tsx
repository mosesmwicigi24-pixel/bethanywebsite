"use client";

import Link from "next/link";
import { useEffect, useState } from "react";
import { useParams } from "next/navigation";
import { getOrder, payLabel, statusFor, type OrderRecord } from "@/lib/orders";
import { fetchOrderStatus, hubLive, type HubOrderStatus } from "@/lib/hub";
import { formatMoney } from "@/lib/products";
import { SITE } from "@/lib/site";

/** The customer's receipt — branded, printable, always retrievable. */
export default function OrderReceiptPage() {
  const { ref } = useParams<{ ref: string }>();
  const [order, setOrder] = useState<OrderRecord | null | undefined>(undefined);
  const [live, setLive] = useState<HubOrderStatus | null>(null);

  useEffect(() => { setOrder(getOrder(decodeURIComponent(ref))); }, [ref]);

  // Live tracker: refresh payment/shipment state from the hub while the
  // page is open (paid orders confirm within seconds of the M-Pesa prompt).
  useEffect(() => {
    const token = order?.paymentToken;
    if (!token || !hubLive()) return;
    let stop = false;
    const tick = async () => {
      const s = await fetchOrderStatus(token);
      if (!stop && s) setLive(s);
    };
    tick();
    const t = setInterval(tick, 20000);
    return () => { stop = true; clearInterval(t); };
  }, [order?.paymentToken]);

  if (order === undefined) return <main className="wrap" style={{ minHeight: 400 }} />;

  if (order === null) {
    return (
      <main className="wrap">
        <div className="confirm">
          <div className="tick">🕊️</div>
          <h1 className="serif">Receipt not found on this device.</h1>
          <p>Receipts are stored on the device the order was placed from. Need a copy? Call <b>{SITE.phone}</b> with your order number.</p>
          <div className="confirm-ctas"><Link className="pill pill-gold" href="/orders">My orders</Link></div>
        </div>
      </main>
    );
  }

  const d = new Date(order.placedAt);
  const fmt = (n: number) => formatMoney(n, order.currency);

  return (
    <main className="wrap receipt-wrap">
      <div className="receipt-head no-print">
        <div>
          <h1 className="serif">Asante — order received.</h1>
          <p className="muted-cap">
            {order.paymentMethod === "mpesa" && <>We&apos;ll send an M-Pesa prompt to {order.customer.phone} to complete payment. </>}
            {order.paymentMethod === "card" && <>A secure card payment link is on its way. </>}
            {order.paymentMethod === "cash_on_delivery" && <>Pay in cash when your order arrives. </>}
            Keep this receipt — it&apos;s saved under <Link href="/orders" style={{ textDecoration: "underline" }}>My orders</Link>.
          </p>
        </div>
        <div className="receipt-ctas">
          {(live ? live.payment_status !== "paid" && live.payment_link : order.paymentLink) && (
            <a className="pill pill-gold" href={(live?.payment_link ?? order.paymentLink)!}>Complete payment</a>
          )}
          <button className="pill pill-solid" onClick={() => window.print()}>Print / Save PDF</button>
        </div>
      </div>

      {live?.shipment && (
        <div className="ship-banner no-print">
          <div>
            <b>🚚 Your order is {live.shipment.status.replace("_", " ")}</b>
            <span>
              {live.shipment.carrier && <>{live.shipment.carrier}{live.shipment.tracking_number && <> · {live.shipment.tracking_number}</>}. </>}
              {live.shipment.estimated_delivery_date && <>Estimated delivery {new Date(live.shipment.estimated_delivery_date).toLocaleDateString("en-KE", { day: "numeric", month: "long" })}.</>}
            </span>
          </div>
          {live.shipment.tracking_url && (
            <a className="pill pill-gold" href={live.shipment.tracking_url}>Track shipment</a>
          )}
        </div>
      )}

      <section className="receipt" aria-label="Order receipt">
        <header className="r-top">
          <img src="/brand/logo-dark.png" alt={SITE.name} />
          <div className="r-biz">
            <b>{SITE.name}</b>
            {SITE.address}, {SITE.city}<br />
            {SITE.phone} · {SITE.email}
          </div>
        </header>

        <div className="r-meta">
          <div><span>Order receipt</span><b>{order.ref}</b></div>
          <div><span>Date</span><b>{d.toLocaleDateString("en-KE", { day: "numeric", month: "long", year: "numeric" })} · {d.toLocaleTimeString("en-KE", { hour: "2-digit", minute: "2-digit" })}</b></div>
          <div><span>Status</span>
            <b>{live
              ? live.payment_status === "paid" ? "✓ Paid — being prepared" : `${live.status} · ${live.payment_status.replace("_", " ")}`
              : statusFor(order)}</b>
            {live?.invoice_number && <em>Invoice {live.invoice_number}</em>}
          </div>
        </div>

        <div className="r-meta">
          <div><span>Customer</span><b>{order.customer.name}</b>{order.customer.church && <em>{order.customer.church}</em>}<em>{order.customer.phone}</em></div>
          <div><span>{order.deliveryMethod === "pickup" ? "Collection" : "Delivery"}</span>
            <b>{order.deliveryMethod === "pickup" ? `Pick up — ${SITE.address}` : order.address}</b>
            {order.deliveryMethod === "delivery" && order.city && <em>{order.city}, {order.countryCode}</em>}
          </div>
          <div><span>Payment</span><b>{payLabel[order.paymentMethod]}</b><em>Billed in {order.currency}</em></div>
        </div>

        <table className="r-table">
          <thead>
            <tr><th>Item</th><th>Qty</th><th>Unit</th><th>Total</th></tr>
          </thead>
          <tbody>
            {order.items.map((it, idx) => (
              <tr key={idx}>
                <td>
                  {it.name}
                  {it.measurements && (
                    <div className="r-meas">✂ Made to order — {Object.entries(it.measurements).filter(([, v]) => v).map(([k, v]) => `${k}: ${v}`).join(", ")}</div>
                  )}
                  {it.size && <div className="r-meas ready">Ready-made — Size {it.size}</div>}
                </td>
                <td>{it.qty}</td>
                <td>{fmt(it.unit)}</td>
                <td>{fmt(it.unit * it.qty)}</td>
              </tr>
            ))}
          </tbody>
        </table>

        <div className="r-totals">
          <div><span>Subtotal</span><span>{fmt(order.subtotal)}</span></div>
          <div><span>Delivery</span><span>{order.delivery === 0 ? (order.countryCode === "KE" ? "Free" : "Quoted before dispatch") : fmt(order.delivery)}</span></div>
          <div className="grand"><span>Total</span><span>{fmt(order.total)}</span></div>
        </div>

        <footer className="r-foot">
          Asante for shopping with {SITE.name}. Prices include VAT where applicable.
          Made-to-order items are sewn after payment confirmation (5–7 days).
          Questions about this order: {SITE.phone}, quoting <b>{order.ref}</b>.
        </footer>
      </section>

      <div className="confirm-ctas no-print" style={{ margin: "30px 0 60px" }}>
        <Link className="pill pill-gold" href="/shop">Continue shopping</Link>
        <Link className="pill pill-ghost" href="/orders">My orders</Link>
      </div>
    </main>
  );
}
