/* Root categories for the storefront.

   The hub's category data has sprawled into ~20 overlapping leaf names
   (Clergy Vestments / Vestments / Gowns / Clergy Apparel; Communion Items /
   Communion Elements / Communion Wines / Golden Communion Trays; Bibles /
   Children Bibles / Bibles & Devotionals; …). The shop facet should show a
   small, clean set of departments, so we fold every leaf onto one root.

   Keep this in sync with the nav in components/chrome.tsx. */

export const ROOT_CATEGORIES = [
  "Communion Elements",
  "Clergy Apparel",
  "Bibles & Devotionals",
  "Gifts & Accessories",
  "Church Essentials",
] as const;

export type RootCategory = (typeof ROOT_CATEGORIES)[number];

/** Leaf category (lowercased) → root department. */
const LEAF_TO_ROOT: Record<string, RootCategory> = {
  // Communion Elements — everything for the Lord's Table + sacramentals
  "communion items": "Communion Elements",
  "communion elements": "Communion Elements",
  "communion accessories": "Communion Elements",
  "communion wines": "Communion Elements",
  "communion wine": "Communion Elements",
  "golden communion trays": "Communion Elements",
  "colden communion trays": "Communion Elements", // hub data typo (Golden)
  "anointing": "Communion Elements",
  "anointing oil": "Communion Elements",
  // Live hub category (name_en "Chalice") — unmapped it fell to the default
  // bucket, filing chalice cups under Church Essentials on the live site.
  "chalice": "Communion Elements",
  "chalices": "Communion Elements",
  "chalice cups": "Communion Elements",

  // Clergy Apparel — vestments, gowns, cassocks, prayer wear + their extras
  "clergy vestments": "Clergy Apparel",
  "vestments": "Clergy Apparel",
  "clergy apparel": "Clergy Apparel",
  "clergy accessories": "Clergy Apparel",
  "gowns": "Clergy Apparel",
  "gown": "Clergy Apparel",
  "prayer wear": "Clergy Apparel",

  // Bibles & Devotionals — scripture + devotional books
  "bibles": "Bibles & Devotionals",
  "children bibles": "Bibles & Devotionals",
  "bibles & devotionals": "Bibles & Devotionals",
  "books & gifts": "Bibles & Devotionals",

  // Gifts & Accessories
  "gifts": "Gifts & Accessories",
  "gifts & accessories": "Gifts & Accessories",

  // Church Essentials — general supplies for the sanctuary
  "church supplies": "Church Essentials",
  "church essentials": "Church Essentials",
};

/** Fold any raw category string onto its root department. */
export function rootCategory(leaf: string | undefined | null): RootCategory {
  if (!leaf) return "Church Essentials";
  return LEAF_TO_ROOT[leaf.trim().toLowerCase()] ?? "Church Essentials";
}

/** Link to the shop filtered to one department. Single source of truth so the
    nav, footer and homepage tiles can't drift apart (the homepage tiles used to
    point at a bare /shop and quietly filtered nothing). */
export const shopCat = (c: RootCategory) => `/shop?category=${encodeURIComponent(c)}`;

/* ============================================================
   Category landing pages — /category/<slug>

   These are the indexable department hubs ("clergy vestments",
   "communion elements", …) that searches actually land on; the /shop
   query filters above remain the browsing UI. Slugs are PERMANENT —
   they are canonical URLs and legacy-redirect targets. Never rename
   one; add a redirect instead.

   All copy below states only verified business facts (lib/site.ts):
   payments, delivery promises, the 5–7 day tailoring window, address
   and hours. No superlatives, no invented claims.
   ============================================================ */

export type CategoryFaq = { q: string; a: string };

export type CategoryPage = {
  slug: string;
  root: RootCategory;
  /** H1 / link label — phrased for how churches actually search. */
  name: string;
  /** <title> (template appends "| Bethany House"). */
  title: string;
  /** Meta description, ~150 chars. */
  description: string;
  /** Intro paragraphs rendered above the grid. */
  intro: string[];
  faqs: CategoryFaq[];
  related: string[]; // sibling slugs
};

