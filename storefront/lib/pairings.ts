import type { Product } from "./products";
import { rootCategory } from "./categories";

/* The product page's bundle box — built only from live catalogue products.

   FROM THE RECEIPTS FIRST. When the hub's sales feed (lib/insights.ts) says
   this product shares receipts with others, those are the partners, and the
   page may say "Frequently bought together" because it is true.

   Where the receipts are silent, a merchandising rule stands in for communion
   pieces — a wine, a bread, cups: what completes the table — and the page
   says so ("Complete the table"), without claiming anyone bought it that way.

   Either way every partner is a real product with its real name, photo and
   price, and must be something "Add all" can honestly put in the cart:
   priced, in stock, and not made-to-measure or variant-only. */

type Kind = "wine" | "bread" | "cups" | "vessel";

function kind(p: Product): Kind | null {
  const t = `${p.name} ${p.category}`.toLowerCase();
  if (/\b(tray|container|refiller|kettle|basket)\b/.test(t)) return null; // accessories pair with nothing by rule
  if (/wine|devai/.test(t)) return "wine";
  if (/host|wafer|bread/.test(t)) return "bread";
  if (/chalice|ciborium/.test(t)) return "vessel";
  if (/\bcups?\b/.test(t)) return "cups";
  return null;
}

const real = (p: Product) => Boolean(p.img) && !p.img.includes("placeholder");
/** One tap can put it in the cart: no size, measurement or variant to choose. */
export const addable = (p: Product) =>
  !p.variantId && !p.variants?.length && !p.producible && p.price > 0 && p.inStock !== false;

export interface Bundle {
  /** one or two partners; empty when no honest bundle exists */
  partners: Product[];
  /** true when the partners come from real receipts, not the rule */
  fromSales: boolean;
}

const NONE: Bundle = { partners: [], fromSales: false };

/**
 * Partners for `p`'s bundle box. `soldWith` is the hub's list of slugs bought
 * on the same receipts as `p`, strongest first (empty when unknown).
 */
export function boughtWith(p: Product, catalog: Product[], soldWith: string[] = []): Bundle {
  if (p.variantId || p.variants?.length || p.producible || p.price <= 0) return NONE;

  // 1. What customers really bought with it — photographed first, then by
  //    how often they share a receipt (the feed's order; sort is stable).
  const bySlug = new Map(catalog.map((q) => [q.slug, q]));
  const sold = soldWith
    .map((s) => bySlug.get(s))
    .filter((q): q is Product => Boolean(q) && q!.slug !== p.slug && addable(q!))
    .sort((a, b) => Number(real(b)) - Number(real(a)))
    .slice(0, 2);
  if (sold.length) return { partners: sold, fromSales: true };

  // 2. The rule: a communion piece with what completes the table.
  if (rootCategory(p.category) !== "Communion Elements") return NONE;
  const own = kind(p);
  if (!own) return NONE;
  const wanted: Kind[] = (["wine", "bread", "cups", "vessel"] as Kind[]).filter((k) => k !== own);
  const out: Product[] = [];
  for (const k of wanted) {
    const pick = catalog
      .filter((q) => q.slug !== p.slug && addable(q) && kind(q) === k
        && rootCategory(q.category) === "Communion Elements")
      // photographed first, then hub-featured, then the dearer (the fuller pack)
      .sort((a, b) => Number(real(b)) - Number(real(a))
        || Number(b.badge === "best") - Number(a.badge === "best")
        || b.price - a.price)[0];
    if (pick) out.push(pick);
    if (out.length === 2) break;
  }
  return out.length === 2 ? { partners: out, fromSales: false } : NONE;
}

/**
 * "Often bought with what's in your cart": real companions of the cart's
 * products, taken a round at a time so every line gets a say, skipping what
 * is already in the cart. `cartSlugs` and the keys of `together` are base
 * product slugs.
 */
export function cartCompanions(
  cartSlugs: string[],
  together: Record<string, string[]>,
  find: (slug: string) => Product | undefined,
  max = 3,
): Product[] {
  const inCart = new Set(cartSlugs);
  const lists = [...inCart].map((s) => together[s] ?? []);
  const out: Product[] = [];
  const seen = new Set<string>();
  for (let round = 0; out.length < max && lists.some((l) => round < l.length); round++) {
    for (const l of lists) {
      const slug = l[round];
      if (!slug || inCart.has(slug) || seen.has(slug)) continue;
      seen.add(slug);
      const q = find(slug);
      if (q && addable(q) && real(q)) out.push(q);
      if (out.length === max) break;
    }
  }
  return out;
}
