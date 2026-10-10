import Link from "next/link";
import Rail from "./Rail";
import { MiniCard } from "./cards";
import type { Product } from "@/lib/products";

/** A titled horizontal product carousel — used on home, product pages, etc. */
export default function ProductRail({
  title,
  products,
  cta,
  href = "/shop",
  small = false,
  tight = false,
  eyebrow,
  className = "",
}: {
  title: string;
  products: Product[];
  cta?: string;
  href?: string;
  small?: boolean;
  tight?: boolean;
  /** small tracked label above the title */
  eyebrow?: string;
  className?: string;
}) {
  return (
    <section className={`section wrap ${tight ? "section-tight" : ""} ${className}`}>
      <div className="section-head">
        <div>
          {eyebrow ? <span className="section-eyebrow">{eyebrow}</span> : null}
          <h2 className={small ? "sm" : ""}>{title}</h2>
        </div>
        {cta && <Link href={href}>{cta} →</Link>}
      </div>
      <Rail navInWrap>
        {products.map((p) => <MiniCard key={p.slug} p={p} />)}
      </Rail>
    </section>
  );
}
