/* Root categories for the storefront.

   The hub's category data has sprawled into ~20 overlapping leaf names
   (Clergy Vestments / Vestments / Gowns / Clergy Apparel; Communion Items /
   Communion Elements / Communion Wines / Golden Communion Trays; Bibles /
   Children Bibles / Bibles & Devotionals; …). The shop facet should show a
   small, clean set of departments, so we fold every leaf onto one root.

   Keep this in sync with the nav in components/chrome.tsx. */

export const ROOT_CATEGORIES = [
  "Communion Elements",
  "Clergy Apparel",
  "Bibles & Devotionals",
  "Gifts & Accessories",
  "Church Essentials",
] as const;

export type RootCategory = (typeof ROOT_CATEGORIES)[number];

/** Leaf category (lowercased) → root department. */
const LEAF_TO_ROOT: Record<string, RootCategory> = {
  // Communion Elements — everything for the Lord's Table + sacramentals
  "communion items": "Communion Elements",
  "communion elements": "Communion Elements",
  "communion accessories": "Communion Elements",
  "communion wines": "Communion Elements",
  "communion wine": "Communion Elements",
  "golden communion trays": "Communion Elements",
  "colden communion trays": "Communion Elements", // hub data typo (Golden)
  "anointing": "Communion Elements",
  "anointing oil": "Communion Elements",

  // Clergy Apparel — vestments, gowns, cassocks, prayer wear + their extras
  "clergy vestments": "Clergy Apparel",
  "vestments": "Clergy Apparel",
  "clergy apparel": "Clergy Apparel",
  "clergy accessories": "Clergy Apparel",
  "gowns": "Clergy Apparel",
  "gown": "Clergy Apparel",
  "prayer wear": "Clergy Apparel",

  // Bibles & Devotionals — scripture + devotional books
  "bibles": "Bibles & Devotionals",
  "children bibles": "Bibles & Devotionals",
  "bibles & devotionals": "Bibles & Devotionals",
  "books & gifts": "Bibles & Devotionals",

  // Gifts & Accessories
  "gifts": "Gifts & Accessories",
  "gifts & accessories": "Gifts & Accessories",

  // Church Essentials — general supplies for the sanctuary
  "church supplies": "Church Essentials",
  "church essentials": "Church Essentials",
};

/** Fold any raw category string onto its root department. */
export function rootCategory(leaf: string | undefined | null): RootCategory {
  if (!leaf) return "Church Essentials";
  return LEAF_TO_ROOT[leaf.trim().toLowerCase()] ?? "Church Essentials";
}
