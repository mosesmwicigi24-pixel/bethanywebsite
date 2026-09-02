import { getCatalog } from "@/lib/catalog";
import { abs } from "@/lib/seo";
import { SITE } from "@/lib/site";

/* Google Merchant Center product feed (RSS 2.0 + g: namespace).
   Point Merchant Center's scheduled fetch at /merchant-feed.xml and the
   Shopping surfaces stay in step with the hub catalog automatically.

   Rules (per Google's product data spec):
   • parents + simple products only — variants canonicalise to parents
   • items need a real raster image: products still on the SVG placeholder
     are EXCLUDED until they get a photo (Merchant Center rejects them
     anyway; excluding keeps the feed clean)
   • no GTIN/MPN exists for this catalog → identifier_exists = no
   • prices in KES exactly as charged */

export const revalidate = 300; // follows the hub catalog like every page

const esc = (s: string) =>
  s.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;");

export async function GET() {
  const catalog = await getCatalog();
  const items = catalog
    .filter((p) => !p.variantId && p.price > 0)
    .map((p) => {
      const gallery = (p.gallery?.length ? p.gallery : [p.img]).filter(Boolean);
      const raster = gallery.filter((g) => !g.endsWith(".svg"));
      if (!raster.length) return null; // no real photo yet — excluded
      const [primary, ...extra] = raster.map(abs);
      const availability = p.inStock === false ? "out_of_stock" : "in_stock";
      return `  <item>
    <g:id>${esc(p.slug)}</g:id>
    <g:title>${esc(p.name)}</g:title>
    <g:description>${esc(p.short || p.name)}</g:description>
    <g:link>${esc(abs(`/product/${p.slug}`))}</g:link>
    <g:image_link>${esc(primary)}</g:image_link>
${extra.slice(0, 10).map((u) => `    <g:additional_image_link>${esc(u)}</g:additional_image_link>`).join("\n")}
    <g:price>${p.price.toFixed(2)} KES</g:price>
    <g:availability>${availability}</g:availability>
    <g:condition>new</g:condition>
    <g:brand>${esc(SITE.name)}</g:brand>
    <g:identifier_exists>no</g:identifier_exists>
    <g:google_product_category>5455</g:google_product_category>
  </item>`;
    })
    .filter(Boolean);

  const xml = `<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
<channel>
  <title>${esc(SITE.name)}</title>
  <link>${esc(SITE.url)}</link>
  <description>${esc(SITE.tagline)}</description>
${items.join("\n")}
</channel>
</rss>
`;

  return new Response(xml, {
    headers: { "Content-Type": "application/xml; charset=utf-8" },
  });
}
