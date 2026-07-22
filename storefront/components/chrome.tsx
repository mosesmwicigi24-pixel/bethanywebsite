import Link from "next/link";
import Search from "./Search";
import MobileMenu from "./MobileMenu";
import NavCart from "./NavCart";
import { CurrencyToggle } from "./Money";
import { SITE } from "@/lib/site";
import { UserIcon, TruckIcon, CardIcon, ShieldIcon } from "./icons";
import Neema from "./Neema";

/* Shared site chrome: utility bar, nav (CSS-hover mega menu), footer, chat fab.
   All server components — interactivity is pure CSS.
   Business facts (phones, address, delivery promise) come from lib/site.ts. */

export function UtilityBar() {
  return (
    <div className="utility">
      <div className="wrap">
        <div className="promo">
          Free delivery in Nairobi CBD for orders above <b>KES 10,000</b> — we ship across East Africa
        </div>
        <div className="meta">
          <a href="#">{SITE.address}</a>
          <a href={SITE.phoneHref}>{SITE.phone}</a>
        </div>
      </div>
    </div>
  );
}

/** Link to the shop filtered by a root department (see lib/categories.ts). */
const shopCat = (c: string) => `/shop?category=${encodeURIComponent(c)}`;

export function Nav() {
  return (
    <nav className="nav">
      <div className="wrap">
        <MobileMenu />
        <Link className="logo" href="/"><img src="/brand/logo-light.png" alt={SITE.name} /></Link>
        <ul className="nav-links">
          <li><Link href="/shop">Offers <span className="flame">🔥</span></Link></li>
          <li className="has-mega">
            <Link href={shopCat("Communion Elements")}>Communion Elements</Link>
            <div className="mega">
              <div className="mega-inner">
                <div className="mega-rail">
                  <Link href="/shop" className="active"><svg viewBox="0 0 24 24"><path d="M8 3h8m-4 0v5m-5 0h10l-1.5 12.5a2 2 0 0 1-2 1.5h-3a2 2 0 0 1-2-1.5L7 8z" /></svg>Chalices &amp; Patens</Link>
                  <Link href="/shop"><svg viewBox="0 0 24 24"><path d="M9 3h6l1 7c0 3-1.7 4.6-4 5-2.3-.4-4-2-4-5l1-7zM12 15v6m-3 0h6" /></svg>Altar Wine</Link>
                  <Link href="/shop"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="8" /><path d="M12 8v8M8 12h8" /></svg>Hosts &amp; Wafers</Link>
                  <Link href="/shop"><svg viewBox="0 0 24 24"><rect x="3" y="7" width="18" height="13" rx="2" /><path d="M8 7V5a4 4 0 0 1 8 0v2" /></svg>Communion Sets</Link>
                  <Link href="/shop"><svg viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h10" /></svg>Altar Linens</Link>
                  <Link href="/shop" className="viewall"><svg viewBox="0 0 24 24"><path d="M7 17 17 7m0 0H9m8 0v8" /></svg>View All Communion</Link>
                </div>
                <div className="mega-main">
                  <Link className="head" href="/shop">View All Chalices &amp; Patens <svg viewBox="0 0 24 24"><path d="M5 12h14m-6-6 6 6-6 6" /></svg></Link>
                  <div className="mega-grid">
                    <Link className="mega-feature" href="/product/chalice-royale">
                      <img src="/products/Chalice_Cup.jpg" alt="" />
                      Chalice Royale
                    </Link>
                    <Link className="mega-cell" href="/product/communion-ware-deluxe"><span className="tag tag-gold">Best Seller</span><img src="/products/gold-wares.jpg" alt="" />Communion Ware Set</Link>
                    <Link className="mega-cell" href="/product/altar-wine"><span className="tag tag-green">New Arrival</span><img src="/products/Altar_wine.png" alt="" />Altar Wine</Link>
                    <Link className="mega-cell" href="/product/communion-hosts"><img src="/products/Hosts.png" alt="" />Hosts &amp; Wafers</Link>
                    <Link className="mega-cell" href="/product/altar-bell"><img src="/products/bell.jpg" alt="" />Altar Bells</Link>
                  </div>
                </div>
                <div className="mega-promo">
                  <h4>Order the way your church prefers</h4>
                  <div className="perks">
                    <div className="perk"><div className="ico">📱</div>M-Pesa Till</div>
                    <div className="perk"><div className="ico">🚚</div>Same-day Nairobi</div>
                    <div className="perk"><div className="ico">🎁</div>Parish Accounts</div>
                    <div className="perk"><div className="ico">✍️</div>Engraving</div>
                  </div>
                  <div className="contact">
                    <span className="badge-pay">M-PESA</span><span className="badge-pay">VISA</span><span className="badge-pay">COD</span>
                    <b style={{ marginTop: 12 }}>{SITE.phone}</b>
                    Order on WhatsApp — quotes for parishes &amp; dioceses
                  </div>
                </div>
              </div>
            </div>
          </li>
          <li><Link href={shopCat("Clergy Apparel")}>Clergy Apparel</Link></li>
          <li><Link href={shopCat("Gifts & Accessories")}>Gifts &amp; Accessories</Link></li>
          <li><Link href={shopCat("Church Essentials")}>Church Essentials</Link></li>
          <li className="has-drop">
            <a href="#">Support</a>
            <div className="drop">
              <a href="#"><TruckIcon />Track Order<span>Delivery status &amp; ETA</span></a>
              <a href="#"><svg viewBox="0 0 24 24"><path d="M12 21s-7-4.6-9.5-9A5.6 5.6 0 0 1 12 6a5.6 5.6 0 0 1 9.5 6c-2.5 4.4-9.5 9-9.5 9z" /></svg>Visit Our Store<span>{SITE.address}</span></a>
              <a href={SITE.phoneHref}><svg viewBox="0 0 24 24"><path d="M21 12a9 9 0 1 1-9-9m9 0-9 9m9-9v5m0-5h-5" /></svg>WhatsApp Us<span>{SITE.phone}</span></a>
            </div>
          </li>
          <li><Link href="/shop">Hot &amp; New</Link></li>
        </ul>
        <div className="nav-icons">
          <CurrencyToggle />
          <Search />
          <NavCart />
          <Link href="/orders" aria-label="My orders"><UserIcon /></Link>
        </div>
      </div>
    </nav>
  );
}

