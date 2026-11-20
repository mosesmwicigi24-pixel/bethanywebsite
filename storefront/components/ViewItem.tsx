"use client";

import { useEffect } from "react";
import { track } from "@/lib/analytics";

/* GA4 `view_item` for a product page — the top of the funnel that
   add_to_cart → begin_checkout → purchase (and contact_click) sit under.
   Prices are reported in KES, the catalogue's base currency. */
export default function ViewItem({ slug, name, price, category }: { slug: string; name: string; price: number; category?: string }) {
  useEffect(() => {
    track("view_item", {
      currency: "KES",
      value: price,
      items: [{ item_id: slug, item_name: name, price, quantity: 1 }],
      item_category: category,
    });
  }, [slug, name, price, category]);
  return null;
}
