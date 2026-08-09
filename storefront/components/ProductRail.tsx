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
}: {
  title: string;
  products: Product[];
  cta?: string;
  href?: string;
  small?: boolean;
  tight?: boolean;
}) {
  return (
    <section className={`section wrap ${tight ? "section-tight" : ""}`}>
      <div className="section-head">
        <h2 className={small ? "sm" : ""}>{title}</h2>
        {cta && <Link href={href}>{cta} →</Link>}
      </div>
      <Rail navInWrap>
        {products.map((p) => <MiniCard key={p.slug} p={p} />)}
      </Rail>
    </section>
  );
}
