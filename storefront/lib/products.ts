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
