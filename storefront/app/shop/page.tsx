import Link from "next/link";
import type { Metadata } from "next";
import Crumbs from "@/components/Crumbs";
import { ProductCard } from "@/components/cards";
import { getCatalog } from "@/lib/catalog";
import { ROOT_CATEGORIES, rootCategory, type RootCategory } from "@/lib/categories";

export const metadata: Metadata = { title: "Shop" };
export const revalidate = 300; // ISR — catalog refreshes without a rebuild

const colours = [
  ["Gold", "#c9a227"],
  ["White", "#fff"],
  ["Purple", "#6b3fa0"],
  ["Red", "#b0312f"],
  ["Green", "#2f7d4f"],
  ["Black", "#111"],
] as const;

export default async function Shop({ searchParams }: { searchParams: Promise<{ category?: string }> }) {
  const { category } = await searchParams;
  // Show parents + simple products only — variants are selected in place on
  // the product page, not listed as their own cards.
  const all = (await getCatalog()).filter((p) => !p.variantId);

  // Fold every product onto its root department, count across the whole catalog.
  const counts = new Map<RootCategory, number>();
  for (const p of all) {
    const root = rootCategory(p.category);
    counts.set(root, (counts.get(root) ?? 0) + 1);
  }
  const roots = ROOT_CATEGORIES.filter((r) => (counts.get(r) ?? 0) > 0);
  const active = roots.find((r) => r === category) ?? null;

  const products = active ? all.filter((p) => rootCategory(p.category) === active) : all;

  return (
    <main className="wrap">
      <Crumbs items={[{ label: "Home", href: "/" }, { label: active ?? "Communion & Clergy Store" }]} />
      <div className="toolbar">
        <div></div>
        <div className="sort">Sort by
          <select defaultValue="Recommend">
            <option>Recommend</option>
            <option>Price: Low to High</option>
            <option>Price: High to Low</option>
            <option>Newest</option>
            <option>Top Rated</option>
          </select>
        </div>
      </div>

      <div className="catalog">
        <aside className="filters">
          <div className="count">Shopping Options ({products.length} Results)</div>
          <h4>Shop by Category</h4>
          <Link className={`f-row cat-link${!active ? " active" : ""}`} href="/shop">
            <span className="cbox" /> All Products <span className="fcount">{all.length}</span>
          </Link>
          {roots.map((r) => (
            <Link className={`f-row cat-link${active === r ? " active" : ""}`} key={r} href={`/shop?category=${encodeURIComponent(r)}`}>
              <span className="cbox" /> {r} <span className="fcount">{counts.get(r)}</span>
            </Link>
          ))}
          <h4>Shop by Liturgical Colour</h4>
          {colours.map(([name, hex]) => (
            <label className="f-row" key={name}>
              <input type="checkbox" /> {name} <span className="sw" style={{ background: hex }} />
            </label>
          ))}
          <h4>Shop by Price</h4>
          <div className="range"><i className="lo" /><i className="hi" /></div>
          <div className="range-labels"><span>KES 500</span><span>KES 50,000</span></div>
          <h4>Shop by Size</h4>
          {["S", "M", "L / XL", "Made to Measure"].map((s) => (
            <label className="f-row" key={s}><input type="checkbox" /> {s}</label>
          ))}
        </aside>

        <div className="grid-products">
          <div className="cat-banner">
            <div>
              <h3>Easter is coming.<br />Is the sanctuary ready?</h3>
              <p>Communion ware, fresh hosts and seasonal vestments — order early for guaranteed delivery before Holy Week.</p>
            </div>
            <img src="/products/gold-wares.jpg" alt="" />
            <Link className="pill pill-gold" href="/product/chalice-royale">Shop the set</Link>
          </div>
          {products.map((p) => <ProductCard key={p.slug} p={p} />)}
        </div>
      </div>
    </main>
  );
}
