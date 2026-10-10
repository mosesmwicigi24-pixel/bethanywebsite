"use client";

import Link from "next/link";
import { usePathname, useSearchParams } from "next/navigation";
import { useEffect, useState } from "react";
import { categoryHref } from "@/lib/categories";
import { openNeema, openSearch } from "@/lib/ui-events";
import { SITE } from "@/lib/site";

/* Full-screen or checkout-critical routes keep their own bottom bars. */
const HIDDEN = [/^\/product\//, /^\/checkout/, /^\/clips/];

const DEPARTMENTS = [
  { label: "Communion", sub: "Chalices · wine · hosts", img: "/products/Chalice_Cup.jpg", href: categoryHref("Communion Elements") },
  { label: "Clergy Apparel", sub: "Gowns · cassocks · stoles", img: "/products/preaching_gown1.jpg", href: categoryHref("Clergy Apparel") },
  { label: "Bibles", sub: "Study · children's · gift", img: "/products/niv_bible.jpg", href: categoryHref("Bibles & Devotionals") },
  { label: "Gifts", sub: "Crosses · keepsakes", img: "/products/cross1.jpg", href: categoryHref("Gifts & Accessories") },
  { label: "Essentials", sub: "Bells · linens · ware", img: "/products/bell.jpg", href: categoryHref("Church Essentials") },
  { label: "Prayer Wear", sub: "Tallits · shawls", img: "/products/tallit.jpg", href: categoryHref("Clergy Apparel") },
];

/**
 * App-style bottom tab bar for phones (hidden from tablet up): Home, Hot Now,
 * a raised Browse button, New Arrivals and Help. Browse opens a sheet of the
 * departments; Help opens Neema, which is why the floating chat pill steps
 * aside on phones while this bar is up.
 */
export default function TabBar() {
  const path = usePathname() ?? "/";
  const sort = useSearchParams().get("sort");
  const [sheet, setSheet] = useState(false);

  // Lock the page behind the open sheet (its links close it on tap).
  useEffect(() => {
    if (!sheet) return;
    const prev = document.body.style.overflow;
    document.body.style.overflow = "hidden";
    const onKey = (e: KeyboardEvent) => { if (e.key === "Escape") setSheet(false); };
    window.addEventListener("keydown", onKey);
    return () => { document.body.style.overflow = prev; window.removeEventListener("keydown", onKey); };
  }, [sheet]);

  if (HIDDEN.some((r) => r.test(path))) return null;

  const at = {
    home: path === "/",
    hot: path === "/shop" && sort === "hot",
    fresh: path === "/shop" && sort === "new",
  };

  return (
    <>
      <nav className="tabbar" aria-label="Quick navigation">
        <Link href="/" className={at.home ? "on" : ""} aria-current={at.home ? "page" : undefined}>
          <svg viewBox="0 0 24 24"><path d="M4 10.5 12 4l8 6.5V19a1.5 1.5 0 0 1-1.5 1.5H15v-6H9v6H5.5A1.5 1.5 0 0 1 4 19z" /></svg>
          <span>Home</span>
        </Link>
        <Link href="/shop?sort=hot" className={at.hot ? "on" : ""} aria-current={at.hot ? "page" : undefined}>
          <svg viewBox="0 0 24 24"><path d="M12 21c-3.9 0-7-2.8-7-6.6 0-2.9 1.7-4.9 3.4-6.6.3 1.8 1.2 3 2.4 3.4C10.4 7.6 12 5 14.6 3c-.3 2.6.7 4.4 2.1 6 1.3 1.4 2.3 3 2.3 5.3C19 18.2 15.9 21 12 21z" /><path d="M12 21c-1.7 0-3-1.3-3-3 0-1.9 1.6-2.9 2.4-4.6.9 1 3.6 2.4 3.6 4.6 0 1.7-1.3 3-3 3z" /></svg>
          <span>Hot Now</span>
        </Link>
        <button type="button" className={`tab-browse${sheet ? " open" : ""}`} aria-expanded={sheet}
          aria-controls="browse-sheet" onClick={() => setSheet((s) => !s)}>
          <span className="tb-disc" aria-hidden="true">
            <svg className="x" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6 6 18" /></svg>
            <svg className="grid" viewBox="0 0 24 24"><rect x="4" y="4" width="4" height="4" rx="1" /><rect x="10" y="4" width="4" height="4" rx="1" /><rect x="16" y="4" width="4" height="4" rx="1" /><rect x="4" y="10" width="4" height="4" rx="1" /><rect x="10" y="10" width="4" height="4" rx="1" /><rect x="16" y="10" width="4" height="4" rx="1" /><rect x="4" y="16" width="4" height="4" rx="1" /><rect x="10" y="16" width="4" height="4" rx="1" /><rect x="16" y="16" width="4" height="4" rx="1" /></svg>
          </span>
          <span>Browse</span>
        </button>
        <Link href="/shop?sort=new" className={at.fresh ? "on" : ""} aria-current={at.fresh ? "page" : undefined}>
          <svg viewBox="0 0 24 24"><path d="M10 3.5 11.6 8a3 3 0 0 0 1.9 1.9L18 11.5l-4.5 1.6a3 3 0 0 0-1.9 1.9L10 19.5 8.4 15a3 3 0 0 0-1.9-1.9L2 11.5 6.5 10A3 3 0 0 0 8.4 8z" /><path d="M18.5 2.5v4M16.5 4.5h4M19 16v3M17.5 17.5h3" /></svg>
          <span>New Arrivals</span>
        </Link>
        <button type="button" onClick={openNeema}>
          <svg viewBox="0 0 24 24"><path d="M20 12a8 8 0 0 1-11.6 7.1L4 20l1-4.1A8 8 0 1 1 20 12z" /><path d="M8.5 11h.01M12 11h.01M15.5 11h.01" /></svg>
          <span>Help</span>
        </button>
      </nav>

      <div className={`bsheet-veil${sheet ? " open" : ""}`} onClick={() => setSheet(false)} aria-hidden={!sheet}>
        <div id="browse-sheet" className="bsheet" role="dialog" aria-modal="true" aria-label="Browse the store"
          onClick={(e) => e.stopPropagation()}>
          <span className="bsheet-grip" aria-hidden="true" />
          <button type="button" className="bsheet-search" onClick={() => { setSheet(false); openSearch(); }}>
            <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7" /><path d="m20 20-3.5-3.5" /></svg>
            Search chalices, gowns, stoles…
          </button>
          <div className="bsheet-h">Shop by department</div>
          <div className="bsheet-grid">
            {DEPARTMENTS.map((d) => (
              <Link key={d.label} href={d.href} onClick={() => setSheet(false)}>
                <span className="im"><img src={d.img} alt="" loading="lazy" decoding="async" /></span>
                <b>{d.label}</b>
                <small>{d.sub}</small>
              </Link>
            ))}
          </div>
          <div className="bsheet-links">
            <Link href="/clips" onClick={() => setSheet(false)}><i>▶</i>Watch clips</Link>
            <Link href="/shop" onClick={() => setSheet(false)}><i>✦</i>All products</Link>
            <Link href="/orders" onClick={() => setSheet(false)}><i>⟳</i>Track order</Link>
            <a href={SITE.whatsapp} target="_blank" rel="noopener"><i>☏</i>WhatsApp us</a>
          </div>
        </div>
      </div>
    </>
  );
}
