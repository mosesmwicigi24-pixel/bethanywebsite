import Link from "next/link";
import type { Metadata } from "next";
import Crumbs from "@/components/Crumbs";
import JsonLd from "@/components/JsonLd";
import { GUIDES } from "@/lib/guides";
import { breadcrumbJsonLd } from "@/lib/seo";
import { SITE } from "@/lib/site";

/* Guides hub — the index of the editorial section. */

export const metadata: Metadata = {
  title: "Guides",
  description:
    "Practical guides from Bethany House — covenant rings, communion bread quantities, liturgical colours, vestment measurements and more, answered plainly.",
  alternates: { canonical: "/guides" },
  openGraph: {
    type: "website",
    title: `Guides | ${SITE.name}`,
    description: "Plain answers to the questions churches actually ask — from the counter at Moi Avenue.",
    url: `${SITE.url}/guides`,
  },
};

export default function GuidesPage() {
  return (
    <main className="wrap cat-page">
      <JsonLd data={breadcrumbJsonLd([{ name: "Home", path: "/" }, { name: "Guides" }])} />

      <Crumbs items={[{ label: "Home", href: "/" }, { label: "Guides" }]} />

      <header className="cat-head">
        <h1>Guides from the counter</h1>
        <p>
          The questions churches ask us across the counter at Moi Avenue,
          answered plainly — what things mean, how much to order, how to
          measure, what each season calls for. Every guide ends with the
          products it discussed, stocked in Nairobi.
        </p>
      </header>

      <section className="guide-grid">
        {GUIDES.map((g) => (
          <Link key={g.slug} href={`/guides/${g.slug}`} className="guide-card">
            <span className="eyebrow">{g.category}</span>
            <h2>{g.title}</h2>
            <p>{g.description}</p>
            <span className="go">Read the guide — {g.minutes} min ›</span>
          </Link>
        ))}
      </section>

      <section className="cat-cta">
        <div>
          <b>A question no guide answers yet?</b>
          <p>Ask a person — {SITE.hours}, or any time on WhatsApp. The best questions become the next guide.</p>
        </div>
        <div className="cta-row">
          <a className="pill pill-gold" href={SITE.whatsapp} target="_blank" rel="noopener">WhatsApp {SITE.phone}</a>
          <Link className="pill pill-ghost" href="/faq">Browse the FAQs</Link>
        </div>
      </section>
    </main>
  );
}
