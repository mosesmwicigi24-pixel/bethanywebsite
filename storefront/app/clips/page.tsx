import type { Metadata } from "next";
import ClipFeed, { type ClipItem } from "@/components/ClipFeed";
import { getCatalog } from "@/lib/catalog";
import { SITE } from "@/lib/site";

export const revalidate = 300;

export const metadata: Metadata = {
  title: "Clips",
  description: `Short clips of communion ware, vestments and gifts from ${SITE.name} — swipe through, tap to shop.`,
  alternates: { canonical: "/clips" },
};

/** Full-screen, swipe-through feed of every product that has a clip —
    the phone-first way in to the catalogue (jojosfashion.com's mobile
    feed, on our stock). Products come from the same catalogue as the
    cards; a clip uploaded in the Hub appears here on the next refresh. */
export default async function ClipsPage() {
  const catalog = await getCatalog();
  const items: ClipItem[] = catalog
    .filter((p) => !p.variantId && p.video)
    .map((p) => ({
      slug: p.slug,
      name: p.name,
      category: p.category,
      video: p.video!,
      img: p.img,
      price: p.price,
      priceUsd: p.priceUsd,
      oldPrice: p.oldPrice,
      chip: p.chips[0]?.text,
      producible: Boolean(p.producible),
    }));

  return <ClipFeed items={items} />;
}
