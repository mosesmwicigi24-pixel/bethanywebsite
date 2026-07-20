import type { MetadataRoute } from "next";
import { getCatalog } from "@/lib/catalog";
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

  const productUrls: MetadataRoute.Sitemap = products
    .filter((p) => !p.variantId)
    .map((p) => {
      const primary = p.gallery?.length ? p.gallery[0] : p.img;
      const image = primary && !primary.endsWith(".svg") ? abs(primary) : undefined;
      return {
        url: `${base}/product/${p.slug}`,
        changeFrequency: "weekly" as const,
        priority: 0.8,
        ...(image ? { images: [image] } : {}),
      };
    });

  return [
    { url: base, changeFrequency: "daily", priority: 1 },
    { url: `${base}/shop`, changeFrequency: "daily", priority: 0.9 },
    { url: `${base}/orders`, changeFrequency: "monthly", priority: 0.3 },
    ...productUrls,
  ];
}
