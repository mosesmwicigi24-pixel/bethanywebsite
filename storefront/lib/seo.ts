import type { Product } from "./products";
import { SITE } from "./site";

/* ============================================================
   Structured-data (JSON-LD) builders — plain objects the pages
   render inside <JsonLd>. Everything here is derived from the
   live Product model (lib/products.ts) and the business facts in
   lib/site.ts, so schema stays in step with the hub catalog.

   Deliberately NO aggregateRating / review markup: the on-page
   reviews are sample content, and emitting rating schema for
   unverified reviews risks a Google manual action. Wire real,
   hub-verified reviews before adding it (see docs/AI_INTEGRATION_ADVISORY.md §7).
   ============================================================ */

/** Absolute URL from an app-relative path or a possibly-absolute image URL. */
export function abs(pathOrUrl: string): string {
  if (!pathOrUrl) return SITE.url;
  if (pathOrUrl.startsWith("http://") || pathOrUrl.startsWith("https://")) return pathOrUrl;
  return `${SITE.url}${pathOrUrl.startsWith("/") ? "" : "/"}${pathOrUrl}`;
}

const imagesOf = (p: Pick<Product, "gallery" | "img">): string[] =>
  (p.gallery?.length ? p.gallery : [p.img]).filter(Boolean).map(abs);

const availabilityOf = (p: Pick<Product, "inStock">): string =>
  p.inStock === false ? "https://schema.org/OutOfStock" : "https://schema.org/InStock";

/** Known variant attributes → schema.org properties; the rest become
    additionalProperty PropertyValue entries so nothing is lost. */
const ATTR_TO_SCHEMA: Record<string, string> = {
  colour: "color",
  color: "color",
  size: "size",
  material: "material",
  pattern: "pattern",
};

function attributesToSchema(attrs: Record<string, string> | undefined): Record<string, unknown> {
  const out: Record<string, unknown> = {};
  const extra: { "@type": "PropertyValue"; name: string; value: string }[] = [];
  for (const [k, v] of Object.entries(attrs ?? {})) {
    if (!v) continue;
    const mapped = ATTR_TO_SCHEMA[k.toLowerCase()];
    if (mapped) out[mapped] = v;
    else extra.push({ "@type": "PropertyValue", name: k, value: v });
  }
  if (extra.length) out.additionalProperty = extra;
  return out;
}

const seller = { "@type": "Organization", name: SITE.name } as const;

function offer(price: number, currency: "KES" | "USD", url: string, availability: string) {
  return {
    "@type": "Offer",
    priceCurrency: currency,
    price: Math.round(price),
    availability,
    url,
    seller,
  };
}

/** Product / ProductGroup structured data for a PDP.
    Variable products (with saved variants) emit a ProductGroup whose
    hasVariant lists each variant Product + Offer — mirroring how the
    catalog already models parents and variants. */
export function productJsonLd(p: Product, opts: { sku: string; path: string }): Record<string, unknown> {
  const url = abs(opts.path);
  const availability = availabilityOf(p);

  const offersFor = (kes: number, usd: number | undefined, itemUrl: string) => {
    const list = [offer(kes, "KES", itemUrl, availability)];
    if (usd && usd > 0) list.push(offer(usd, "USD", itemUrl, availability));
    return list.length === 1 ? list[0] : list;
  };

  if (p.variants?.length) {
    const variesBy = Array.from(
      new Set(
        p.variants.flatMap((v) =>
          Object.keys(v.attributes ?? {})
            .map((k) => ATTR_TO_SCHEMA[k.toLowerCase()])
            .filter(Boolean),
        ),
      ),
    ).map((prop) => `https://schema.org/${prop}`);

    return {
      "@context": "https://schema.org",
      "@type": "ProductGroup",
      name: p.name,
      description: p.short,
      image: imagesOf(p),
      brand: { "@type": "Brand", name: SITE.name },
      category: p.category,
      productGroupID: opts.sku,
      ...(variesBy.length ? { variesBy } : {}),
      hasVariant: p.variants.map((v) => ({
        "@type": "Product",
        name: v.name,
        sku: v.sku ?? `${opts.sku}-${v.id}`,
        image: (v.gallery?.length ? v.gallery : [v.img]).filter(Boolean).map(abs),
        ...attributesToSchema(v.attributes),
        offers: offersFor(v.price, v.priceUsd, abs(`/product/${v.slug}`)),
      })),
    };
  }

  return {
    "@context": "https://schema.org",
    "@type": "Product",
    name: p.name,
    description: p.short,
    image: imagesOf(p),
    sku: opts.sku,
    brand: { "@type": "Brand", name: SITE.name },
    category: p.category,
    offers: offersFor(p.price, p.priceUsd, url),
  };
}

/** BreadcrumbList from the visible crumb trail. Items without a path
    (the current page) omit `item`, per Google guidance. */
export function breadcrumbJsonLd(items: { name: string; path?: string }[]): Record<string, unknown> {
  return {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    itemListElement: items.map((it, i) => ({
      "@type": "ListItem",
      position: i + 1,
      name: it.name,
      ...(it.path ? { item: abs(it.path) } : {}),
    })),
  };
}

/** Site-wide Organization + physical Store (a LocalBusiness) identity.
    Rendered once in the root layout. Lets Google build a knowledge panel
    and lets AI assistants answer "where do I buy communion supplies in
    Nairobi" with Bethany House. */
export function organizationJsonLd(): Record<string, unknown> {
  const org = {
    "@type": "Organization",
    "@id": `${SITE.url}/#organization`,
    name: SITE.name,
    url: SITE.url,
    logo: abs("/brand/logo-light.png"),
    email: SITE.email,
    telephone: SITE.phone,
    description: SITE.tagline,
    contactPoint: [
      {
        "@type": "ContactPoint",
        telephone: SITE.phone,
        contactType: "sales",
        areaServed: "Worldwide",
        availableLanguage: ["English", "Swahili"],
      },
    ],
    address: {
      "@type": "PostalAddress",
      streetAddress: SITE.address,
      addressLocality: "Nairobi",
      addressCountry: "KE",
    },
  };

  const store = {
    "@type": "Store",
    "@id": `${SITE.url}/#store`,
    name: SITE.name,
    image: abs("/brand/logo-light.png"),
    url: SITE.url,
    telephone: SITE.phone,
    email: SITE.email,
    priceRange: "$$",
    currenciesAccepted: "KES, USD",
    paymentAccepted: SITE.payments,
    address: {
      "@type": "PostalAddress",
      streetAddress: SITE.address,
      addressLocality: "Nairobi",
      addressRegion: "Nairobi",
      addressCountry: "KE",
    },
    openingHoursSpecification: [
      {
        "@type": "OpeningHoursSpecification",
        dayOfWeek: ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
        opens: "08:00",
        closes: "17:00",
      },
    ],
    parentOrganization: { "@id": `${SITE.url}/#organization` },
  };

  return { "@context": "https://schema.org", "@graph": [org, store] };
}
