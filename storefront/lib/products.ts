export type Badge = "best" | "new" | "exclusive";

export interface Chip {
  icon: string;
  text: string;
}

export interface Measurement {
  name: string;
  unit?: string;
  required?: boolean;
}

export interface Product {
  slug: string;
  name: string;
  short: string;
  img: string;
  /** KES price (hub: prices row where currency_code=KES) */
  price: number;
  oldPrice?: number;
  /** USD price (hub: prices row where currency_code=USD) */
  priceUsd: number;
  oldPriceUsd?: number;
  /** hub: products.is_producible — made to order */
  producible?: boolean;
  /** hub: products.measurements JSON — template the customer must fill */
  measurements?: Measurement[];
  /** short campaign line for the product poster banner */
  tagline?: string;
  /** full gallery (falls back to [img]) */
  gallery?: string[];
  /** Apple-style "Take a closer look" features */
  closerLook?: { label: string; text: string; img?: string }[];
  rating: number;
  reviews: number;
  badge?: Badge;
  chips: Chip[];
  seller?: string;
  category: string;
}

export const formatKES = (n: number) => `KES ${n.toLocaleString("en-KE")}`;

export type Currency = "KES" | "USD";

/** Hub rule (Order::resolveCurrency): Kenya -> KES, everywhere else -> USD. */
export const formatMoney = (n: number, c: Currency) =>
  c === "KES" ? formatKES(n) : `$${n.toLocaleString("en-US")}`;

export const priceFor = (p: Product, c: Currency) =>
  c === "KES" ? p.price : p.priceUsd;
export const oldPriceFor = (p: Product, c: Currency) =>
  c === "KES" ? p.oldPrice : p.oldPriceUsd;

export const badgeLabel: Record<Badge, { text: string; cls: string }> = {
  best: { text: "Best Seller", cls: "tag-gold" },
  new: { text: "New Arrival", cls: "tag-green" },
  exclusive: { text: "Store Exclusive", cls: "tag-navy" },
};