export function TrustRow() {
  return (
    <div className="wrap">
      <div className="trust">
        <div className="item"><TruckIcon />{SITE.deliveryShort}</div>
        <div className="item"><CardIcon />M-Pesa, Card &amp; Cash on Delivery</div>
        <div className="item"><ShieldIcon />Sanctuary-Grade Quality Guarantee</div>
      </div>
    </div>
  );
}

export function Footer() {
  return (
    <footer className="footer">
      <div className="wrap cols">
        <div className="brand">
          <img src="/brand/logo-light.png" alt={SITE.name} />
          <p>{SITE.tagline}</p>
          <div className="newsletter"><input placeholder="Email for offers & new arrivals" /><button>Subscribe</button></div>
        </div>
        <div>
          <h5>Shop</h5>
          <Link href={shopCat("Communion Elements")}>Communion Elements</Link>
          <Link href={shopCat("Clergy Apparel")}>Clergy Apparel</Link>
          <Link href={shopCat("Gifts & Accessories")}>Gifts &amp; Accessories</Link>
          <Link href={shopCat("Church Essentials")}>Church Essentials</Link>
          <Link href="/shop">Daily Offers</Link>
        </div>
        <div>
          <h5>Support</h5>
          <Link href="/orders">Track Your Order</Link>
          <Link href="/policies/shipping">Delivery &amp; Returns</Link>
          <Link href="/policies/returns">Returns &amp; Refunds</Link>
          <a href={SITE.phoneHref}>Parish Accounts</a>
          <a href={SITE.phoneHref}>Engraving Services</a>
          <a href={`mailto:${SITE.email}`}>Contact Us</a>
        </div>
        <div className="contact">
          <h5>Visit Us</h5>
          <p><b>{SITE.address}</b><br />{SITE.city}</p>
          <p>{SITE.hours}</p>
          <p><b>{SITE.phone}</b> · {SITE.phone2}</p>
          <p>{SITE.email}</p>
        </div>
      </div>
      <div className="wrap base">
        <span>© 2026 {SITE.name}. All rights reserved.</span>
        <span className="legal-links">
          <Link href="/policies/terms">Terms of Service</Link>
          <Link href="/policies/privacy">Privacy Policy</Link>
          <Link href="/policies/returns">Returns &amp; Refunds</Link>
          <Link href="/policies/shipping">Shipping &amp; Delivery</Link>
        </span>
        <span>{SITE.payments}</span>
      </div>
    </footer>
  );
}

/** Floating launcher + Neema chat panel (client widget). */
export function ChatFab() {
  return <Neema />;
}
