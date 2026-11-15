/* ============================================================
   What customers say — genuine reviews only.

   Every entry here must be a real, published customer review, copied word
   for word from where the customer posted it (today: the shop's Google
   Business Profile), under the name the customer published it with. Never
   write, tidy up or paraphrase one. If a review is edited or removed at its
   source, edit or remove it here.

   While this list is empty the homepage shows no quotes at all — only an
   invitation to read the reviews on Google.
   ============================================================ */

export interface CustomerReview {
  /** the reviewer's public display name, as published */
  author: string;
  /** the review, word for word */
  text: string;
  rating: 1 | 2 | 3 | 4 | 5;
  /** when it was posted, as the source shows it — "March 2026" */
  when?: string;
  source: "Google";
}

export const REVIEWS: CustomerReview[] = [];

/** The shop's listing on Google Maps — where its reviews are read and written. */
export const GOOGLE_REVIEWS_URL =
  "https://www.google.com/maps/search/?api=1&query=Bethany+House%2C+Sonalux+Building%2C+Moi+Avenue%2C+Nairobi";
