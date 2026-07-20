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

/** Calm browse card: photo → title → one subtitle → rating·stock → price.
    Feature detail, seller rank and add-to-cart live on the PDP, not the tile. */
export function ProductCard({ p }: { p: Product }) {
  const href = `/product/${p.slug}`;
  const sub = p.chips.map((c) => c.text).join(" · ");
  const off =
    p.oldPrice && p.oldPrice > p.price
      ? Math.round((1 - p.price / p.oldPrice) * 100)
      : 0;
  const mto = !!p.producible && !p.sizes;
  const avail = !p.producible
    ? "In stock"
    : mto
      ? "Made to order"
      : "Ready-made & tailored";
  return (
    <article className="pcard">
      <Link className="ph" href={href} aria-label={p.name}>
        <BadgeTag p={p} />
        <Img src={p.img} alt={p.name} />
      </Link>
      <QuickActions slug={p.slug} />
      <h3><Link href={href}>{p.name}</Link></h3>
      {sub && <p className="sub">{sub}</p>}
      <div className="meta">
        {p.reviews > 0 && (
          <span className="stars">
            <span className="star">★</span> {p.rating.toFixed(1)}
            <span className="cnt"> ({p.reviews.toLocaleString("en-KE")})</span>
          </span>
        )}
        <span className={`stk ${mto ? "mto" : ""}`}>{avail}</span>
      </div>
      <div className="price">
        <b><Price p={p} /></b>
        <OldPrice p={p} />
        {off > 0 && <span className="off">−{off}%</span>}
      </div>
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

/** Apple dark editorial card. */
export function EditorialCard({
  eyebrow, title, img,
}: {
  eyebrow: string; title: string[]; img: string;
}) {
  return (
    <article className="edit-card">
      <div className="txt">
        <div className="eyebrow">{eyebrow}</div>
        <h3>{title.map((t, i) => <span key={i}>{t}<br /></span>)}</h3>
      </div>
      <div className="ph"><img src={img} alt="" /></div>
      <button className="plus" aria-label="Learn more">+</button>
    </article>
  );
}
