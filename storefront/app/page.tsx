import Link from "next/link";
import Rail from "@/components/Rail";
import HeroCarousel from "@/components/HeroCarousel";
import Reveal from "@/components/Reveal";
import ProductRail from "@/components/ProductRail";
import { ProductCard, LineupCard, EditorialCard } from "@/components/cards";
import { bySlug } from "@/lib/products";

const pick = (...slugs: string[]) =>
  slugs.map(bySlug).filter((p): p is NonNullable<typeof p> => Boolean(p));

export default function Home() {
  const bestSellers = pick("chalice-royale", "preaching-gown", "altar-wine", "pectoral-cross");
  const fresh = pick("ornate-chasuble", "clergy-shirt", "communion-hosts", "altar-bell", "devotional-365", "tallit-prayer-shawl");

  return (
    <main>
      {/* ---- Rotating campaign hero ---- */}
      <HeroCarousel />
      <div className="creed">
        <div className="wrap">
          <span><i>✦</i> Free Nairobi delivery over KES 2,000</span>
          <span><i>✦</i> Sonalux Building, Moi Avenue</span>
          <span><i>✦</i> Parish &amp; diocese accounts</span>
          <span><i>✦</i> Free engraving on communion ware</span>
        </div>
      </div>

      {/* ---- Shop by category: full-width tile grid ---- */}
      <Reveal as="section" className="section wrap">
        <div className="section-head">
          <h2>Shop by category.</h2>
          <Link href="/shop">All products →</Link>
        </div>
        <div className="cat-tiles">
          <Link className="cat-tile" href="/shop">
            <span className="im"><img src="/products/Chalice_Cup.jpg" alt="Communion elements" /></span>
            <span className="lbl">Communion <i>›</i></span>
            <span className="sub">Chalices · wine · hosts</span>
          </Link>
          <Link className="cat-tile" href="/shop">
            <span className="im"><img src="/products/preaching_gown1.jpg" alt="Clergy apparel" /></span>
            <span className="lbl">Clergy Apparel <i>›</i></span>
            <span className="newdot">New arrivals</span>
          </Link>
          <Link className="cat-tile" href="/shop">
            <span className="im"><img src="/products/niv_bible.jpg" alt="Bibles" /></span>
            <span className="lbl">Bibles <i>›</i></span>
            <span className="sub">Study · children's · gift</span>
          </Link>
          <Link className="cat-tile" href="/shop">
            <span className="im"><img src="/products/cross1.jpg" alt="Gifts" /></span>
            <span className="lbl">Gifts <i>›</i></span>
            <span className="sub">Crosses · keepsakes</span>
          </Link>
          <Link className="cat-tile" href="/shop">
            <span className="im"><img src="/products/bell.jpg" alt="Church essentials" /></span>
            <span className="lbl">Essentials <i>›</i></span>
            <span className="sub">Bells · linens · ware</span>
          </Link>
          <Link className="cat-tile" href="/shop">
            <span className="im"><img src="/products/tallit.jpg" alt="Prayer wear" /></span>
            <span className="lbl">Prayer Wear <i>›</i></span>
            <span className="sub">Tallits · shawls</span>
          </Link>
        </div>
      </Reveal>

      {/* ---- Promo banner duo ---- */}
      <Reveal as="section" className="wrap">
        <div className="promo-duo">
          <div className="promo-b navy">
            <div className="txt">
              <span className="eyebrow">Holy Week Offer</span>
              <h3>The Lord&apos;s Table, complete.</h3>
              <p>Chalice, altar wine and 1,000 hosts — bundled from KES 21,800.</p>
              <Link className="pill pill-gold" href="/product/chalice-royale">Shop the bundle</Link>
            </div>
            <img src="/products/gold-wares.jpg" alt="" />
          </div>
          <div className="promo-b ivory">
            <div className="txt">
              <span className="eyebrow">Made to Measure</span>
              <h3>Tailored for the pulpit.</h3>
              <p>Gowns, cassocks and chasubles measured in Nairobi — ready in 5–7 days.</p>
              <Link className="pill pill-solid" href="/shop">Book a fitting</Link>
            </div>
            <img src="/products/preaching_gown1.jpg" alt="" />
          </div>
        </div>
      </Reveal>

      {/* ---- Explore the collection ---- */}
      <section className="band">
        <Reveal>
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
        </Reveal>
      </section>

      {/* ---- Best sellers ---- */}
      <Reveal as="section" className="section wrap">
        <div className="section-head"><h2>Best sellers.</h2><Link href="/shop">View all →</Link></div>
        <div className="grid-products">
          {bestSellers.map((p) => <ProductCard key={p.slug} p={p} />)}
        </div>
      </Reveal>

      {/* ---- Get to know ---- */}
      <section className="dark-band">
        <Reveal>
          <div className="wrap"><h2>Get to know Bethany House.</h2></div>
          <Rail dark>
            <EditorialCard eyebrow="Craft" title={["Set apart", "for the sacred."]} img="/products/gold-wares.jpg" />
            <EditorialCard eyebrow="Tailoring" title={["Vestments,", "made to measure."]} img="/products/cassock212.jpg" />
            <EditorialCard eyebrow="Delivery" title={["Nairobi today.", "East Africa this week."]} img="/products/usher.jpg" />
            <EditorialCard eyebrow="Heritage" title={["Serving the church,", "faithfully."]} img="/products/bishop.jpeg" />
            <EditorialCard eyebrow="Prayer Wear" title={["The tallit,", "woven with meaning."]} img="/products/tallit.jpg" />
          </Rail>
        </Reveal>
      </section>

      {/* ---- Fresh on the shelves ---- */}
      <Reveal>
        <ProductRail title="Fresh on the shelves." cta="Shop new arrivals" products={fresh} />
      </Reveal>

      {/* ---- Testimonials ---- */}
      <section className="testis">
        <Reveal className="wrap">
          <div className="section-head"><h2>Loved at the altar.</h2></div>
          <div className="grid">
            <article className="testi">
              <div className="stars">★★★★★</div>
              <q>The finish is far richer in person than in the photos. Delivered to Nakuru in two days, packed like treasure.</q>
              <div className="who"><span className="av">M</span><span><b>Rev. Canon Mwangi</b><span>Cathedral parish, Nakuru</span></span></div>
            </article>
            <article className="testi">
              <div className="stars">★★★★★</div>
              <q>Our preaching gowns were measured on Tuesday and worn that Sunday. The tailoring is simply excellent.</q>
              <div className="who"><span className="av">A</span><span><b>Pastor Achieng O.</b><span>Nairobi West</span></span></div>
            </article>
            <article className="testi">
              <div className="stars">★★★★★</div>
              <q>One supplier for hosts, wine and ware — with a parish account. Bethany House understands how churches buy.</q>
              <div className="who"><span className="av">K</span><span><b>Fr. Kamau</b><span>Diocese procurement</span></span></div>
            </article>
          </div>
        </Reveal>
      </section>

      {/* ---- Why Bethany ---- */}
      <section className="why">
        <Reveal className="wrap">
          <div className="section-head"><h2>Why Bethany House.</h2></div>
          <div className="grid">
            <article className="pillar"><div className="ico">✦</div><h3>Sanctuary-grade craft</h3><p>Communion ware in 24K electroplate, vestments embroidered by hand — chosen to be worthy of their use.</p></article>
            <article className="pillar"><div className="ico">✂</div><h3>Made to measure</h3><p>Cassocks, gowns and chasubles tailored in Nairobi to your measurements, in every liturgical colour.</p></article>
            <article className="pillar"><div className="ico">🚚</div><h3>Delivery you can plan on</h3><p>Same-day within Nairobi, days across East Africa — with delivery dates you can schedule services around.</p></article>
            <article className="pillar"><div className="ico">🤝</div><h3>With you after the sale</h3><p>Engraving, repairs, replacements and parish accounts — a supplier relationship, not a transaction.</p></article>
          </div>
        </Reveal>
      </section>

      {/* ---- Newsletter vesper ---- */}
      <section className="vespers">
        <Reveal>
          <h2>Grace in <em>every</em> detail.</h2>
          <p>New arrivals, seasonal collections and parish offers — a short letter, once a month.</p>
          <div className="newsletter"><input placeholder="Your email address" aria-label="Email address" /><button>Subscribe</button></div>
        </Reveal>
      </section>
    </main>
  );
}