export const products: Product[] = [
  {
    slug: "chalice-royale",
    tagline: "Set Apart for the Sacred",
    gallery: ["/products/Chalice_Cup.jpg", "/products/Chalice_Cup21.jpg", "/products/gold-wares.jpg", "/products/gold0_72.jpg"],
    closerLook: [
      { label: "24K gold finish", text: "Electroplated over solid brass and hand-polished for six hours per piece, then sealed with an anti-tarnish coat for weekly service.", img: "/products/Chalice_Cup.jpg" },
      { label: "Jewelled stem", text: "Four hand-set stones mark the cross at the node — a detail seen up close, at the rail.", img: "/products/Chalice_Cup21.jpg" },
      { label: "Fitted paten lid", text: "The paten seats precisely on the 450ml cup, keeping the elements covered until the moment of service.", img: "/products/Chalice_Cup.jpg" },
      { label: "Free engraving", text: "Parish names, dedications and anniversaries — etched beneath the base so the cup itself stays unmarked.", img: "/products/gold0_72.jpg" },
      { label: "The matching set", text: "Pair with the thurible and communion ware for one finish across the whole altar.", img: "/products/gold-wares.jpg" },
    ],
    priceUsd: 142, oldPriceUsd: 169,
    name: "Chalice Royale — Gold Chalice & Paten Set",
    short: "Chalice Royale",
    img: "/products/Chalice_Cup.jpg",
    price: 18500,
    oldPrice: 22000,
    rating: 4.9,
    reviews: 214,
    badge: "best",
    chips: [
      { icon: "✦", text: "24K gold-plated brass" },
      { icon: "✍", text: "Free engraving available" },
    ],
    seller: "Communion Best Seller #1",
    category: "Communion Elements",
  },
  {
    slug: "communion-ware-deluxe",
    tagline: "One Table, Served Whole",
    gallery: ["/products/gold-wares.jpg", "/products/live-iStock-1219476645.jpg", "/products/live-iStock-139692028.jpg", "/products/gold0_72.jpg"],
    closerLook: [
      { label: "Serves 500+", text: "Stacking communion trays, chalice and host box sized for congregations of five hundred and above.", img: "/products/live-iStock-139692028.jpg" },
      { label: "Stacking trays", text: "Interlocking golden trays carry the cups securely between the vestry and the rail.", img: "/products/live-iStock-1219476645.jpg" },
      { label: "One finish", text: "Every piece shares the same gold tone, so the table reads as one setting.", img: "/products/gold-wares.jpg" },
      { label: "Engraving available", text: "The base of each piece can carry your parish name — free, on request.", img: "/products/gold0_72.jpg" },
    ],
    priceUsd: 269, oldPriceUsd: 319,
    name: "Communion Ware Deluxe Set",
    short: "Communion Ware Deluxe",
    img: "/products/gold-wares.jpg",
    price: 35000,
    oldPrice: 42000,
    rating: 5.0,
    reviews: 48,
    badge: "exclusive",
    chips: [
      { icon: "◎", text: "Serves congregations of 500+" },
      { icon: "✦", text: "Tray, chalice & host box" },
    ],
    seller: "Communion Best Seller #3",
    category: "Communion Elements",
  },
  {
    slug: "altar-wine",
    tagline: "The Fruit of the Vine",
    gallery: ["/products/Altar_wine.png", "/products/live-Altar_wine.jpg"],
    closerLook: [
      { label: "Certified for service", text: "Sealed bottles, certified for communion service — stored upright and cool until delivery.", img: "/products/Altar_wine.png" },
      { label: "Non-alcoholic option", text: "A non-alcoholic pressing is available for congregations that require it — same bottle size, same handling.", img: "/products/live-Altar_wine.jpg" },
      { label: "Parish quantities", text: "Order by the bottle or by the case; parish accounts get standing quantities each month.", img: "/products/Altar_wine.png" },
    ],
    priceUsd: 12,
    name: "Altar Wine — 750ml",
    short: "Altar Wine",
    img: "/products/Altar_wine.png",
    price: 1200,
    rating: 4.8,
    reviews: 1032,
    chips: [
      { icon: "✚", text: "Non-alcoholic option available" },
      { icon: "◎", text: "Sealed & certified for service" },
    ],
    seller: "Communion Best Seller #2",
    category: "Communion Elements",
  },
  {
    slug: "communion-hosts",
    tagline: "The Bread of Remembrance",
    gallery: ["/products/Hosts.jpg", "/products/Hosts.png"],
    closerLook: [
      { label: "1,000 pieces", text: "A thousand hosts per sealed pack — enough for a month of services in most congregations.", img: "/products/Hosts.jpg" },
      { label: "Whole-wheat & gluten-free", text: "Both recipes available in every pack size, clearly marked.", img: "/products/Hosts.png" },
      { label: "Sealed freshness", text: "Packed in a sealed freshness bag; keep the pack closed and cool between services.", img: "/products/Hosts.jpg" },
    ],
    priceUsd: 14, oldPriceUsd: 16,
    name: "Communion Hosts — 1,000 pieces",
    short: "Communion Hosts",
    img: "/products/Hosts.jpg",
    price: 1500,
    oldPrice: 1800,
    rating: 4.9,
    reviews: 866,
    badge: "exclusive",
    chips: [
      { icon: "◎", text: "Whole-wheat & gluten-free" },
      { icon: "✚", text: "Sealed freshness pack" },
    ],
    seller: "Communion Best Seller #4",
    category: "Communion Elements",
  },
  {
    slug: "preaching-gown",
    tagline: "Preach with Presence",
    gallery: ["/products/preaching_gown1.jpg", "/products/live-ORDINATION2.png", "/products/live-ORDINATION6.png", "/products/live-preaching_gown_11.jpg", "/products/live-gown-back.jpg"],
    closerLook: [
      { label: "Made to measure", text: "Cut to the seven measurements you provide and sewn in our Nairobi workshop — 5–7 days from order to pulpit.", img: "/products/live-ORDINATION2.png" },
      { label: "Breathable fabric", text: "A wrinkle-free blend that holds its line through a full service and travels without ironing.", img: "/products/preaching_gown1.jpg" },
      { label: "Embroidery", text: "Sleeve and panel embroidery in gold or liturgical colours, stitched to your tradition.", img: "/products/live-ORDINATION6.png" },
      { label: "Every occasion", text: "Ordinations, Sunday service, conventions — styles for every moment at the pulpit.", img: "/products/live-preaching_gown_11.jpg" },
      { label: "Alterations after", text: "We alter and repair what we sew — the gown is maintained for the life of its ministry.", img: "/products/live-gown-back.jpg" },
    ],
    priceUsd: 96, oldPriceUsd: 115,
    producible: true,
    measurements: [
      { name: "Neck", unit: "in", required: true },
      { name: "Shoulders", unit: "in", required: true },
      { name: "Sleeves", unit: "in", required: true },
      { name: "Chest", unit: "in", required: true },
      { name: "Waist", unit: "in" },
      { name: "Hips", unit: "in" },
      { name: "Full Length", unit: "in", required: true },
    ],
    name: "Premium Preaching Gown — Tailored",
    short: "Preaching Gown",
    img: "/products/preaching_gown1.jpg",
    price: 12500,
    oldPrice: 15000,
    rating: 4.8,
    reviews: 157,
    badge: "new",
    chips: [
      { icon: "✂", text: "Made-to-measure in Nairobi" },
      { icon: "〜", text: "Breathable wrinkle-free fabric" },
    ],
    seller: "Apparel Best Seller #1",
    category: "Clergy Apparel",
  },
  {
    slug: "clergy-cassock",
    tagline: "Daily Reverence, Tailored",
    gallery: ["/products/cassock212.jpg", "/products/live-6U3A4750.jpg", "/products/live-6U3A4787.jpg", "/products/live-CASSOCK1.png", "/products/live-cassock2.jpg"],
    closerLook: [
      { label: "Full-length cut", text: "A classic full-length silhouette, tailored to your measurements for daily wear.", img: "/products/live-6U3A4750.jpg" },
      { label: "Every colour", text: "Black, white, purple, red and green — the full liturgical year, plus custom piping.", img: "/products/live-6U3A4787.jpg" },
      { label: "Breathable weave", text: "Chosen for Nairobi heat: a breathable weave that keeps its drape through the day.", img: "/products/live-CASSOCK1.png" },
      { label: "Made in 5–7 days", text: "Measured, sewn and delivered within a week, anywhere in Kenya.", img: "/products/cassock212.jpg" },
    ],
    priceUsd: 65,
    producible: true,
    measurements: [
      { name: "Neck", unit: "in", required: true },
      { name: "Shoulders", unit: "in", required: true },
      { name: "Sleeves", unit: "in", required: true },
      { name: "Chest", unit: "in", required: true },
      { name: "Waist", unit: "in" },
      { name: "Hips", unit: "in" },
      { name: "Full Length", unit: "in", required: true },
    ],
    name: "Classic Clergy Cassock",
    short: "Clergy Cassock",
    img: "/products/cassock212.jpg",
    price: 8500,
    rating: 4.7,
    reviews: 203,
    chips: [
      { icon: "✂", text: "All liturgical colours" },
      { icon: "〜", text: "Full-length, breathable" },
    ],
    seller: "Apparel Best Seller #2",
    category: "Clergy Apparel",
  },
  {
    slug: "ornate-chasuble",
    tagline: "Vested in Glory",
    gallery: ["/products/chasuble31.jpg", "/products/live-CHASUBLE_1.png", "/products/live-CHASUBLE_2.png", "/products/live-CHASUBLE_4.png"],
    closerLook: [
      { label: "Hand embroidery", text: "The emblem is embroidered by hand, panel by panel, before assembly.", img: "/products/live-CHASUBLE_1.png" },
      { label: "Matching stole", text: "Every chasuble ships with its matching stole, cut from the same cloth.", img: "/products/live-CHASUBLE_2.png" },
      { label: "Seasonal sets", text: "Order the full liturgical set — one chasuble per season, one wardrobe for the year.", img: "/products/live-CHASUBLE_4.png" },
      { label: "Made to measure", text: "Sewn to your measurements in Nairobi, 5–7 days from order.", img: "/products/chasuble31.jpg" },
    ],
    priceUsd: 108, oldPriceUsd: 127,
    producible: true,
    measurements: [
      { name: "Neck", unit: "in", required: true },
      { name: "Shoulders", unit: "in", required: true },
      { name: "Sleeves", unit: "in", required: true },
      { name: "Chest", unit: "in", required: true },
      { name: "Waist", unit: "in" },
      { name: "Hips", unit: "in" },
      { name: "Full Length", unit: "in", required: true },
    ],
    name: "Ornate Chasuble — Embroidered",
    short: "Ornate Chasuble",
    img: "/products/chasuble31.jpg",
    price: 14000,
    oldPrice: 16500,
    rating: 4.9,
    reviews: 89,
    badge: "new",
    chips: [
      { icon: "✦", text: "Hand-embroidered emblem" },
      { icon: "✂", text: "Matching stole included" },
    ],
    seller: "Apparel Best Seller #4",
    category: "Clergy Apparel",
  },
  {
    slug: "clergy-stole",
    tagline: "Colours of the Seasons",
    gallery: ["/products/Stoles5.jpg", "/products/live-20201116_143915.jpg", "/products/live-stoles2.jpg"],
    closerLook: [
      { label: "Every colour", text: "Purple, red, green, white and gold — the full liturgical calendar in one drawer.", img: "/products/Stoles5.jpg" },
      { label: "Custom emblems", text: "Crosses, doves, wheat and chalices — or your parish emblem, embroidered on order.", img: "/products/live-20201116_143915.jpg" },
      { label: "Gift-ready", text: "A favourite ordination and anniversary gift — ask about gift wrapping.", img: "/products/live-stoles2.jpg" },
    ],
    priceUsd: 27,
    name: "Embroidered Clergy Stole",
    short: "Clergy Stole",
    img: "/products/Stoles5.jpg",
    price: 3500,
    rating: 4.8,
    reviews: 412,
    chips: [
      { icon: "✦", text: "Every liturgical colour" },
      { icon: "✍", text: "Custom emblems on order" },
    ],
    seller: "Apparel Best Seller #3",
    category: "Clergy Apparel",
  },
  {
    slug: "clergy-shirt",
    tagline: "The Everyday Collar",
    gallery: ["/products/Shirt11.jpg", "/products/live-shirt.jpg"],
    closerLook: [
      { label: "Tab collar", text: "A clean tab-collar cut in an easy-iron cotton blend for daily wear.", img: "/products/Shirt11.jpg" },
      { label: "Six colours", text: "Black, white, grey, blue, purple and red — sized S to XXL.", img: "/products/live-shirt.jpg" },
      { label: "Made to measure", text: "Off the shelf in standard sizes, or sewn to your three measurements in a week.", img: "/products/Shirt11.jpg" },
    ],
    priceUsd: 22, oldPriceUsd: 25,
    producible: true,
    measurements: [
      { name: "Neck", unit: "in", required: true },
      { name: "Chest", unit: "in", required: true },
      { name: "Sleeves", unit: "in", required: true },
      { name: "Shirt Length", unit: "in" },
    ],
    name: "Tab-Collar Clergy Shirt",
    short: "Clergy Shirt",
    img: "/products/Shirt11.jpg",
    price: 2800,
    oldPrice: 3200,
    rating: 4.6,
    reviews: 534,
    chips: [
      { icon: "〜", text: "Easy-iron cotton blend" },
      { icon: "◎", text: "S–XXL, 6 colours" },
    ],
    seller: "Apparel Best Seller #5",
    category: "Clergy Apparel",
  },
  {
    slug: "pectoral-cross",
    tagline: "Worn Close to the Heart",
    gallery: ["/products/cross1.jpg", "/products/live-cross2.jpg"],
    closerLook: [
      { label: "Gold finish", text: "A polished gold finish over solid metal, with a matching chain in the box.", img: "/products/cross1.jpg" },
      { label: "Gift-boxed", text: "Presented in a lined gift box — ready for ordinations and consecrations.", img: "/products/live-cross2.jpg" },
      { label: "Engraving", text: "Names and dates engraved on the reverse, free on request.", img: "/products/cross1.jpg" },
    ],
    priceUsd: 50, oldPriceUsd: 60,
    name: "Pectoral Cross — Gold Finish",
    short: "Pectoral Cross",
    img: "/products/cross1.jpg",
    price: 6500,
    oldPrice: 7800,
    rating: 4.8,
    reviews: 121,
    chips: [
      { icon: "✦", text: "Gift-boxed with chain" },
      { icon: "✍", text: "Engraving available" },
    ],
    seller: "Gifts Best Seller #3",
    category: "Gifts & Accessories",
  },
  {
    slug: "altar-bell",
    tagline: "The Sound of Reverence",
    gallery: ["/products/bell.jpg"],
    closerLook: [
      { label: "Four-bell brass", text: "Four tuned brass bells on one handle for a full, rounded ring.", img: "/products/bell.jpg" },
      { label: "Polished finish", text: "Hand-polished brass that keeps its shine with a soft cloth.", img: "/products/bell.jpg" },
    ],
    priceUsd: 40,
    name: "Altar Bell — 4-Bell Brass",
    short: "Altar Bell",
    img: "/products/bell.jpg",
    price: 5200,
    rating: 4.7,
    reviews: 64,
    chips: [
      { icon: "✦", text: "Polished brass finish" },
      { icon: "◎", text: "Clear, rounded tone" },
    ],
    seller: "Essentials Best Seller #2",
    category: "Church Essentials",
  },
  {
    slug: "tallit-prayer-shawl",
    tagline: "Woven with Meaning",
    gallery: ["/products/tallit.jpg", "/products/live-Tallit_1.png", "/products/live-Tallit_3.png"],
    closerLook: [
      { label: "Traditional weave", text: "Woven bands and knotted fringes in the traditional pattern.", img: "/products/live-Tallit_1.png" },
      { label: "Carry pouch", text: "Each tallit comes with its matching zip pouch for travel and storage.", img: "/products/live-Tallit_3.png" },
      { label: "Sizes & colours", text: "Classic blue, gold and purple bands across three sizes.", img: "/products/tallit.jpg" },
    ],
    priceUsd: 35,
    name: "Tallit Prayer Shawl",
    short: "Tallit",
    img: "/products/tallit.jpg",
    price: 4500,
    rating: 4.9,
    reviews: 76,
    badge: "new",
    chips: [
      { icon: "✦", text: "Woven with traditional design" },
      { icon: "◎", text: "Carry pouch included" },
    ],
    seller: "Prayer Wear Best Seller #1",
    category: "Prayer Wear",
  },
  {
    slug: "devotional-365",
    tagline: "A Year with Jesus",
    gallery: ["/products/365days.png", "/products/live-365-2.png"],
    closerLook: [
      { label: "Daily readings", text: "A scripture, reflection and prayer for every day of the year.", img: "/products/365days.png" },
      { label: "Gift edition", text: "A favourite confirmation and graduation gift — ask about bulk pricing for classes.", img: "/products/live-365-2.png" },
    ],
    priceUsd: 15,
    name: "365 Days With Jesus — Devotional",
    short: "365 Days With Jesus",
    img: "/products/365days.png",
    price: 1950,
    rating: 4.9,
    reviews: 302,
    badge: "new",
    chips: [{ icon: "◎", text: "Daily scripture & reflection" }],
    category: "Bibles & Devotionals",
  },
];

export const bySlug = (slug: string) => products.find((p) => p.slug === slug);
export const byCategory = (cat: string) => products.filter((p) => p.category === cat);