export const CATEGORY_PAGES: CategoryPage[] = [
  {
    slug: "clergy-vestments",
    root: "Clergy Apparel",
    name: "Clergy Vestments & Apparel",
    title: "Clergy Vestments in Nairobi, Kenya — Cassocks, Gowns & Stoles",
    description:
      "Cassocks, preaching and ordination gowns, albs, stoles, clergy shirts and collars in Nairobi, Kenya — ready-made or made to measure in 5–7 days, delivered countrywide.",
    intro: [
      "Everything the pulpit wears: cassocks, preaching gowns, ordination gowns, albs, stoles, clergy shirts, collars and prayer wear. Buy ready-made sizes off the rack, or have your vestments made to measure in our Nairobi workshop — tailoring takes 5–7 days.",
      "We serve ministers across traditions — Anglican, Catholic, Methodist, Presbyterian, Pentecostal, Seventh-day Adventist and more — and stock the liturgical colours each season calls for. Visit us for a fitting at Sonalux Building, 7th Floor, Room 18, Moi Avenue, Nairobi (Mon–Sat, 8:00 AM–5:00 PM), or send your measurements on WhatsApp and we will tailor and deliver: free within Nairobi CBD on orders above KES 10,000, across Kenya and East Africa, and worldwide on request.",
    ],
    faqs: [
      {
        q: "How long does a made-to-measure cassock or gown take?",
        a: "Tailoring in our Nairobi workshop takes 5–7 days from confirmed measurements. Ready-made sizes ship immediately while stocks last.",
      },
      {
        q: "Can I order vestments from outside Nairobi or outside Kenya?",
        a: "Yes. Send your measurements and order on WhatsApp (+254 727 891 989). We deliver across Kenya and East Africa, and ship worldwide — share your city and country for the shipping cost and delivery time.",
      },
      {
        q: "How do I pay?",
        a: "M-Pesa, Visa, Mastercard, or cash on delivery. Prices can be viewed in KES, USD or ZMW on every product page.",
      },
      {
        q: "Can I come in for a fitting?",
        a: "Yes — we welcome fittings at Sonalux Building, 7th Floor, Room 18, Moi Avenue, Nairobi CBD, Monday to Saturday, 8:00 AM to 5:00 PM.",
      },
    ],
    related: ["communion-elements", "church-gifts", "church-essentials"],
  },
  {
    slug: "communion-elements",
    root: "Communion Elements",
    name: "Holy Communion Elements & Supplies",
    title: "Holy Communion Elements in Nairobi, Kenya — Wafers, Wine & Trays",
    description:
      "Buy communion wafers, altar wine and grape juice, trays, cups and chalices in Nairobi, Kenya — M-Pesa or card, free CBD delivery, countrywide and worldwide shipping.",
    intro: [
      "The Lord's Table, fully supplied from Nairobi: communion wafers and hosts, altar wine and grape juice, serving trays and cups, chalices, ciboria and refiller bottles. We serve congregations of every size — from a small fellowship to churches serving thousands — and supply both wine and unfermented grape juice so every tradition is provided for.",
      "Order online or on WhatsApp (+254 727 891 989) and pay by M-Pesa, card or cash on delivery. Delivery is free within Nairobi CBD on orders above KES 10,000; we deliver across Kenya and East Africa, and ship worldwide on request from our store at Sonalux Building, Moi Avenue, Nairobi.",
    ],
    faqs: [
      {
        q: "How much should our church order for a communion service?",
        a: "It depends on your congregation size and how you serve. Tell us your typical attendance on WhatsApp or by phone and we will recommend quantities of wafers, wine or juice, and the tray and cup set that fits.",
      },
      {
        q: "Do you stock both communion wine and non-alcoholic grape juice?",
        a: "Yes — both are stocked, so churches that use wine and churches that use unfermented grape juice are equally served.",
      },
      {
        q: "Can we get supplies delivered before Sunday?",
        a: "Order ahead and we will schedule delivery: free within Nairobi CBD above KES 10,000, and across Kenya and East Africa. For urgent orders, call or WhatsApp +254 727 891 989 and we will advise honestly what is possible.",
      },
      {
        q: "How do I pay?",
        a: "M-Pesa, Visa, Mastercard, or cash on delivery — whichever suits your church's process.",
      },
    ],
    related: ["church-essentials", "clergy-vestments", "church-gifts"],
  },
  {
    slug: "bibles-devotionals",
    root: "Bibles & Devotionals",
    name: "Bibles & Devotionals",
    title: "Buy Bibles in Nairobi, Kenya — Study, Children's & Gift Bibles",
    description:
      "Buy Bibles and devotionals in Nairobi, Kenya — study Bibles, children's Bibles and daily devotionals, with bulk orders for churches and schools welcome.",
    intro: [
      "Scripture and daily reading for the whole congregation: study and reference Bibles, children's Bibles and story collections, and daily devotionals. Popular versions in stock include the NIV and New King James Version, alongside children's titles and 365-day devotionals.",
      "Churches and schools ordering in bulk are welcome — call or WhatsApp +254 727 891 989 for a quote. Pay by M-Pesa, card or cash on delivery; delivery is free within Nairobi CBD on orders above KES 10,000, with delivery across Kenya and East Africa and worldwide shipping on request.",
    ],
    faqs: [
      {
        q: "Can our church or school order Bibles in bulk?",
        a: "Yes — bulk orders are welcome. Call or WhatsApp +254 727 891 989 with the title and quantity you need and we will prepare a quote.",
      },
      {
        q: "Which Bible versions do you stock?",
        a: "Stock changes, but popular versions include the NIV and New King James Version, plus children's Bibles and devotionals. Browse the products below for what is available today, or ask us for a specific title.",
      },
      {
        q: "Do you deliver?",
        a: "Yes — free within Nairobi CBD on orders above KES 10,000, across Kenya and East Africa, and worldwide on request.",
      },
      {
        q: "How do I pay?",
        a: "M-Pesa, Visa, Mastercard, or cash on delivery.",
      },
    ],
    related: ["church-gifts", "clergy-vestments", "communion-elements"],
  },
  {
    slug: "church-gifts",
    root: "Gifts & Accessories",
    name: "Church Gifts & Accessories",
    title: "Church Gifts in Nairobi, Kenya — Covenant Rings, Crosses & Keepsakes",
    description:
      "Meaningful church gifts in Nairobi, Kenya — covenant rings, crosses, keepsakes, tallits and prayer shawls for ordinations, confirmations and pastor appreciation, delivered countrywide.",
    intro: [
      "Gifts with meaning for the moments that matter: ordinations and inductions, consecrations, confirmations, pastor and elder appreciation, church anniversaries and thanksgiving. Browse covenant rings for bishops, apostles and overseers, crosses and keepsakes, tallits and prayer shawls, and accessories that serve daily ministry.",
      "Unsure what to give? Tell us the occasion on WhatsApp (+254 727 891 989) and we will suggest options within your budget. Free engraving is offered on communion ware, and every order can be delivered — free within Nairobi CBD above KES 10,000, across Kenya and East Africa, and worldwide on request.",
    ],
    faqs: [
      {
        q: "What is a good gift for an ordination or induction?",
        a: "Crosses, tallits and prayer shawls, quality Bibles and communion ware are all fitting. Tell us the occasion and budget on WhatsApp and we will suggest options.",
      },
      {
        q: "Do you stock covenant rings?",
        a: "Yes — bishopric rings in all sizes and apostolic rings for overseers and senior ministers, fitted in store at Moi Avenue, Nairobi and delivered across Kenya and worldwide. Congregations often present one at a consecration; our covenant rings guide explains the meaning and how to choose.",
      },
      {
        q: "Do you offer engraving?",
        a: "Yes — free engraving is offered on communion ware, which makes chalices and trays a lasting presentation gift.",
      },
      {
        q: "Can you deliver a gift directly to the recipient?",
        a: "Yes — share the delivery address and we will arrange it: free within Nairobi CBD above KES 10,000, across Kenya and East Africa, and worldwide on request.",
      },
      {
        q: "How do I pay?",
        a: "M-Pesa, Visa, Mastercard, or cash on delivery.",
      },
    ],
    related: ["bibles-devotionals", "clergy-vestments", "communion-elements"],
  },
  {
    slug: "church-essentials",
    root: "Church Essentials",
    name: "Church Essentials & Supplies",
    title: "Church Supplies in Nairobi, Kenya — Bells, Candles & Incense",
    description:
      "Church supplies in Nairobi, Kenya — bells, candles, incense and sanctuary ware, with parish and diocese accounts and delivery across East Africa.",
    intro: [
      "The everyday supplies a sanctuary runs on: bells, candles and candle holders, altar linens and general church ware. Whether you are replacing a single item or setting up a new church from scratch, this is the department to start in.",
      "Setting up a new congregation? Visit us at Sonalux Building, Moi Avenue (Mon–Sat, 8:00 AM–5:00 PM) or message +254 727 891 989 and we will help you put a complete list together. We serve parish and diocese accounts, take M-Pesa, card or cash on delivery, and deliver — free within Nairobi CBD above KES 10,000, across Kenya and East Africa, and worldwide on request.",
    ],
    faqs: [
      {
        q: "We are setting up a new church — can you help us plan what to buy?",
        a: "Yes. Visit the store or send us a message on WhatsApp (+254 727 891 989) and we will help you build a complete, budget-conscious list for your sanctuary.",
      },
      {
        q: "Do you serve parishes and dioceses buying centrally?",
        a: "Yes — we serve parish and diocese accounts. Contact us to set up ordering for your church body.",
      },
      {
        q: "Do you deliver upcountry?",
        a: "Yes — we deliver across Kenya and East Africa, and ship worldwide on request. Delivery is free within Nairobi CBD on orders above KES 10,000.",
      },
      {
        q: "How do I pay?",
        a: "M-Pesa, Visa, Mastercard, or cash on delivery.",
      },
    ],
    related: ["communion-elements", "clergy-vestments", "church-gifts"],
  },
];

const BY_SLUG = new Map(CATEGORY_PAGES.map((c) => [c.slug, c]));
const BY_ROOT = new Map(CATEGORY_PAGES.map((c) => [c.root, c]));

export const categoryPage = (slug: string) => BY_SLUG.get(slug);

/** Department entry link — the indexable hub page for a root category.
    Use this for nav/footer/homepage department links; keep shopCat() for
    the shop's own filter UI. */
export const categoryHref = (root: RootCategory) =>
  `/category/${BY_ROOT.get(root)!.slug}`;
