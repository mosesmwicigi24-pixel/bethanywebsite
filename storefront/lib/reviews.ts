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

/* Copied 2026-11-16 from the owner's screenshots of the shop's Google
   reviews — spelling, punctuation and emoji exactly as posted. Reviews
   with a star rating but no words are not listed (there is nothing to
   quote). Order = the order shown; the homepage takes the first six. */
export const REVIEWS: CustomerReview[] = [
  {
    author: "Doreen Dee", rating: 5, source: "Google",
    text: "The best shop for communion elements. Customer satisfaction 👌 is top notch thank you, Bethany, for being a one stop shop. God bless you 🙏",
  },
  {
    author: "Njagi Munene", rating: 5, source: "Google",
    text: "Excellent work by Bethany House. The touch of quality and timeliness is just but adorable. I recommend Bethany House to anyone seeking priestly garments and services.\nMay God bless you.",
  },
  {
    author: "Njeri Muturi", rating: 4, source: "Google",
    text: "Ordered my home communion pack and im very greatful that delivery was done in quick time and the customer service too was splendid!! The packing of the items is well done. I would not hesitate to recommend Bethany House for your communion needs supply! I will be back!",
  },
  {
    author: "Ruchiaka Joseph Kinyua", rating: 5, source: "Google",
    text: "Very efficient, very good quality service, good customer service and friendliness . I kindly and highly recommend it for you. They will not disappoint you. Always prompt and quick",
  },
  {
    author: "Bengat Naftali", rating: 5, source: "Google",
    text: "I've visited Bethany Gift shop countless time and their Church Products are amazing. also their customer service is exceptional.I recommend anyone to them you wont be disappointed. Good Job Bethany Keep up and God bless.",
  },
  {
    author: "Susan Njenga", rating: 5, source: "Google",
    text: "Customer service is great! the quality of products is excellent. I recommend Bethany gift shop for all your priestly accessories and more.",
  },
  {
    author: "Grace Kibe", rating: 5, source: "Google",
    text: "Thank you Bethany Gift Shop. Your customer care are excellent and timely delivery. Keep up the good work. I would recommend you over and over again.",
  },
  {
    author: "Eric Mwangi", rating: 5, source: "Google",
    text: "Lovely place, incredible professional team, high quality product's",
  },
  {
    author: "Njoroh Bazyon", rating: 5, source: "Google",
    text: "Efficient, welcoming and their work is neat, clean and presentable.",
  },
  {
    author: "Peter muasya", rating: 4, source: "Google",
    text: "Well served by courteous, friendly staff. Keep up",
  },
  {
    author: "gennevieve awino", rating: 5, source: "Google",
    text: "They are very polite and helpful",
  },
  {
    author: "peter mukhwana", rating: 5, source: "Google",
    text: "It's just exemplary",
  },
  {
    author: "Judah Thura", rating: 4, source: "Google",
    text: "Good service 💯",
  },
];

/** The shop's listing on Google Maps — where its reviews are read and written. */
export const GOOGLE_REVIEWS_URL =
  "https://www.google.com/maps/search/?api=1&query=Bethany+House%2C+Sonalux+Building%2C+Moi+Avenue%2C+Nairobi";
