import type { ContentBlock } from "./theme";

/* ============================================================
   What customers say — genuine reviews, kept in the hub.

   Reviews are not written in this codebase. They live in the hub's database
   as content blocks in the slot `home_testimonial` (admin → Home Front
   Customization → Home Page → Customer Reviews), and arrive with the rest of
   the homepage content (getSiteContent):

     title          the reviewer's public name
     subtitle       the review, word for word
     styles.rating  1–5 stars
     styles.eyebrow where it was posted — "Google review"

   A block without a name, a text or a valid star rating is not a review and
   is ignored — so nothing half-entered, and none of the older written-for-
   the-website quotes (which carried no rating), can reach the page. With no
   reviews the homepage shows no quotes at all, only the way to read them on
   Google.
   ============================================================ */

export interface CustomerReview {
  id: number;
  /** the reviewer's public display name, as published */
  author: string;
  /** the review, word for word */
  text: string;
  rating: 1 | 2 | 3 | 4 | 5;
  /** where it was posted, e.g. "Google review" ("" when the hub has none) */
  source: string;
}

/** How many reviews the homepage shows (the hub's order decides which). */
export const HOME_REVIEWS = 6;

export function reviewsFrom(blocks: ContentBlock[] | undefined): CustomerReview[] {
  const out: CustomerReview[] = [];
  for (const b of blocks ?? []) {
    const author = b.title?.trim();
    const text = b.subtitle?.trim();
    const rating = Number((b.styles ?? {}).rating);
    if (!author || !text || !Number.isInteger(rating) || rating < 1 || rating > 5) continue;
    const eyebrow = (b.styles ?? {}).eyebrow;
    out.push({
      id: b.id,
      author,
      text,
      rating: rating as CustomerReview["rating"],
      source: typeof eyebrow === "string" ? eyebrow.trim() : "",
    });
  }
  return out;
}

/** The shop's listing on Google Maps — where its reviews are read and written. */
export const GOOGLE_REVIEWS_URL =
  "https://www.google.com/maps/search/?api=1&query=Bethany+House%2C+Sonalux+Building%2C+Moi+Avenue%2C+Nairobi";
