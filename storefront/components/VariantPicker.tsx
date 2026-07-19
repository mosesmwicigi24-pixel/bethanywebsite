import Link from "next/link";
import type { Product } from "@/lib/products";

/** Sibling variants of the same hub product, each its own page. Rendered
    as thumbnail links so a customer can jump between colours/options. */
export default function VariantPicker({ current, variants }: { current: string; variants: Product[] }) {
  if (variants.length < 2) return null;
  return (
    <div className="opt vpick-wrap">
      <span className="vpick-label">
        Options
        <em>{variants.length} available</em>
      </span>
      <div className="vpick">
        {variants.map((v) => (
          <Link
            key={v.slug}
            href={`/product/${v.slug}`}
            className={`vpick-item ${v.slug === current ? "on" : ""}`}
            aria-current={v.slug === current}
            title={v.variantAttributes ? Object.values(v.variantAttributes).join(" · ") : v.short}
          >
            <img src={v.img} alt={v.short} />
          </Link>
        ))}
      </div>
    </div>
  );
}
