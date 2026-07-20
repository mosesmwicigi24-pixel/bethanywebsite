import type { MetadataRoute } from "next";
import { SITE } from "@/lib/site";

/* Let crawlers index the catalogue; keep transactional and receipt pages
   out of the index (carts and tokenised order receipts have no search
   value and shouldn't be surfaced). Points crawlers at the sitemap. */
export default function robots(): MetadataRoute.Robots {
  return {
    rules: [
      {
        userAgent: "*",
        allow: "/",
        disallow: ["/cart", "/checkout", "/order/"],
      },
    ],
    sitemap: `${SITE.url}/sitemap.xml`,
    host: SITE.url,
  };
}
