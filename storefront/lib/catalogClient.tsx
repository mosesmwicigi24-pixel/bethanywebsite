"use client";

import { ReactNode, createContext, useContext, useMemo } from "react";
import type { Product } from "./products";

/* The live catalog, seeded once server-side (root layout fetches
   getCatalog()) and shared with every client component — cart lookups,
   search, related products — so nothing depends on a hardcoded list. */

interface CatalogCtx {
  all: Product[];
  bySlug: (slug: string) => Product | undefined;
  /** base product slug → slugs bought on the same receipts (lib/insights.ts) */
  together: Record<string, string[]>;
}

const NONE: Record<string, string[]> = {};
const Ctx = createContext<CatalogCtx>({ all: [], bySlug: () => undefined, together: NONE });
export const useCatalog = () => useContext(Ctx);

export function CatalogProvider(
  { catalog, together = NONE, children }: { catalog: Product[]; together?: Record<string, string[]>; children: ReactNode },
) {
  const value = useMemo<CatalogCtx>(() => {
    const map = new Map(catalog.map((p) => [p.slug, p]));
    return { all: catalog, bySlug: (slug) => map.get(slug), together };
  }, [catalog, together]);
  return <Ctx.Provider value={value}>{children}</Ctx.Provider>;
}
