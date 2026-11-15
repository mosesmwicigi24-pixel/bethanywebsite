import type { Product } from "./products";
import { rootCategory } from "./categories";

/* "Frequently bought together" — built only from live catalogue products.

   There is no co-purchase feed yet, so the pairing is a merchandising rule,
   not a statistic: a communion piece is shown with what completes the table
   (a wine, a bread, cups). Every partner is a real product with its real
   name, photo and price, and must be something "Add all" can honestly put in
   the cart: priced, in stock, and not made-to-measure or variant-only. */

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
const addable = (p: Product) =>
  !p.variantId && !p.variants?.length && !p.producible && p.price > 0 && p.inStock !== false;

/** Up to two real partners for `p`, or [] when no honest bundle exists. */
export function boughtWith(p: Product, catalog: Product[]): Product[] {
  if (rootCategory(p.category) !== "Communion Elements") return [];
  if (p.variantId || p.variants?.length || p.producible || p.price <= 0) return [];
  const own = kind(p);
  if (!own) return [];
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
  return out.length === 2 ? out : [];
}
