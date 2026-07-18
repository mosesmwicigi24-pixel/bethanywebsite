import Link from "next/link";
import type { Metadata } from "next";
import { ProductCard } from "@/components/cards";
import { products } from "@/lib/products";

export const metadata: Metadata = { title: "Shop" };

const colours = [
  ["Gold", "#c9a227"],
  ["White", "#fff"],
  ["Purple", "#6b3fa0"],
  ["Red", "#b0312f"],
  ["Green", "#2f7d4f"],
  ["Black", "#111"],
] as const;

const categories = [
  ["Communion Elements", true],
  ["Clergy Apparel", true],
  ["Bibles & Devotionals", false],
  ["Gifts & Accessories", false],
  ["Church Essentials", false],
] as const;

export default function Shop() {
  return (
    <main className="wrap">
      <div className="crumbs">
        <Link href="/">Home</Link><span className="sep">»</span><b>Communion &amp; Clergy Store</b>
      </div>
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
          <div className="count">Shopping Options ({products.length * 4} Results)</div>
          <h4>Shop by Category</h4>
          {categories.map(([name, on]) => (
            <label className="f-row" key={name}><input type="checkbox" defaultChecked={on} /> {name}</label>
          ))}
          <h4>Shop by Liturgical Colour</h4>
          {colours.map(([name, hex]) => (
            <label className="f-row" key={name}>
              <input type="checkbox" /> {name} <span className="sw" style={{ background: hex }} />
            </label>
          ))}
          <h4>Shop by Price</h4>
          <div className="range"><i className="lo" /><i className="hi" /></div>
          <div style={{ display: "flex", justifyContent: "space-between", fontSize: "12.5px", color: "#666" }}>
            <span>KES 500</span><span>KES 50,000</span>
          </div>
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
