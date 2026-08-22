/** Single source of truth for business facts used across pages.
    Change a phone number or the delivery promise HERE, nowhere else. */
export const SITE = {
  name: "Bethany House",
  /** Canonical production origin — used for metadataBase, sitemap, robots,
      canonical links and absolute URLs in structured data. No trailing slash. */
  url: "https://bethanyhouse.co.ke",
  tagline:
    "The #1 supplier of Holy Communion elements, clergy apparel and Christian gifts — serving churches across East Africa.",
  phone: "+254 727 891 989",
  phoneHref: "tel:+254727891989",
  phone2: "+254 785 490 805",
  phoneHref2: "tel:+254785490805",
  /** Mail is hosted on bethanyhouse.co.ke (the old bethanygiftshop.com
      domain no longer resolves and cannot receive mail). */
  email: "info@bethanyhouse.co.ke",
  whatsapp: "https://wa.me/254727891989",
  /** Official social profiles — mirrored on the Google Business Profile, so
      schema `sameAs`, GBP and the site all assert one identity. */
  socials: [
    "https://www.facebook.com/houseofbethany",
    "https://www.instagram.com/bethanyhousecreative/",
    "https://www.tiktok.com/@bethanyhouse6",
    "https://youtube.com/@bethanyhouse2075",
  ],
  mapsUrl: "https://maps.google.com/?q=Sonalux+Building,+Moi+Avenue,+Nairobi",
  address: "Sonalux Building, 7th Floor, Room 18, Moi Avenue",
  city: "Nairobi, Kenya",
  /** Local directions — for a Kenyan (+254) customer who asks where we are. */
  directions:
    "We are located in Nairobi CBD, Kenya at Sonalux Building, 7th Floor, Room 18 — along Moi Avenue, near Nairobi Sports House, opposite Family Bank.",
  /** International reply — for a customer outside Kenya asking where we are / about delivery. */
  shipWorldwide:
    "We deliver to every city, every country and every continent from our workshop here in Nairobi, Kenya. Kindly share your city and country, and we will provide the shipping cost and the estimated delivery time.",
  /** Concise landmark hint for the footer / contact block (complements `address`). */
  landmarks: "Nairobi CBD — along Moi Avenue, near Nairobi Sports House, opposite Family Bank.",
  hours: "Mon–Sat · 8:00 AM – 5:00 PM",
  deliveryPromise:
    "Free delivery in Nairobi CBD for orders above KES 10,000 — we ship across East Africa",
  deliveryShort: "Free Nairobi CBD delivery over KES 10,000",
  payments: "M-Pesa · Visa · Mastercard · Cash on Delivery",
} as const;
