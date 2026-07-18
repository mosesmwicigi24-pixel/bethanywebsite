import type { Product } from "@/lib/products";

/** oraimo-style product poster: campaign tagline, spec strip, hero image
    on a warm gradient — the "SMART CARE, TIMELESS DESIGN" pattern. */
export default function PosterBanner({ p }: { p: Product }) {
  if (!p.tagline) return null;
  const img = p.gallery?.[1] ?? p.img;
  const dark = p.category === "Communion Elements" || p.category === "Gifts & Accessories";

  return (
    <section className={`poster ${dark ? "dark" : "warm"}`}>
      <span className="poster-eyebrow">{p.short} · Bethany House</span>
      <h2>{p.tagline}</h2>
      <p className="poster-specs">
        {[...p.chips.map((c) => c.text), p.producible ? "Ready in 5–7 days" : "In stock — ships today"].join("  |  ")}
      </p>
      <div className="poster-art"><img src={img} alt={p.name} /></div>
    </section>
  );
}
