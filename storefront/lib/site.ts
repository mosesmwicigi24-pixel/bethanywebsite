/** Single source of truth for business facts used across pages.
    Change a phone number or the delivery promise HERE, nowhere else. */
export const SITE = {
  name: "Bethany House",
  tagline:
    "The #1 supplier of Holy Communion elements, clergy apparel and Christian gifts — serving churches across East Africa.",
  phone: "+254 727 891 989",
  phoneHref: "tel:+254727891989",
  phone2: "+254 785 490 805",
  email: "info@bethanygiftshop.com",
  address: "Sonalux Building, Moi Avenue",
  city: "Nairobi, Kenya",
  hours: "Mon–Sat · 8:00 AM – 5:00 PM",
  deliveryPromise:
    "Free delivery in Nairobi CBD for orders above KES 10,000 — we ship across East Africa",
  deliveryShort: "Free Nairobi CBD delivery over KES 10,000",
  payments: "M-Pesa · Visa · Mastercard · Cash on Delivery",
} as const;
