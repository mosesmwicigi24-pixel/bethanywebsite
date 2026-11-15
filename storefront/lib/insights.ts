import type { Product } from "./products";

/* ============================================================
   Buying patterns — what the receipts say.

   The hub mines its recognised sales (till, web, WhatsApp/chat and quotes
   that became orders) for products that share a receipt, and for the
   products on the most receipts, and publishes both as slugs:

     GET /api/v1/storefront/insights            (docs/HUB_CONTRACT.md §9)

   The storefront never sees a customer, a count or an amount — only
   "these go together" and "these sell most", strongest first.

   Advisory by design: with the hub unset, the endpoint missing, or no
   sales history yet, every helper here returns "nothing known" and each
   caller keeps its own honest fallback.
   ============================================================ */

const HUB = process.env.NEXT_PUBLIC_HUB_API; // https://hub.bethanyhouse.co.ke/api/v1
const REVALIDATE = 300; // seconds — buying patterns move slowly

export interface Insights {
  /** base product slug → slugs bought on the same receipt, strongest first */
  together: Record<string, string[]>;
  /** base product slugs on the most receipts, most first */
  popular: string[];
}

export const NO_INSIGHTS: Insights = { together: {}, popular: [] };

const slugList = (v: unknown): string[] =>
  Array.isArray(v) ? v.filter((s): s is string => typeof s === "string" && s.length > 0) : [];

/** The hub's feed, shape-checked. Never throws; unknown → NO_INSIGHTS. */
export async function getInsights(): Promise<Insights> {
  if (!HUB) return NO_INSIGHTS;
  try {
    const r = await fetch(`${HUB}/storefront/insights`, {
      next: { revalidate: REVALIDATE },
      headers: { Accept: "application/json" },
    });
    if (!r.ok) return NO_INSIGHTS;
    const body = (await r.json()) as { data?: { bought_together?: unknown; popular?: unknown } } | null;
    const raw = body?.data?.bought_together;
    const together: Record<string, string[]> = {};
    // An empty map arrives as [] (PHP); only a real object carries pairs.
    if (raw && typeof raw === "object" && !Array.isArray(raw)) {
      for (const [slug, list] of Object.entries(raw)) {
        const companions = slugList(list).filter((s) => s !== slug);
        if (companions.length) together[slug] = companions;
      }
    }
    return { together, popular: slugList(body?.data?.popular) };
  } catch {
    return NO_INSIGHTS;
  }
}

/** The slug the hub knows a product by (a variant sells under its parent). */
export const hubSlug = (p: Pick<Product, "slug" | "baseSlug">) => p.baseSlug ?? p.slug;

/** Best sellers first, in the hub's order; everything else keeps its place. */
export function byPopularity<T extends Pick<Product, "slug" | "baseSlug">>(list: T[], popular: string[]): T[] {
  if (!popular.length) return list;
  const rank = new Map(popular.map((s, i) => [s, i]));
  const at = (p: T) => rank.get(hubSlug(p)) ?? popular.length;
  return list.map((p, i) => ({ p, i })).sort((a, b) => at(a.p) - at(b.p) || a.i - b.i).map((x) => x.p);
}
