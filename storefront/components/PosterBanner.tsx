import type { Product } from "@/lib/products";

export interface PosterOverride {
  eyebrow?: string;
  tagline?: string;
  specs?: string;
  img?: string;
}

/** oraimo-style product poster: campaign tagline, spec strip, hero image
    on a warm gradient. Content comes from the curated overlay by default, or
    from the CMS (`override`) when a product_poster block exists. */
export default function PosterBanner({ p, override }: { p: Product; override?: PosterOverride }) {
  const tagline = override?.tagline ?? p.tagline;
  if (!tagline) return null;
  const img = override?.img ?? p.gallery?.[1] ?? p.img;
  const dark = p.category === "Communion Elements" || p.category === "Gifts & Accessories";
  const eyebrow = override?.eyebrow ?? `${p.short} · Bethany House`;
  const specs = override?.specs
    ?? [...p.chips.map((c) => c.text), p.producible ? "Ready in 5–7 days" : "In stock — ships today"].join("  |  ");

  return (
    <section className={`poster ${dark ? "dark" : "warm"}`}>
      <span className="poster-eyebrow">{eyebrow}</span>
      <h2>{tagline}</h2>
      <p className="poster-specs">{specs}</p>
      <div className="poster-art"><img src={img} alt={p.name} /></div>
    </section>
  );
}
