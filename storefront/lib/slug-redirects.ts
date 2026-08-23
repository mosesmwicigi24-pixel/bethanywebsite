/* Old product URLs → their canonical slugs.

   Two eras of legacy URL feed this map:
   • The 2026-07-30 hub slug cleanup (migration 2026_30_07_000002): 36 slugs
     renamed, duplicates merged, and some URLs whose old-site content had
     drifted onto unrelated products.
   • The pre-relaunch site's junk-suffixed slugs ("{name}-{9 random chars}"),
     which `resolveLegacySlug` below handles by pattern, so only slugs whose
     BASE also changed need an entry here.

   POLICY (since 2026-08-23): redirects follow what the URL *promises* — its
   slug text — because nearly all traffic on these URLs now arrives from
   search results and old links where the slug is the visitor's expectation.
   (The original cleanup map preserved last-content continuity instead, e.g.
   the Mitre lived at /product/the-carry-along-bible; those cross-product
   mappings are overridden in next.config.ts, which runs before this map.)

   The hub keeps its own mapping in product_slug_redirects (for API consumers
   + checkout); this map gives the storefront instant permanent redirects
   without a hub round-trip. Never map a slug that exists in the live catalog
   — the page checks this map BEFORE the catalog, so such an entry kills the
   product's own page (that bug hid the Preaching Gown until 2026-08-23). */

export const SLUG_REDIRECTS: Record<string, string> = {
  // 2026-07-30 duplicate-product merge: extras archived, URLs → survivor.
  "chalice-cup-medium": "golden-chalice-cup-medium",
  "200-pcs-of-bread": "communion-wafer-bread-200pcs",
  "canon-gown-catholic": "canon-gown",
  "princes-cassock": "white-princes-cassock",
  "1000-pieces-of-bread": "communion-wafer-bread-500pcs",
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
  "princes-cassock-blue": "white-princes-cassock",
  "ring-1": "bishops-ring",
  "silver-communion-cups-1": "silver-communion-cups",
  "staffcroziersheherd-rod": "staff-crozier-shepherd-rod",
  "stole": "single-sided-stole",
  "tallit-prayer-shawl-medium-outoidpwy": "tallit-prayer-shawl-medium",
  "the-365-day-childrens-bible-1hncf393w": "the-365-day-childrens-bible",
  "trail-product-ue81uojy5": "ordination-gown",
  "white-cassock-fbu30que0": "white-cassock",

  // Slug-semantics corrections (2026-08-23) — the URL's words win over the
  // old site's drifted content. Mirrors the overrides in next.config.ts.
  "preaching-gown": "preaching-gown-843glv9rc",
  "preaching-gown-1": "preaching-gown-843glv9rc",
  "anointing-oil-eliad-olive-oil-750ml": "eliad-anointing-oil",
  // (the-carry-along-bible URLs → /category/bibles-devotionals via next.config —
  //  category targets can't live in this product-only map.)

  // Renamed / consolidated products, mapped from what shoppers still search
  // (bases surfaced by Search Console's 404 report, 2026-08-23).
  "thurible": "incense-burner",
  "thuribleincence-burner": "incense-burner",
  "bell": "altar-bell",
  "bishop-mitre": "mitre",
  "bishop-crozier": "staff-crozier-shepherd-rod",
  "double-sided-stoles": "double-sided-stole",
  "stoles-double-sided": "double-sided-stole",
  "stole-double-sided": "double-sided-stole",
  "stoles-single-sided": "single-sided-stole",
  "aipca-stoles-single-sided": "single-sided-stole",
  "aipca-stoles-double-sided": "double-sided-stole",
  "tallit-prayer-shawl": "tallit-prayer-shawl-medium",
  "usher-belts": "usher-belt",
  "pope-chasuble": "chasuble",
  "open-chasuble": "chasuble",
  "self-print-chasuble": "chasuble",
  "bethany-house-cassocks": "cassock",
  "full-cassocks": "cassock",
  "self-print-cassock": "cassock",
  "black-gown-executive": "preaching-gown-843glv9rc",
  "clergy-shirt-u-collar": "round-collar-shirt",
  "u-collar": "round-collar-shirt",
  "satin-clergy-shirt": "clergy-shirt",
  "clergy-shirt-straight-collar": "straight-collar-shirt",
  "collar-size-8": "straight-collar",
  "pectoral-cross-large": "pectoral-cross-gold",
  "eliad-olive-oil-750ml": "eliad-anointing-oil",
  "anointing-oil-medium-bottle": "anointing-oil",
  "holy-communion-bread": "holy-communion-bread-1000pcs",
  "communion-bread-1000-pcs": "holy-communion-bread-1000pcs",
  "hosts-large": "communion-hosts",
  "portable-bread-holders": "bread-container",
  "basic-communion-pack": "pre-packed-communion-cups",
  "devai-prefilled-communion-cups": "pre-packed-communion-cups",
  "prefilled-cups-devai": "pre-packed-communion-cups",
  "efrat-massoret-wine": "efrat-communion-wine",
  "altar-wine-refiller": "refiller",
  "silver-communion-refiler-jug": "refiller",
  "metallic-silver-cups": "silver-communion-cups",
  "silver-tray": "silver-communion-tray",
  "silver-tray-lid": "silver-communion-tray",
  "silver-tray-large-72-cups": "silver-communion-tray",
  "silver-communion-tray-with-40-silver-cups": "silver-communion-tray",
  "golden-tray-without-lid": "golden-communion-tray",
  "gold-communion-tray-cover": "golden-communion-tray",
  "stackable-goden-bread-tray": "gold-bread-tray",
  "aluminium-cover": "aluminium-tray",
  "wooden-tray-bigger": "wooden-tray",
  "suit-butler-garment-bags-for-travel": "bag",
  "love-packaging-bags": "bag",
  "sprinkler-brass-no-2": "sprinkler",
  "sprinckler-brass-no-3": "sprinkler",
  "sprinckler-brass-no-4": "sprinkler",
  "sprinckler-brass-1": "sprinkler",
  "silver-bread-tray-lid": "silver-bread-tray",
  "plastic-communion-cups": "plastic-cups",
  "cope": "cope-complete-set",
  "chalice-cup-small-stainless-steel-cup": "chalice-cup-gold-coated-stainless-steel",
  "table-cross": "small-cross-1",
  "offering-bag": "offering-basket",
  "bible-study-leather": "niv-application-bible",
  "rectangle-stainless-steel-communion-tray": "silver-communion-tray",
};

/** Resolve a dead product slug to a live one, or null when it should 404.
    Walks the map plus the old site's slug patterns, one step per round:
    map hop → strip "--v{id}" (variant) → strip "-{9 junk chars}" →
    strip "-{1–2 digits}" (duplicate counters). A slug the caller reports
    as live is returned immediately, so live slugs are never rewritten. */
export function resolveLegacySlug(
  slug: string,
  isLive: (s: string) => boolean,
): string | null {
  let cur = slug;
  for (let i = 0; i < 8; i++) {
    if (isLive(cur)) return cur === slug ? null : cur;
    const mapped = SLUG_REDIRECTS[cur];
    if (mapped) { cur = mapped; continue; }
    let next = cur.replace(/--v\d+$/, "");
    if (next === cur) next = cur.replace(/-[a-z0-9]{9}$/, "");
    if (next === cur) next = cur.replace(/-\d{1,2}$/, "");
    if (next === cur) return null;
    cur = next;
  }
  return null;
}
