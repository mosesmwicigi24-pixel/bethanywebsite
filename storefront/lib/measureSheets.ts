import type { Measurement, Product } from "./products";

/* ============================================================
   Made-to-order fit + measurement sheets

   The hub serves ONE flat measurement list per product with no gender.
   Men and ladies are measured differently, so the made-to-order form
   asks "Who is it for?" first, then shows the right requirements.

   Source of the two field sets, in priority order:
     1. If the hub tags any of the product's measurements with a `gender`,
        we honour that (fields for the chosen fit + untagged/"both" fields).
        → this path lights up automatically once the hub adds gendered data.
     2. Otherwise we use the house STANDARD_SHEETS below — mirrored from the
        hub's "Men set" / "Ladies set" quick-add templates — while still
        including the product's own REQUIRED fields, so the hub's per-product
        measurement validation on checkout keeps passing.
   ============================================================ */

export type Fit = "men" | "ladies";
export const FITS: Fit[] = ["men", "ladies"];
export const FIT_LABEL: Record<Fit, string> = { men: "Men", ladies: "Ladies" };

/** Key under which the chosen fit is attached to the submitted measurements,
    so it rides along to the hub production order + the staff notes. */
export const FIT_KEY = "Who it's for";

const req = (name: string): Measurement => ({ name, unit: "in", required: true });

/** House standard sheets — mirror the hub's "Men set" (10-field) and
    "Ladies set" (11-field) quick-add templates. Edit here to retune. */
export const STANDARD_SHEETS: Record<Fit, Measurement[]> = {
  men: ["Neck", "Shoulders", "Sleeves", "Wrist", "Arm Hole", "Upper Arm", "Chest", "Stomach", "Shirt Length", "Height"].map(req),
  ladies: ["Neck", "Shoulders", "Sleeves", "Wrist", "Arm Hole", "Upper Arm", "Bodice", "Waist", "Hips", "Blouse Length", "Full Length"].map(req),
};

/** The measurement fields to show for a product given the chosen fit. */
export function sheetFor(product: Product, fit: Fit): Measurement[] {
  const own = product.measurements ?? [];

  // 1) Hub already tags fields by gender — honour it (auto-upgrade path):
  //    fields for this fit, plus any untagged/"both" fields.
  if (own.some((m) => m.gender === "men" || m.gender === "ladies")) {
    return own.filter((m) => !m.gender || m.gender === fit);
  }

  // 2) No gendered data yet — use the house standard sheet for the fit.
  return STANDARD_SHEETS[fit];
}

/** Measurements to submit: the chosen fit + only the values for the
    fields currently shown (drops anything left over from a prior fit). */
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
