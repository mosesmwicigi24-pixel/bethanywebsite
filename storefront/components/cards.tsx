import Link from "next/link";
import { Product, badgeLabel, formatKES } from "@/lib/products";
import CartButton from "./CartButton";

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

/** Full oraimo-style catalog card. */
export function ProductCard({ p }: { p: Product }) {
  const href = `/product/${p.slug}`;
  return (
    <article className="pcard">
      <Link className="ph" href={href}>
        <BadgeTag p={p} />
        <img src={p.img} alt={p.name} />
        <span className="rating"><Stars p={p} /></span>
      </Link>
      <h3>{p.name}</h3>
      <div className="chips">
        {p.chips.map((c) => (
          <div className="chip" key={c.text}><span className="ic">{c.icon}</span>{c.text}</div>
        ))}
      </div>
      <div className="price">
        <b>{formatKES(p.price)}</b>
        {p.oldPrice && <s>{formatKES(p.oldPrice)}</s>}
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
        <img src={p.img} alt={p.name} />
      </Link>
      <div className="rating"><Stars p={p} /></div>
      <h4>{p.name}</h4>
      <div className="row">
        <div className="price">
          <b>{formatKES(p.price)}</b>
          {p.oldPrice && <s>{formatKES(p.oldPrice)}</s>}
        </div>
        <CartButton />
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
