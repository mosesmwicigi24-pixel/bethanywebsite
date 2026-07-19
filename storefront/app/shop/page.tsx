import Link from "next/link";
import type { Metadata } from "next";
import Crumbs from "@/components/Crumbs";
import { ProductCard } from "@/components/cards";
import { getCatalog } from "@/lib/catalog";

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

export default async function Shop() {
  const products = await getCatalog();

  // real category list, ordered by how many products each holds
  const counts = new Map<string, number>();
  for (const p of products) counts.set(p.category, (counts.get(p.category) ?? 0) + 1);
  const categories = [...counts.entries()].sort((a, b) => b[1] - a[1]);

  return (
    <main className="wrap">
      <Crumbs items={[{ label: "Home", href: "/" }, { label: "Communion & Clergy Store" }]} />
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
          {categories.map(([name, n]) => (
            <label className="f-row" key={name}><input type="checkbox" /> {name} <span className="sw" style={{ background: "transparent", color: "var(--muted)", width: "auto", border: "none", fontSize: 12 }}>{n}</span></label>
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
