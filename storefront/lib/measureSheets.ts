import type { Measurement, Product } from "./products";

/* ============================================================
   Made-to-order fit — fully hub-driven

   Men and ladies are measured differently. Rather than invent sheets in
   the storefront, we read the requirements straight from the hub: each
   measurement may carry an optional `gender` ("men" | "ladies"); absent
   means the field applies to both.

     • A product whose measurements are tagged for BOTH men and ladies
       shows a "Who is it for?" selector, and the fields filter to the
       chosen fit (+ any untagged "both" fields).
     • A product with no gendered tags (today's data) shows its plain
       measurement list, exactly as before — the selector simply doesn't
       appear until the hub tags that product.

   Because every field we submit comes from the product's own hub
   template, the hub's per-product measurement validation keeps passing.
   ============================================================ */

export type Fit = "men" | "ladies";
export const FITS: Fit[] = ["men", "ladies"];
export const FIT_LABEL: Record<Fit, string> = { men: "Men", ladies: "Ladies" };

/** Key under which the chosen fit is recorded on the order (structured
    measurements + the staff notes line). */
export const FIT_KEY = "Who it's for";

/** The fits the hub has actually defined fields for on this product. */
export function fitsFor(product: Product): Fit[] {
  const set = new Set<Fit>();
  for (const m of product.measurements ?? []) {
    if (m.gender === "men" || m.gender === "ladies") set.add(m.gender);
  }
  return FITS.filter((f) => set.has(f));
}

/** True when the customer must choose a fit — the hub tagged this
    product's measurements for BOTH men and ladies. */
export function isGendered(product: Product): boolean {
  return fitsFor(product).length >= 2;
}

/** Fields to show, straight from the hub, for the chosen fit:
      • not gendered            → the product's full list, as-is
      • gendered, no fit yet     → empty (prompt for a fit first)
      • gendered + fit           → fields for this fit + untagged ("both") */
export function sheetFor(product: Product, fit: Fit | null): Measurement[] {
  const own = product.measurements ?? [];
  if (!isGendered(product)) return own;
  if (!fit) return [];
  return own.filter((m) => !m.gender || m.gender === fit);
}

/** Measurements to submit: the shown fields' values, plus the chosen fit
    recorded under FIT_KEY. */
export function withFit(
  fit: Fit,
  template: Measurement[],
  values: Record<string, string>,
): Record<string, string> {
  const out: Record<string, string> = { [FIT_KEY]: FIT_LABEL[fit] };
  for (const f of template) {
    const v = values[f.name]?.trim();
    if (v) out[f.name] = v;
  }
  return out;
}
