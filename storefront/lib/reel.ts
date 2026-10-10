import type { ReelFrame, ReelShelf } from "@/components/HomeReel";
import type { Product } from "./products";
import type { ContentBlock } from "./theme";
import { rootCategory, type RootCategory } from "./categories";

/* The home reel's shelves: "All" (a curated mix) plus one story ring per
   department, each a short run of that department's best-photographed
   products. Everything shown is real catalogue data — the only words added
   are a line of scripture per department, the reel's quiet signature. */

const DEPARTMENTS: { root: RootCategory; label: string; ring: string; verse: ReelFrame["verse"] }[] = [
  { root: "Communion Elements", label: "Communion", ring: "/products/Chalice_Cup.jpg",
    verse: { text: "Do this in remembrance of me.", ref: "Luke 22:19" } },
  { root: "Clergy Apparel", label: "Clergy", ring: "/products/preaching_gown1.jpg",
    verse: { text: "Let your priests be clothed with righteousness.", ref: "Psalm 132:9" } },
  { root: "Bibles & Devotionals", label: "Bibles", ring: "/products/niv_bible.jpg",
    verse: { text: "Your word is a lamp to my feet.", ref: "Psalm 119:105" } },
  { root: "Gifts & Accessories", label: "Gifts", ring: "/products/cross1.jpg",
    verse: { text: "Every good and perfect gift is from above.", ref: "James 1:17" } },
  { root: "Church Essentials", label: "Essentials", ring: "/products/bell.jpg",
    verse: { text: "Let all things be done decently and in order.", ref: "1 Corinthians 14:40" } },
];
const VERSE = new Map(DEPARTMENTS.map((d) => [d.root, d.verse]));

const PER_SHELF = 8;

/** A product the reel can show full-screen: priced, with a real photo. */
const shootable = (p: Product) => p.price > 0 && Boolean(p.img) && !p.img.includes("placeholder");

/** Honest, data-backed reassurance chips for a frame. */
function trust(p: Product): string[] {
  const out: string[] = [];
  if (p.producible) out.push(p.sizes ? "Ready-made or tailored" : "Tailored in Nairobi · 5–7 days");
  else if (p.inStock !== false) out.push("In stock · ships today");
  if (p.reviews > 0) out.push(`★ ${p.rating.toFixed(1)} · ${p.reviews.toLocaleString("en-KE")} reviews`);
  return out;
}

function productFrame(p: Product): ReelFrame {
  return {
    key: p.slug,
    kind: "product",
    slug: p.slug,
    href: `/product/${p.slug}`,
    eyebrow: p.category,
    title: p.name,
    sub: p.tagline || p.short,
    img: p.img,
    alt: p.name,
    video: p.video,
    price: p.price,
    oldPrice: p.oldPrice,
    producible: Boolean(p.producible),
    verse: VERSE.get(rootCategory(p.category)),
    trust: trust(p),
  };
}

/** Clips first, then hub-featured, then the rest in catalogue order. */
const rank = (p: Product) => (p.video ? 2 : 0) + (p.badge === "best" ? 1 : 0);

export function buildReelShelves(
  catalog: Product[],
  curatedSlugs: string[],
  heroSlides: ContentBlock[] = [],
): ReelShelf[] {
  const parents = catalog.filter((p) => !p.variantId && shootable(p));
  const bySlug = new Map(parents.map((p) => [p.slug, p]));

  // ---- All: clips, the curated flagships, then hub-featured — capped.
  const mix: Product[] = [];
  for (const p of [
    ...parents.filter((p) => p.video),
    ...curatedSlugs.map((s) => bySlug.get(s)).filter((p): p is Product => Boolean(p)),
    ...parents.filter((p) => p.badge === "best"),
  ]) {
    if (mix.length >= PER_SHELF) break;
    if (!mix.includes(p)) mix.push(p);
  }
  const mixFrames = mix.map(productFrame);

  // CMS hero slides (home_hero) with a photo ride along as campaign frames
  // after the second product, so the admin still steers the opener. A slide
  // promoting a product (or photo) the reel already shows is skipped.
  const shown = new Set(mixFrames.flatMap((f) => [f.href, f.img]));
  const campaigns: ReelFrame[] = heroSlides
    .filter((b) => b.image_url && b.title && !shown.has(b.link_url ?? "") && !shown.has(b.image_url))
    .slice(0, 2)
    .map((b, n) => {
      const st = (b.styles ?? {}) as Record<string, unknown>;
      return {
        key: `cms-${b.id ?? n}`,
        kind: "campaign",
        href: b.link_url || "/shop",
        eyebrow: typeof st.eyebrow === "string" && st.eyebrow ? st.eyebrow.replace(/[\s\-–—]+$/, "") : "Bethany House",
        title: b.title!,
        sub: b.subtitle || undefined,
        img: b.mobile_image_url || b.image_url!,
        alt: b.title!.replace(/\*/g, ""),
        cta: b.link_text || undefined,
      };
    });

  const shelves: ReelShelf[] = [{
    key: "all",
    label: "All",
    frames: [...mixFrames.slice(0, 2), ...campaigns, ...mixFrames.slice(2)],
  }];

  // ---- One ring per department that has enough to swipe through.
  for (const d of DEPARTMENTS) {
    const run = parents
      .filter((p) => rootCategory(p.category) === d.root)
      .sort((a, b) => rank(b) - rank(a))
      .slice(0, PER_SHELF);
    if (run.length < 2) continue;
    shelves.push({ key: d.root, label: d.label, ring: d.ring, frames: run.map(productFrame) });
  }
  return shelves;
}
