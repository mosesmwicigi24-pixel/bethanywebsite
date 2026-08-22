"use client";

import Link from "next/link";
import { useEffect, useState } from "react";
import { SITE } from "@/lib/site";

const SHOP_LINKS = [
  ["Offers 🔥", "/shop"],
  ["Communion Elements", "/category/communion-elements"],
  ["Clergy Apparel", "/category/clergy-vestments"],
  ["Gifts & Accessories", "/category/church-gifts"],
  ["Church Essentials", "/category/church-essentials"],
  ["Bibles & Devotionals", "/category/bibles-devotionals"],
  ["Hot & New", "/shop"],
] as const;

/** Slide-in navigation drawer for small screens. */
export default function MobileMenu() {
  const [open, setOpen] = useState(false);
  const close = () => setOpen(false);

  useEffect(() => {
    document.body.style.overflow = open ? "hidden" : "";
    const onKey = (e: KeyboardEvent) => e.key === "Escape" && close();
    window.addEventListener("keydown", onKey);
    return () => {
      document.body.style.overflow = "";
      window.removeEventListener("keydown", onKey);
    };
  }, [open]);

  return (
    <>
      <button className="nav-burger" aria-label="Open menu" aria-expanded={open} onClick={() => setOpen(true)}>
        <svg viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16" /></svg>
      </button>

      <div className={`mnav-veil ${open ? "open" : ""}`} onClick={close}>
        <aside className="mnav" role="dialog" aria-modal="true" aria-label="Menu" onClick={(e) => e.stopPropagation()}>
          <div className="mnav-head">
            <img src="/brand/logo-light.png" alt="Bethany House" />
            <button aria-label="Close menu" onClick={close}>✕</button>
          </div>
          <nav className="mnav-links">
            {SHOP_LINKS.map(([label, href]) => (
              <Link key={label} href={href} onClick={close}>{label}<span>›</span></Link>
            ))}
          </nav>
          <div className="mnav-sub">
            <Link href="/orders" onClick={close}>Track Your Order</Link>
            <Link href="/faq" onClick={close}>FAQs &amp; Help</Link>
            <Link href="/contact" onClick={close}>Visit Our Store — {SITE.address}</Link>
            <a href={SITE.phoneHref}>WhatsApp / Call {SITE.phone}</a>
          </div>
          <div className="mnav-foot">
            <span className="badge-pay">M-PESA</span>
            <span className="badge-pay">VISA</span>
            <span className="badge-pay">COD</span>
            <p>{SITE.deliveryShort}<br />{SITE.hours}</p>
          </div>
        </aside>
      </div>
    </>
  );
}
