"use client";

import Link from "next/link";
import { useCallback, useEffect, useState } from "react";
import Crumbs from "@/components/Crumbs";
import FindMyOrders from "@/components/FindMyOrders";
import { getOrders, statusFor, type OrderRecord } from "@/lib/orders";
import { formatMoney } from "@/lib/products";

/** Order history — orders from this device plus any synced via Find my orders. */
export default function OrdersPage() {
  const [orders, setOrders] = useState<OrderRecord[] | null>(null);

  useEffect(() => { setOrders(getOrders()); }, []);
  const onSynced = useCallback((recs: OrderRecord[]) => setOrders(recs), []);

  return (
    <main className="wrap">
      <Crumbs items={[{ label: "Home", href: "/" }, { label: "My Orders" }]} />
      <h1 className="serif cart-title">My orders.</h1>

      <FindMyOrders onSynced={onSynced} />

      {orders && orders.length === 0 && (
        <div className="confirm">
          <div className="tick">🕊️</div>
          <h1 className="serif">No orders yet.</h1>
          <p>Your receipts will appear here the moment you place an order.</p>
          <div className="confirm-ctas"><Link className="pill pill-gold" href="/shop">Start shopping</Link></div>
        </div>
      )}

      <div className="orders-list">
        {orders?.map((o) => (
          <Link className="order-row" key={o.ref} href={`/order/${encodeURIComponent(o.ref)}`}>
            <div className="minw0">
              <b>{o.ref}</b>
              <span className="muted-cap">
                {new Date(o.placedAt).toLocaleDateString("en-KE", { day: "numeric", month: "short", year: "numeric" })}
                {" · "}{o.items.reduce((s, i) => s + i.qty, 0)} item(s)
                {o.items.some((i) => i.measurements) && " · ✂ made to order"}
              </span>
            </div>
            <span className="order-status">{statusFor(o)}</span>
            <b className="order-total">{formatMoney(o.total, o.currency)}</b>
            <span className="order-go">View receipt ›</span>
          </Link>
        ))}
      </div>
    </main>
  );
}
