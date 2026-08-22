import Link from "next/link";
import type { Metadata } from "next";
import { permanentRedirect } from "next/navigation";
import Crumbs from "@/components/Crumbs";
import JsonLd from "@/components/JsonLd";
import Reveal from "@/components/Reveal";
import { ProductCard } from "@/components/cards";
import { getCatalog } from "@/lib/catalog";
import { CATEGORY_PAGES, categoryPage, rootCategory } from "@/lib/categories";
import { breadcrumbJsonLd, itemListJsonLd, faqPageJsonLd } from "@/lib/seo";
import { SITE } from "@/lib/site";

/* Category landing pages — the indexable department hubs. The /shop query
   filters remain the browsing UI; these pages exist so "clergy vestments",
   "communion elements" etc. have a real page to rank, with genuine intro
   copy, a crawlable product grid, FAQs, and ItemList/FAQPage markup. */

export const revalidate = 300; // ISR — product grid follows the hub catalog

export function generateStaticParams() {
  return CATEGORY_PAGES.map((c) => ({ slug: c.slug }));
}

export async function generateMetadata(
  { params }: { params: Promise<{ slug: string }> },
): Promise<Metadata> {
  const { slug } = await params;
  const def = categoryPage(slug);
  if (!def) return {}; // the page itself will redirect
  return {
    title: def.title,
    description: def.description,
    alternates: { canonical: `/category/${def.slug}` },
    openGraph: {
      type: "website",
      title: `${def.name} | ${SITE.name}`,
      description: def.description,
      url: `${SITE.url}/category/${def.slug}`,
    },
  };
}

export default async function CategoryPage(
  { params }: { params: Promise<{ slug: string }> },
) {
  const { slug } = await params;
  const def = categoryPage(slug);
  // Unknown /category/* addresses are legacy-site leftovers (the old site's
  // suffixed slugs, e.g. clergy-vestments-fofem6, minus the ones redirected
  // explicitly in next.config.ts). Forward them permanently to the shop
  // instead of 404ing away their remaining equity.
  if (!def) permanentRedirect("/shop");

  const catalog = (await getCatalog()).filter((p) => !p.variantId);
  const products = catalog.filter((p) => rootCategory(p.category) === def.root);
  const related = def.related
    .map((s) => categoryPage(s))
    .filter((c): c is NonNullable<typeof c> => Boolean(c));
  const path = `/category/${def.slug}`;

  return (
    <main className="wrap cat-page">
      <JsonLd data={breadcrumbJsonLd([
        { name: "Home", path: "/" },
        { name: "Shop", path: "/shop" },
        { name: def.name },
      ])} />
      <JsonLd data={itemListJsonLd(def.name, path, products)} />
      <JsonLd data={faqPageJsonLd(def.faqs)} />

      <Crumbs items={[
        { label: "Home", href: "/" },
        { label: "Shop", href: "/shop" },
        { label: def.name },
      ]} />

      <header className="cat-head">
        <h1>{def.name}</h1>
        {def.intro.map((p, i) => <p key={i}>{p}</p>)}
        <div className="cat-facts">
          <span>M-Pesa · Visa · Mastercard · Cash on Delivery</span>
          <span>Free Nairobi CBD delivery over KES 10,000</span>
          <span>Kenya · East Africa · worldwide on request</span>
        </div>
      </header>

      <Reveal as="section" className="section-tight">
        <div className="section-head">
          <h2 className="sm">{products.length} product{products.length === 1 ? "" : "s"} in {def.name}</h2>
          <Link href="/shop">All products →</Link>
        </div>
        <div className="grid-products">
          {products.map((p) => <ProductCard key={p.slug} p={p} />)}
        </div>
      </Reveal>

      <section className="faqs cat-faqs">
        <h4>Common questions</h4>
        {def.faqs.map((f) => (
          <details key={f.q}>
            <summary>{f.q}</summary>
            <p>{f.a}</p>
          </details>
        ))}
      </section>

      <section className="cat-related">
        <span>Also explore:</span>
        {related.map((r) => (
          <Link key={r.slug} href={`/category/${r.slug}`}>{r.name}</Link>
        ))}
      </section>

      <section className="cat-cta">
        <div>
          <b>Not sure what your church needs?</b>
          <p>Message us on WhatsApp or visit the store — {SITE.address}, Nairobi. {SITE.hours}.</p>
        </div>
        <div className="cta-row">
          <a className="pill pill-gold" href="https://wa.me/254727891989" target="_blank" rel="noopener">WhatsApp us</a>
          <a className="pill pill-ghost" href={SITE.phoneHref}>Call {SITE.phone}</a>
        </div>
      </section>
    </main>
  );
}
