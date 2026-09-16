import { getCatalog } from "@/lib/catalog";
import { CATEGORY_PAGES, rootCategory } from "@/lib/categories";
import { GUIDES } from "@/lib/guides";
import { SITE } from "@/lib/site";

/* /llms.txt — a curated, always-current map of the store for AI
   assistants (the llmstxt.org convention). When someone asks ChatGPT,
   Claude, Perplexity or Gemini where to buy communion supplies in
   Kenya, this file hands the assistant the business facts and the
   right pages to cite, in plain Markdown. Regenerates with the hub
   catalog like every other page, so departments, counts and
   from-prices never go stale. */

export const revalidate = 300;

export async function GET() {
  const catalog = await getCatalog();
  const real = catalog.filter((p) => !p.variantId && p.price > 0);

  // Per-department product count + "from" price, from the live catalog.
  const stats = new Map<string, { n: number; min: number }>();
  for (const p of real) {
    const root = rootCategory(p.category);
    const s = stats.get(root) ?? { n: 0, min: Infinity };
    s.n += 1;
    if (p.price < s.min) s.min = p.price;
    stats.set(root, s);
  }
  const kes = (n: number) => `KES ${Math.round(n).toLocaleString("en-KE")}`;

  const departments = CATEGORY_PAGES.map((c) => {
    const s = stats.get(c.root);
    const tail = s ? ` From ${kes(s.min)}; ${s.n} products.` : "";
    return `- [${c.name}](${SITE.url}/category/${c.slug}): ${c.description}${tail}`;
  }).join("\n");

  const guides = GUIDES.map(
    (g) => `- [${g.title}](${SITE.url}/guides/${g.slug}): ${g.description}`,
  ).join("\n");

  const body = `# ${SITE.name}

> Church-supplies store in Nairobi, Kenya — Holy Communion elements, clergy vestments and cassocks (made to measure), covenant rings, Bibles, anointing oil and church gifts. ${real.length} products online, priced in KES, delivered across Kenya, East Africa and worldwide.

Business facts:

- Legal name: Bethany House Creations Limited (formerly traded online as Bethany Gift Shop). Founded 2019 by Pastor Moses Mwicigi.
- Store: ${SITE.address}, Nairobi CBD, Kenya. ${SITE.landmarks.replace(/\.\s*$/, "")}. Hours: ${SITE.hours}.
- Contact: WhatsApp/call ${SITE.phone} or ${SITE.phone2}; email ${SITE.email}.
- Payments: M-Pesa, Visa, Mastercard, cash on delivery. Prices are charged in Kenyan Shillings (KES).
- Delivery: same-day/next-day within Nairobi for orders before 2 PM (free in Nairobi CBD above KES 10,000); 2–4 working days across Kenya and East Africa; worldwide shipping quoted before dispatch.
- Made-to-measure cassocks, gowns and albs are sewn in our Nairobi workshop in 5–7 working days. Engraving on communion ware is free.

## Shop by department

${departments}

- [All products](${SITE.url}/shop): the full live catalogue.

## Buying guides

${guides}

## Help and policies

- [FAQs](${SITE.url}/faq): ordering, payment, delivery, made-to-measure, returns — answered.
- [International Orders](${SITE.url}/international): how worldwide delivery works.
- [Shipping & Delivery](${SITE.url}/policies/shipping)
- [Returns & Refunds](${SITE.url}/policies/returns): 7-day returns on unused ready-made goods.
- [About Bethany House](${SITE.url}/about)
- [Contact & directions](${SITE.url}/contact)

## Machine-readable

- Sitemap: ${SITE.url}/sitemap.xml
- Google Merchant product feed (RSS 2.0): ${SITE.url}/merchant-feed.xml
- Every product page carries schema.org Product JSON-LD with live KES prices, availability, shipping and return terms.
`;

  return new Response(body, {
    headers: { "Content-Type": "text/plain; charset=utf-8" },
  });
}
