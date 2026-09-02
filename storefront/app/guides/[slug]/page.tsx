import Link from "next/link";
import type { Metadata } from "next";
import { notFound } from "next/navigation";
import Crumbs from "@/components/Crumbs";
import JsonLd from "@/components/JsonLd";
import ProductRail from "@/components/ProductRail";
import { getCatalog } from "@/lib/catalog";
import { GUIDES, guideBySlug } from "@/lib/guides";
import { abs, breadcrumbJsonLd, faqPageJsonLd } from "@/lib/seo";
import { SITE } from "@/lib/site";

/* Guide article — long-form answer to one search phrase-family, ending in
   the products it discussed. Article + BreadcrumbList + FAQPage schema. */

export const revalidate = 300; // product rail follows the hub catalog

export function generateStaticParams() {
  return GUIDES.map((g) => ({ slug: g.slug }));
}

export async function generateMetadata(
  { params }: { params: Promise<{ slug: string }> },
): Promise<Metadata> {
  const { slug } = await params;
  const g = guideBySlug(slug);
  if (!g) return {};
  return {
    title: g.metaTitle,
    description: g.description,
    alternates: { canonical: `/guides/${g.slug}` },
    openGraph: {
      type: "article",
      title: `${g.title} | ${SITE.name}`,
      description: g.description,
      url: `${SITE.url}/guides/${g.slug}`,
      publishedTime: g.published,
      modifiedTime: g.updated,
    },
  };
}

/** "2 September 2026" — fixed locale so ISR output is stable. */
const prettyDate = (iso: string) =>
  new Date(iso).toLocaleDateString("en-GB", { year: "numeric", month: "long", day: "numeric" });

export default async function GuidePage(
  { params }: { params: Promise<{ slug: string }> },
) {
  const { slug } = await params;
  const g = guideBySlug(slug);
  if (!g) notFound();

  const catalog = await getCatalog();
  const products = g.productSlugs
    .map((s) => catalog.find((p) => p.slug === s))
    .filter((p): p is NonNullable<typeof p> => Boolean(p));
  const related = g.related
    .map((s) => guideBySlug(s))
    .filter((r): r is NonNullable<typeof r> => Boolean(r));

  return (
    <main className="wrap cat-page">
      <JsonLd data={breadcrumbJsonLd([
        { name: "Home", path: "/" },
        { name: "Guides", path: "/guides" },
        { name: g.title },
      ])} />
      <JsonLd data={faqPageJsonLd(g.faqs)} />
      <JsonLd data={{
        "@context": "https://schema.org",
        "@type": "Article",
        headline: g.title,
        description: g.description,
        datePublished: g.published,
        dateModified: g.updated,
        image: abs("/brand/logo-light.png"),
        author: { "@type": "Organization", name: SITE.name, url: SITE.url },
        publisher: { "@id": `${SITE.url}/#organization` },
        mainEntityOfPage: abs(`/guides/${g.slug}`),
      }} />

      <Crumbs items={[
        { label: "Home", href: "/" },
        { label: "Guides", href: "/guides" },
        { label: g.title },
      ]} />

      <header className="cat-head guide-head">
        <span className="eyebrow">{g.category} · {g.minutes} min read · Updated {prettyDate(g.updated)}</span>
        <h1>{g.title}</h1>
        {g.intro.map((p, i) => <p key={i}>{p}</p>)}
      </header>

      <article className="about-story guide-body">
        {g.sections.map((s) => (
          <section key={s.h}>
            <h2>{s.h}</h2>
            {s.p.map((para, i) => <p key={i}>{para}</p>)}
          </section>
        ))}
      </article>

      {products.length > 0 && (
        <ProductRail
          title="Shop this guide"
          products={products}
          cta="View the department"
          href={g.shopHref}
          small
          tight
        />
      )}

      <section className="faqs cat-faqs">
        <h4>Quick answers</h4>
        {g.faqs.map((f) => (
          <details key={f.q}>
            <summary>{f.q}</summary>
            <p>{f.a}</p>
          </details>
        ))}
      </section>

      <section className="cat-related">
        <span>Keep reading:</span>
        {related.map((r) => (
          <Link key={r.slug} href={`/guides/${r.slug}`}>{r.title}</Link>
        ))}
        <Link href="/guides">All guides</Link>
      </section>

      <section className="cat-cta">
        <div>
          <b>Rather ask a person?</b>
          <p>{SITE.hours} at {SITE.address}, Nairobi — or any hour on WhatsApp.</p>
        </div>
        <div className="cta-row">
          <a className="pill pill-gold" href={SITE.whatsapp} target="_blank" rel="noopener">WhatsApp us</a>
          <a className="pill pill-ghost" href={SITE.phoneHref}>Call {SITE.phone}</a>
        </div>
      </section>
    </main>
  );
}
