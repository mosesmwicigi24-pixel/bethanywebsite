import Link from "next/link";
import type { Metadata } from "next";
import Crumbs from "@/components/Crumbs";
import JsonLd from "@/components/JsonLd";
import Reveal from "@/components/Reveal";
import { abs, breadcrumbJsonLd } from "@/lib/seo";
import { SITE } from "@/lib/site";

/* About page — the founder story. Every claim here is grounded either in
   lib/site.ts business facts or in the Daily Nation feature on Bethany House
   (nation.africa, "How the Holy Communion has become a profitable business"):
   sourcing began 2014, formal establishment 2019, elements imported from
   Israel, Cyprus, the US and China, recurring church orders. */

const NATION_URL =
  "https://nation.africa/kenya/life-and-style/how-the-holy-communion-has-become-a-profitable-business-5285876";

export const metadata: Metadata = {
  title: "About Us",
  description:
    "How Bethany House grew from sourcing scarce communion wafers in 2014 into Nairobi's dedicated church-supply store — communion elements, made-to-measure vestments and church gifts on Moi Avenue.",
  alternates: { canonical: "/about" },
  openGraph: {
    type: "website",
    title: `About Us | ${SITE.name}`,
    description:
      "The story of Bethany House — supplying Holy Communion elements, clergy apparel and church gifts from Moi Avenue, Nairobi since 2014.",
    url: `${SITE.url}/about`,
  },
};

export default function AboutPage() {
  return (
    <main className="wrap cat-page">
      <JsonLd data={breadcrumbJsonLd([{ name: "Home", path: "/" }, { name: "About Us" }])} />
      <JsonLd data={{
        "@context": "https://schema.org",
        "@type": "AboutPage",
        name: `About ${SITE.name}`,
        url: abs("/about"),
        mainEntity: { "@id": `${SITE.url}/#organization` },
      }} />

      <Crumbs items={[{ label: "Home", href: "/" }, { label: "About Us" }]} />

      <header className="cat-head">
        <h1>The story of Bethany House</h1>
        <p>
          Communion elements, clergy apparel and church gifts — supplied from
          Moi Avenue, Nairobi to churches across Kenya, East Africa and beyond.
        </p>
        <div className="cat-facts">
          <span>Sourcing communion elements since 2014</span>
          <span>Established 2019</span>
          <span>Imported from Israel, Cyprus, the US &amp; China</span>
          <span>{SITE.hours}</span>
        </div>
      </header>

      <Reveal as="section" className="about-story">
        <h2>It began with a shortage at the Lord&rsquo;s Table</h2>
        <p>
          In 2014, Pastor Moses Mwicigi kept meeting the same quiet problem in
          Nairobi&rsquo;s churches: when it was time for Holy Communion, the
          elements were hard to come by. The Catholic Church had long-standing
          supply lines for wafers and sacramental wine — but congregations
          outside that tradition often had nowhere reliable to turn.
        </p>
        <p>
          So he began sourcing the elements himself — communion wafers,
          sacramental wine and unfermented grape juice — first for a handful of
          churches, then for the churches those churches told.
        </p>

        <h2>A store on Moi Avenue</h2>
        <p>
          In 2019 that work was formally established as {SITE.name}. Today the
          store at {SITE.address}, Nairobi CBD carries everything the sacrament
          needs — wafers and hosts, altar wine and grape juice, trays, cups,
          chalices and ciboria — alongside clergy vestments made to measure in
          our Nairobi workshop and gifts for ordinations, anniversaries and
          every church milestone.
        </p>

        <h2>Sourced worldwide, supplied from Nairobi</h2>
        <p>
          We import communion elements from Israel, Cyprus, the United States
          and China, hold stock in Nairobi, and deliver — free in Nairobi CBD
          for orders above KES 10,000, across Kenya and East Africa in 2–4
          working days, and worldwide on request. Vestments and gowns are
          measured and sewn here in Nairobi, ready in 5–7 days.
        </p>

        <h2>Churches that come back</h2>
        <p>
          Most of what we do is repeat business: parishes and dioceses that
          restock every season and know exactly who to call. Churches that use
          wine and churches that use unfermented grape juice are equally
          served, engraving on communion ware is free, and prices can be viewed
          in KES, USD or ZMW.
        </p>
      </Reveal>

      <a className="press-card" href={NATION_URL} target="_blank" rel="noopener">
        <span className="kicker">As featured in the Daily Nation</span>
        <p>
          The Daily Nation profiled how Bethany House grew from sourcing scarce
          communion wafers into one of Nairobi&rsquo;s dedicated church-supply
          stores — and how Kenya&rsquo;s churches keep the Lord&rsquo;s Table set.
        </p>
        <span className="go">Read the feature on nation.africa ›</span>
      </a>

      <Reveal as="section" className="about-pillars">
        <div className="pillar">
          <div className="ico">✚</div>
          <h3>The Lord&rsquo;s Table</h3>
          <p>Wafers and hosts, altar wine and unfermented grape juice, trays, cups, chalices and ciboria — for congregations of every size.</p>
        </div>
        <div className="pillar">
          <div className="ico">✂</div>
          <h3>The pulpit &amp; the procession</h3>
          <p>Cassocks, gowns, albs, stoles, shirts and collars — ready-made off the rack, or made to measure in our Nairobi workshop in 5–7 days.</p>
        </div>
        <div className="pillar">
          <div className="ico">✍</div>
          <h3>The milestones</h3>
          <p>Ordinations, anniversaries and dedications — lasting gifts, with free engraving on communion ware.</p>
        </div>
      </Reveal>

      <section className="cat-cta">
        <div>
          <b>Come see the store — or send your church&rsquo;s list on WhatsApp.</b>
          <p>{SITE.address}, Nairobi. {SITE.hours}. {SITE.landmarks}</p>
        </div>
        <div className="cta-row">
          <a className="pill pill-gold" href={SITE.whatsapp} target="_blank" rel="noopener">WhatsApp us</a>
          <Link className="pill pill-ghost" href="/contact">Contact &amp; directions</Link>
        </div>
      </section>
    </main>
  );
}
