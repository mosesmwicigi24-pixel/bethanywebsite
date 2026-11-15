import Link from "next/link";
import { Product, badgeLabel } from "@/lib/products";
import { Price, OldPrice } from "./Money";
import CartButton from "./CartButton";
import HoverVideo from "./HoverVideo";
import QuickActions from "./QuickActions";
import CardCta, { type CtaMode } from "./CardCta";

const Stars = ({ p }: { p: Product }) => (
  <>
    {p.rating.toFixed(1)} <span className="star">★</span>{" "}
    <span className="cnt">({p.reviews.toLocaleString("en-KE")})</span>
  </>
);

const BadgeTag = ({ p }: { p: Product }) =>
  p.badge ? (
    <span className={`tag ${badgeLabel[p.badge].cls}`}>{badgeLabel[p.badge].text}</span>
  ) : null;

/** Whole-percent saving of the sale price against the regular one. */
const savingPct = (p: Product) =>
  p.oldPrice && p.oldPrice > p.price && p.price > 0 ? Math.round((1 - p.price / p.oldPrice) * 100) : 0;

/**
 * Catalog card, built to sell: the photo fills the tile; under it the name,
 * the price, and one full-width button — each at the same height on every
 * card, so a row lines up as names / prices / buttons.
 *
 * The button always does the next honest thing (see CardCta): add to cart,
 * or go choose a size / option, or ask the price. Proof and urgency ride on
 * the photo as small labels — best seller, a real saving, sold out — never
 * invented. Everything else lives on the product page.
 */
export function ProductCard({ p }: { p: Product }) {
  const href = `/product/${p.slug}`;
  const soldOut = !p.producible && p.inStock === false;
  const hasOptions = Boolean(p.variants?.length);
  const priced = p.price > 0;
  const off = savingPct(p);
  const mode: CtaMode = soldOut ? "sold" : !priced ? "ask" : p.producible ? "size" : hasOptions ? "options" : "add";
  return (
    <article className={`pcard${soldOut ? " is-sold" : ""}`}>
      <Link className="ph" href={href}>
        <HoverVideo src={p.img} video={p.video} alt={p.name} />
        <span className="labels">
          {soldOut ? <span className="tag tag-sold">Sold out</span> : <BadgeTag p={p} />}
          {off >= 5 && !soldOut && <span className="tag tag-off">Save {off}%</span>}
        </span>
        {p.reviews > 0 && <span className="rating"><Stars p={p} /></span>}
      </Link>
      <QuickActions />
      <h3><Link href={href}>{p.name}</Link></h3>
      {priced ? (
        <div className="price">
          {hasOptions && <span className="from">From</span>}
          <b><Price p={p} /></b>
          <OldPrice p={p} />
        </div>
      ) : (
        // No price set in the hub yet: say so, rather than print "KES 0".
        <div className="price"><span className="from">Price on request</span></div>
      )}
      <CardCta slug={p.slug} name={p.name} href={href} mode={mode} />
    </article>
  );
}

/** Compact carousel card with add-to-cart. */
export function MiniCard({ p }: { p: Product }) {
  return (
    <article className="mini-card">
      <Link className="ph" href={`/product/${p.slug}`}>
        <BadgeTag p={p} />
        <HoverVideo src={p.img} video={p.video} alt={p.name} />
      </Link>
      {p.reviews > 0 && <div className="rating"><Stars p={p} /></div>}
      <h4>{p.name}</h4>
      <span className="mc-cat">{p.category}</span>
      <div className="row">
        <div className="price">
          <b><Price p={p} /></b>
          <OldPrice p={p} />
        </div>
        <CartButton slug={p.slug} />
      </div>
    </article>
  );
}

/** Apple lineup-style card for the home carousel. */
export function LineupCard({
  href, img, dots, title, blurb,
}: {
  href: string; img: string; dots: string[]; title: string; blurb: string;
}) {
  return (
    <article className="lineup-card">
      <Link className="art" href={href}><img src={img} alt={title} loading="lazy" decoding="async" /></Link>
      <div className="dots">{dots.map((d, i) => <i key={i} style={{ background: d }} />)}</div>
      <h3>{title}</h3>
      <p>{blurb}</p>
      <div className="cta">
        <Link className="pill pill-blue" href={href}>Learn more</Link>
        <Link className="pill pill-mini" href={href}>Buy</Link>
      </div>
    </article>
  );
}

/** Apple-style editorial tile: full-bleed photo, title over a gradient scrim.
    `focus` sets the image's object-position so faces/products stay in frame. */
export function EditorialCard({
  eyebrow, title, img, focus,
}: {
  eyebrow: string; title: string[]; img: string; focus?: string;
}) {
  return (
    <article className="edit-card">
      <div className="ph"><img src={img} alt="" loading="lazy" decoding="async" style={focus ? { objectPosition: focus } : undefined} /></div>
      <div className="txt">
        <div className="eyebrow">{eyebrow}</div>
        <h3>{title.map((t, i) => <span key={i}>{t}<br /></span>)}</h3>
      </div>
      <button className="plus" aria-label="Learn more">+</button>
    </article>
  );
}
