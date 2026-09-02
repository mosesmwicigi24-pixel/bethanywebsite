import type { MetadataRoute } from "next";
import { getCatalog } from "@/lib/catalog";
import { CATEGORY_PAGES, rootCategory } from "@/lib/categories";
import { GUIDES } from "@/lib/guides";
import { SITE } from "@/lib/site";
import { abs } from "@/lib/seo";

/* Sitemap generated from the live hub catalog. Only parents + simple
   products are listed — variant deep-links canonicalise to their parent
   (see the product page's generateMetadata), so listing them would just
   invite duplicate-content crawling. Refreshes with the catalog because
   getCatalog() fetches with ISR; no rebuild needed. */
export default async function sitemap(): Promise<MetadataRoute.Sitemap> {
  const base = SITE.url;
  const products = await getCatalog();

  // lastModified only where a REAL timestamp exists (hub updated_at) —
  // Google uses accurate lastmod to schedule recrawls and ignores fake ones.
  const mtime = (iso?: string) => {
    const d = iso ? new Date(iso) : null;
    return d && !isNaN(d.getTime()) ? { lastModified: d } : {};
  };

  const productUrls: MetadataRoute.Sitemap = products
    .filter((p) => !p.variantId)
    .map((p) => {
      const primary = p.gallery?.length ? p.gallery[0] : p.img;
      const image = primary && !primary.endsWith(".svg") ? abs(primary) : undefined;
      return {
        url: `${base}/product/${p.slug}`,
        changeFrequency: "weekly" as const,
        priority: 0.8,
        ...mtime(p.updatedAt),
        ...(image ? { images: [image] } : {}),
      };
    });

  // A category page changes when any product it lists changes.
  const rootMtime = new Map<string, string>();
  for (const p of products) {
    if (p.variantId || !p.updatedAt) continue;
    const root = rootCategory(p.category);
    if (!rootMtime.has(root) || p.updatedAt > rootMtime.get(root)!) rootMtime.set(root, p.updatedAt);
  }
  const newest = [...rootMtime.values()].sort().pop();

  // /orders is deliberately absent: it is a noindexed customer utility
  // (see app/orders/layout.tsx) with nothing for search engines.
  return [
    { url: base, changeFrequency: "daily", priority: 1, ...mtime(newest) },
    { url: `${base}/shop`, changeFrequency: "daily", priority: 0.9, ...mtime(newest) },
    ...CATEGORY_PAGES.map((c) => ({
      url: `${base}/category/${c.slug}`,
      changeFrequency: "weekly" as const,
      priority: 0.9,
      ...mtime(rootMtime.get(c.root)),
    })),
    { url: `${base}/about`, changeFrequency: "monthly", priority: 0.6 },
    { url: `${base}/contact`, changeFrequency: "monthly", priority: 0.6 },
    { url: `${base}/faq`, changeFrequency: "monthly", priority: 0.6 },
    { url: `${base}/international`, changeFrequency: "monthly", priority: 0.7 },
    { url: `${base}/guides`, changeFrequency: "weekly", priority: 0.7, ...mtime([...GUIDES].map((g) => g.updated).sort().pop()) },
    ...GUIDES.map((g) => ({
      url: `${base}/guides/${g.slug}`,
      changeFrequency: "monthly" as const,
      priority: 0.7,
      ...mtime(g.updated),
    })),
    ...productUrls,
  ];
}
