import type { Product, Measurement, VariantOption } from "./products";
import { curated } from "./products";

/* ============================================================
   Hub catalog — the storefront's live product source.

   Fetches every published product from the hub's public API
   (GET /api/v1/products), expands each variant of a variable product
   into its own independent Product (slug "parent--v{id}"), and merges
   the curated design overlay (local photography, chips, taglines,
   "closer look" stories, measurement templates) by slug so the
   flagship products keep their rich pages while every other hub
   product gets a clean, correct default.

   Server-only (uses fetch with ISR). Pages call getCatalog()/
   getProductBySlug(); the client gets the result via <CatalogProvider>.
   ============================================================ */

const HUB = process.env.NEXT_PUBLIC_HUB_API; // https://hub.bethanyhouse.co.ke/api/v1
const REVALIDATE = 300; // seconds — catalog refreshes without a rebuild
const USD_PER_KES = 1 / 100; // shop rate: USD = KES ÷ 100
const PLACEHOLDER = "/brand/placeholder.svg";

interface HubPrice { currency_code: string; regular_price: string; sale_price: string | null; product_variant_id: number | null }
interface HubImage { image_url: string; is_primary?: boolean; sort_order?: number }
interface HubTranslation { language_code: string; name: string; short_description?: string | null }
interface HubCategory { name_en?: string | null; slug?: string | null; show_in_storefront?: boolean }
interface HubProduct {
  id: number; slug: string; sku: string; product_type: string; status: string;
  is_producible: boolean; is_featured?: boolean; measurements?: Measurement[] | null;
  category?: HubCategory | null; images?: HubImage[]; translations?: HubTranslation[]; prices?: HubPrice[];
  in_stock?: boolean; available_qty?: number;
}
interface HubVariant {
  id: number; product_id: number; sku: string; variant_name: string;
  attributes?: Record<string, string>; is_active: boolean; is_default?: boolean;
  prices?: HubPrice[]; images?: HubImage[];
}

async function hubGet<T>(path: string): Promise<T | null> {
  if (!HUB) return null;
  try {
    const r = await fetch(`${HUB}${path}`, { next: { revalidate: REVALIDATE }, headers: { Accept: "application/json" } });
    if (!r.ok) return null;
    return (await r.json()) as T;
  } catch {
    return null;
  }
}

const priceOf = (rows: HubPrice[] | undefined, code: string): number | undefined => {
  const row = rows?.find((p) => p.currency_code === code);
  if (!row) return undefined;
  const v = Number(row.sale_price ?? row.regular_price);
  return Number.isFinite(v) && v > 0 ? v : undefined;
};
const oldPriceOf = (rows: HubPrice[] | undefined, code: string): number | undefined => {
  const row = rows?.find((p) => p.currency_code === code);
  if (!row?.sale_price) return undefined;
  const reg = Number(row.regular_price), sale = Number(row.sale_price);
  return reg > sale ? reg : undefined;
};

const imagesOf = (imgs: HubImage[] | undefined): string[] =>
  (imgs ?? [])
    .slice()
    .sort((a, b) => Number(b.is_primary) - Number(a.is_primary) || (a.sort_order ?? 0) - (b.sort_order ?? 0))
    .map((i) => i.image_url)
    .filter(Boolean);

/** Map a hub product (optionally a specific variant) to a storefront Product,
    then layer the curated overlay on top when one exists for the slug. */
function toProduct(hp: HubProduct, variant?: HubVariant): Product {
  const baseSlug = hp.slug;
  const slug = variant ? `${baseSlug}--v${variant.id}` : baseSlug;
  const c = curated[baseSlug]; // curated overlay (may be undefined)

  const hubName = hp.translations?.find((t) => t.language_code === "en")?.name
    ?? hp.translations?.[0]?.name ?? hp.slug;
  const name = variant
    ? `${c?.name ?? hubName} — ${variant.variant_name}`
    : c?.name ?? hubName;

  const priceRows = variant?.prices ?? hp.prices;
  const kes = priceOf(priceRows, "KES") ?? c?.price ?? 0;
  const usd = priceOf(priceRows, "USD") ?? (kes ? Math.round(kes * USD_PER_KES) : (c?.priceUsd ?? 0));
  const oldKes = oldPriceOf(priceRows, "KES") ?? c?.oldPrice;
  const oldUsd = oldPriceOf(priceRows, "USD") ?? c?.oldPriceUsd;

  const variantImgs = variant ? imagesOf(variant.images) : [];
  const hubImgs = imagesOf(hp.images);
  const gallery = variantImgs.length ? variantImgs
    : c?.gallery ?? (hubImgs.length ? hubImgs : c?.img ? [c.img] : [PLACEHOLDER]);

  return {
    slug,
    baseSlug,
    variantId: variant?.id,
    variantAttributes: variant?.attributes,
    name,
    short: c?.short ?? (variant ? variant.variant_name : hubName),
    img: gallery[0],
    gallery,
    price: kes,
    oldPrice: oldKes,
    priceUsd: usd,
    oldPriceUsd: oldUsd,
    producible: hp.is_producible,
    measurements: (hp.measurements && hp.measurements.length ? hp.measurements : c?.measurements) ?? undefined,
    sizes: c?.sizes,
    tagline: c?.tagline,
    closerLook: c?.closerLook,
    chips: c?.chips ?? [],
    rating: c?.rating ?? 5,
    reviews: c?.reviews ?? 0,
    badge: c?.badge ?? (hp.is_featured ? "best" : undefined),
    seller: c?.seller,
    category: c?.category ?? hp.category?.name_en ?? "Church Supplies",
    inStock: hp.in_stock,
  };
}

