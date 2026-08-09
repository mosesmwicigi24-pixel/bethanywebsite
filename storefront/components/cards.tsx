import Link from "next/link";
import { Product, badgeLabel } from "@/lib/products";
import { Price, OldPrice } from "./Money";
import CartButton from "./CartButton";
import Img from "./Img";
import QuickActions from "./QuickActions";

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

/** Condense a feature line for a grid card (the product page shows it in full).
    Most read "headline — explanation", so the headline alone is the scannable
    label; otherwise trim at a word boundary rather than mid-word. */
export function cardFeature(text: string, max = 46): string {
  const head = text.split(/\s[—–]\s/)[0].trim();
  if (head.length <= max) return head;
  const cut = head.slice(0, max);
  const at = cut.lastIndexOf(" ");
  return (at > max * 0.6 ? cut.slice(0, at) : cut).replace(/[,;:]$/, "") + "…";
}

/** Full oraimo-style catalog card. */
export function ProductCard({ p }: { p: Product }) {
  const href = `/product/${p.slug}`;
  return (
    <article className="pcard">
      <Link className="ph" href={href}>
        <BadgeTag p={p} />
        <Img src={p.img} alt={p.name} />
        {p.reviews > 0 && <span className="rating"><Stars p={p} /></span>}
      </Link>
      <QuickActions slug={p.slug} />
      <h3>{p.name}</h3>
      <div className="chips">
        {p.chips.slice(0, 2).map((c) => (
          <div className="chip" key={c.text}><span className="ic">{c.icon}</span>{cardFeature(c.text)}</div>
        ))}
      </div>
      <div className="price">
        <b><Price p={p} /></b>
        <OldPrice p={p} />
      </div>
      <div className={`avail ${p.producible && !p.sizes ? "mto" : ""}`}>
        {p.producible
          ? p.sizes ? "Ready-made & made to measure" : "Made to order · 5–7 days"
          : "In stock · ships today"}
      </div>
      {p.seller && (
        <div className="seller">
          <Link href={href}><span className="tag tag-top"></span> {p.seller} ›</Link>
        </div>
      )}
    </article>
  );
}

/** Compact carousel card with add-to-cart. */
export function MiniCard({ p }: { p: Product }) {
  return (
    <article className="mini-card">
      <Link className="ph" href={`/product/${p.slug}`}>
        <BadgeTag p={p} />
        <Img src={p.img} alt={p.name} />
      </Link>
      {p.reviews > 0 && <div className="rating"><Stars p={p} /></div>}
      <h4>{p.name}</h4>
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
      <Link className="art" href={href}><img src={img} alt={title} /></Link>
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
      <div className="ph"><img src={img} alt="" style={focus ? { objectPosition: focus } : undefined} /></div>
      <div className="txt">
        <div className="eyebrow">{eyebrow}</div>
        <h3>{title.map((t, i) => <span key={i}>{t}<br /></span>)}</h3>
      </div>
      <button className="plus" aria-label="Learn more">+</button>
    </article>
  );
}
