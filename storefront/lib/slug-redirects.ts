/* Old product URLs → their canonical slugs after the 2026-07-30 catalog slug
   cleanup (hub migration 2026_30_07_000002). Many live slugs described a
   DIFFERENT product (the Mitre lived at /product/the-carry-along-bible, the
   Anointing horn at /product/cincture-belt); the rest carried junk random-SKU
   suffixes. The hub renamed 36 slugs to match each product's name; 9 of the
   vacated URLs were immediately re-owned by the product a shopper always
   expected there (those need no entry), and these 27 died and 301 here.

   The hub keeps the same mapping in its product_slug_redirects table (for API
   consumers + checkout); this map gives the storefront instant, SEO-friendly
   permanent redirects without a hub round-trip. */

export const SLUG_REDIRECTS: Record<string, string> = {
  // 2026-07-30 duplicate-product merge: extras archived, URLs → survivor.
  "200-pcs-of-bread": "communion-wafer-bread-200pcs",
  "canon-gown-catholic": "canon-gown",
  "princes-cassock": "white-princes-cassock",
  "1000-pieces-of-bread": "communion-wafer-bread-500pcs",
  "anointing-oil-eliad-olive-oil-750ml": "round-collar-shirt",
  "biblia-yangu-kubwa-niipendayo-ocjn841jk": "biblia-yangu-kubwa-niipendayo",
  "canon-gown-m3bxruqjc": "canon-gown-catholic",
  "chalice-cup-medium-7im6k8ka4": "golden-chalice-cup-medium",
  "chasuble-1": "purple-red-cope",
  "cincture-belt-rong11jy7": "cincture-belt",
  "cincture-rope-537ou8q5z": "cincture-rope",
  "communion-bread-250pcs-cwizib1zl": "communion-wafer-bread-200pcs",
  "custom-designed-dress-ml8fmkzql": "custom-designed-dress",
  "executive-water-bottle-xziqu3rfm": "executive-water-bottle",
  "glass-cups-yx9pkfqq5": "glass-cups",
  "holy-communion-bread-500pcs-ku5ze5zfy": "holy-communion-bread-1000pcs",
  "pectoral-cross-csmhij6r8": "pectoral-cross-gold",
  "preaching-gown-1": "silver-communion-tray",
  "preaching-gown-843glv9rc": "preaching-gown",
  "princes-cassock-blue": "white-princes-cassock",
  "ring-1": "bishops-ring",
  "silver-communion-cups-1": "silver-communion-cups",
  "staffcroziersheherd-rod": "staff-crozier-shepherd-rod",
  "stole": "single-sided-stole",
  "tallit-prayer-shawl-medium-outoidpwy": "tallit-prayer-shawl-medium",
  "the-365-day-childrens-bible-1hncf393w": "the-365-day-childrens-bible",
  "the-carry-along-bible": "mitre",
  "the-carry-along-bible-9co393v3r": "straight-collar",
  "trail-product-ue81uojy5": "ordination-gown",
  "white-cassock-fbu30que0": "white-cassock",
};