/** A saved hub variant as a selectable option on its parent product. */
function toVariantOption(hp: HubProduct, v: HubVariant): VariantOption {
  const kes = priceOf(v.prices, "KES") ?? 0;
  const usd = priceOf(v.prices, "USD") ?? (kes ? Math.round(kes * USD_PER_KES) : 0);
  const vg = imagesOf(v.images);
  const pg = imagesOf(hp.images);
  const gallery = vg.length ? vg : (pg.length ? pg : [PLACEHOLDER]);
  return {
    id: v.id,
    slug: `${hp.slug}--v${v.id}`,
    attributes: v.attributes ?? {},
    name: v.variant_name,
    price: kes,
    priceUsd: usd,
    oldPrice: oldPriceOf(v.prices, "KES"),
    oldPriceUsd: oldPriceOf(v.prices, "USD"),
    img: gallery[0],
    gallery,
    sku: v.sku,
  };
}

let _cache: { at: number; list: Product[] } | null = null;

/** Every published product from the hub, with variants expanded to their
    own independent products. Curated overlay applied by slug. */
export async function getCatalog(): Promise<Product[]> {
  // in-process memo so a single render doesn't refetch per component
  if (_cache && Date.now() - _cache.at < REVALIDATE * 1000) return _cache.list;

  const res = await hubGet<{ data: HubProduct[] }>("/products?per_page=200");
  // Never surface archived products (the hub API already excludes them; explicit for safety).
  const hubProducts = (res?.data ?? []).filter((p) => p.status !== "archived");
  if (!hubProducts.length) {
    // Hub unreachable — fall back to the curated set so the site still serves.
    return Object.values(curated) as Product[];
  }

  const out: Product[] = [];
  const variableIds = hubProducts.filter((p) => p.product_type === "variable");

  // fetch variants for variable products in parallel
  const variantMap = new Map<number, HubVariant[]>();
  await Promise.all(
    variableIds.map(async (p) => {
      const v = await hubGet<{ data: HubVariant[] }>(`/products/${p.id}/variants`);
      variantMap.set(p.id, (v?.data ?? []).filter((x) => x.is_active));
    }),
  );

  for (const hp of hubProducts) {
    const variants = variantMap.get(hp.id);
    if (hp.product_type === "variable" && variants && variants.length) {
      // Parent product carries its variants, selectable in place on the PDP,
      // and shows the "from" (cheapest) price on the shop card.
      const opts = variants.map((v) => toVariantOption(hp, v));
      const parent = toProduct(hp);
      const priced = opts.filter((o) => o.price > 0);
      const cheapest = priced.length
        ? priced.reduce((a, b) => (b.price < a.price ? b : a))
        : opts[0];
      // Variants of one garment cost the same; some hub rows just lack a price.
      // Backfill from the cheapest so nothing ever renders KES 0 (PDP or cart).
      if (cheapest) {
        for (const o of opts) {
          if (o.price <= 0) { o.price = cheapest.price; o.priceUsd = cheapest.priceUsd; }
        }
      }
      parent.variants = opts;
      if (cheapest) {
        parent.price = cheapest.price;
        parent.priceUsd = cheapest.priceUsd;
        parent.oldPrice = cheapest.oldPrice;
        parent.oldPriceUsd = cheapest.oldPriceUsd;
        if (!parent.gallery?.length || parent.gallery[0] === PLACEHOLDER) {
          parent.gallery = cheapest.gallery;
          parent.img = cheapest.img;
        }
      }
      out.push(parent);
      // Keep each variant resolvable too (cart/bySlug lines + old deep links).
      for (const v of variants) {
        const vp = toProduct(hp, v);
        if (vp.price <= 0 && cheapest) { vp.price = cheapest.price; vp.priceUsd = cheapest.priceUsd; }
        out.push(vp);
      }
    } else {
      out.push(toProduct(hp));
    }
  }

  _cache = { at: Date.now(), list: out };
  return out;
}

/** Resolve a storefront slug (simple product or "parent--v{id}" variant). */
export async function getProductBySlug(slug: string): Promise<Product | null> {
  const all = await getCatalog();
  return all.find((p) => p.slug === slug) ?? null;
}
