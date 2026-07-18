import Link from "next/link";
import { TrustRow } from "@/components/chrome";
import Rail from "@/components/Rail";
import { ProductCard, MiniCard, LineupCard, EditorialCard } from "@/components/cards";
import { bySlug } from "@/lib/products";

const pick = (...slugs: string[]) =>
  slugs.map(bySlug).filter((p): p is NonNullable<typeof p> => Boolean(p));

export default function Home() {
  const bestSellers = pick("chalice-royale", "preaching-gown", "altar-wine", "pectoral-cross");
  const fresh = pick("ornate-chasuble", "clergy-shirt", "communion-hosts", "altar-bell", "devotional-365", "tallit-prayer-shawl");

  return (
    <main>
      <TrustRow />

      {/* Apple-style index hero */}
      <header className="hero-index wrap">
        <h1>Everything the<br />altar calls for.</h1>
        <p className="sub">
          Holy Communion elements, clergy apparel and Christian gifts — supplied to
          churches across East Africa from the heart of Nairobi.
        </p>
        <div className="cat-index">
          <Link href="/shop"><span className="thumb"><img src="/products/Chalice_Cup.jpg" alt="" /></span>Communion</Link>
          <Link href="/shop"><span className="thumb"><img src="/products/preaching_gown1.jpg" alt="" /></span>Clergy Apparel<span className="new">New</span></Link>
          <Link href="/shop"><span className="thumb"><img src="/products/niv_bible.jpg" alt="" /></span>Bibles</Link>
          <Link href="/shop"><span className="thumb"><img src="/products/cross1.jpg" alt="" /></span>Gifts</Link>
          <Link href="/shop"><span className="thumb"><img src="/products/bell.jpg" alt="" /></span>Essentials</Link>
          <Link href="/shop"><span className="thumb"><img src="/products/tallit.jpg" alt="" /></span>Prayer Wear</Link>
        </div>
      </header>

      {/* Explore the collection */}
      <section className="band">
        <div className="wrap"><h2>Explore the collection.</h2></div>
        <Rail>
          <LineupCard href="/product/chalice-royale" img="/products/Chalice_Cup.jpg"
            dots={["#c9a227", "#cfd3da"]} title="Chalice Royale"
            blurb="24K gold-plated chalice & paten. The centrepiece of the Lord's Table." />
          <LineupCard href="/shop" img="/products/preaching_gown1.jpg"
            dots={["#111", "#6b3fa0", "#b0312f", "#fff"]} title="Preaching Gowns"
            blurb="Tailored in Nairobi. Made to measure for every pulpit." />
          <LineupCard href="/shop" img="/products/gold-wares.jpg"
            dots={["#c9a227", "#cfd3da"]} title="Communion Sets"
            blurb="Complete ware for congregations of 50 to 5,000." />
          <LineupCard href="/shop" img="/products/Stoles5.jpg"
            dots={["#2f7d4f", "#6b3fa0", "#b0312f", "#c9a227"]} title="Stoles & Vestments"
            blurb="Every liturgical colour, embroidered by hand." />
          <LineupCard href="/shop" img="/products/365days.png"
            dots={["#16355e"]} title="Bibles & Devotionals"
            blurb="From children's Bibles to 365-day devotionals." />
        </Rail>
      </section>

      {/* Best sellers */}
      <section className="section wrap">
        <div className="section-head"><h2>Best sellers.</h2><Link href="/shop">View all →</Link></div>
        <div className="grid-products">
          {bestSellers.map((p) => <ProductCard key={p.slug} p={p} />)}
        </div>
      </section>

      {/* Get to know */}
      <section className="dark-band">
        <div className="wrap"><h2>Get to know Bethany House.</h2></div>
        <Rail dark>
          <EditorialCard eyebrow="Craft" title={["Set apart", "for the sacred."]} img="/products/gold-wares.jpg" />
          <EditorialCard eyebrow="Tailoring" title={["Vestments,", "made to measure."]} img="/products/cassock212.jpg" />
          <EditorialCard eyebrow="Delivery" title={["Nairobi today.", "East Africa this week."]} img="/products/usher.jpg" />
          <EditorialCard eyebrow="Heritage" title={["Serving the church,", "faithfully."]} img="/products/bishop.jpeg" />
          <EditorialCard eyebrow="Prayer Wear" title={["The tallit,", "woven with meaning."]} img="/products/tallit.jpg" />
        </Rail>
      </section>

      {/* Fresh on the shelves */}
      <section className="section wrap">
        <div className="section-head"><h2>Fresh on the shelves.</h2><Link href="/shop">Shop new arrivals →</Link></div>
        <Rail navInWrap>
          {fresh.map((p) => <MiniCard key={p.slug} p={p} />)}
        </Rail>
      </section>
    </main>
  );
}
