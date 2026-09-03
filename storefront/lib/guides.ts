/* ============================================================
   Guides — the editorial engine. Each guide targets one search
   phrase-family a product page can't carry alone ("covenant ring",
   "how many communion wafers", "liturgical colours"), answers it
   genuinely, and hands the reader the products it discussed.

   Rules of the section:
   • Slugs are PERMANENT once deployed — they accumulate equity.
   • Every product fact must match the live catalog copy; every
     church-practice claim stays mainstream and tradition-neutral.
   • FAQs here are rendered on the page AND emitted as FAQPage
     schema, so they must mirror exactly (see faqPageJsonLd).
   • `updated` feeds the sitemap's lastModified — bump it only when
     the content genuinely changes.
   ============================================================ */

export type GuideSection = { h: string; p: string[] };
export type GuideFaq = { q: string; a: string };

export type Guide = {
  slug: string;
  /** Card + H1 */
  title: string;
  /** <title> (template appends "| Bethany House") */
  metaTitle: string;
  /** Meta description ~150 chars */
  description: string;
  /** Eyebrow label on card + article */
  category: string;
  published: string; // ISO date
  updated: string; // ISO date — bump on real content changes
  minutes: number; // honest read-time estimate
  intro: string[];
  sections: GuideSection[];
  faqs: GuideFaq[];
  /** Live catalog slugs rendered as the "shop this guide" rail */
  productSlugs: string[];
  /** Where the rail's "view all" points */
  shopHref: string;
  related: string[]; // sibling guide slugs
};

export const GUIDES: Guide[] = [
  {
    slug: "covenant-rings",
    title: "Covenant Rings: What a Bishop's Ring Means",
    metaTitle: "Covenant Rings — the Meaning of a Bishop's Ring",
    description:
      "Why bishops, apostles and overseers wear a covenant ring, what the ring signifies, and how to choose one — from Bethany House, Nairobi.",
    category: "Clergy Regalia",
    published: "2026-09-02",
    updated: "2026-09-02",
    minutes: 4,
    intro: [
      "Walk into an ordination or consecration service and you will see it: at the laying on of hands, a ring is placed on the new bishop's finger. It is not jewellery. It is a covenant sign — one of the oldest symbols the church borrowed from Scripture's own vocabulary.",
    ],
    sections: [
      {
        h: "A sign the Bible already spoke",
        p: [
          "Scripture reaches for the ring whenever it wants to speak of covenant, authority and belonging. Pharaoh set his signet ring on Joseph's hand to seal delegated authority (Genesis 41:42). The father in the parable of the prodigal son called for a ring to mark restored sonship (Luke 15:22). And the Lord told Zerubbabel, \"I will make you like my signet ring, for I have chosen you\" (Haggai 2:23).",
          "When the church places a ring on a bishop's hand, it is speaking that same language: this person is sealed to a charge, chosen for a work, bound in covenant.",
        ],
      },
      {
        h: "What the covenant ring signifies",
        p: [
          "The bishop's ring is often described as a wedding band of ministry — the shepherd wedded in fidelity to the church and its people. Where a wedding ring says \"bound to one spouse,\" the episcopal ring says \"bound to one flock.\" It is worn daily, not only in vestments, precisely because the covenant does not end when the service does.",
          "In many traditions the ring is presented at consecration alongside the other marks of the office — the pectoral cross at the chest, the mitre, and the crozier or shepherd's staff in the hand. Each carries its own meaning; the ring's is fidelity.",
        ],
      },
      {
        h: "Who wears one",
        p: [
          "Bishops first — but not bishops only. Apostles, overseers and senior ministers in many African church traditions receive a ring at their elevation, and the apostolic ring has become a dignified emblem of covenant and commission across denominations. What matters is not the tradition's name but what the church intends the ring to say.",
        ],
      },
      {
        h: "Choosing a covenant ring in Kenya",
        p: [
          "Two questions settle most choices. First, the office: a bishopric ring tends toward the classic band or signet form, while apostolic rings often carry a cross or emblem face. Second, the fit: rings are worn daily for years, so sizing matters more than with any other regalia — we stock bishopric rings in all sizes and fit them in store at Moi Avenue, Nairobi.",
          "Both our bishopric and apostolic rings are stocked in Nairobi and delivered across Kenya, East Africa and worldwide on request — many are bought by congregations as consecration gifts, presented on the day itself.",
        ],
      },
    ],
    faqs: [
      {
        q: "What does a bishop's ring symbolise?",
        a: "Covenant fidelity — the shepherd bound to the church and its people, the way a wedding band binds spouses. Scripture uses the ring as a sign of covenant, authority and belonging (Genesis 41:42, Haggai 2:23, Luke 15:22).",
      },
      {
        q: "Who can wear a covenant or apostolic ring?",
        a: "Practice varies by tradition. Bishops receive one at consecration in many churches; apostles, overseers and senior ministers wear apostolic rings in many African traditions. It is the church's intention, not the metal, that gives the ring its meaning.",
      },
      {
        q: "On which finger is a bishop's ring worn?",
        a: "Most commonly the ring finger of the right hand, though practice differs between traditions — follow your church's custom.",
      },
      {
        q: "Can I buy a covenant ring in Nairobi?",
        a: "Yes — Bethany House stocks bishopric rings in all sizes and apostolic rings at Sonalux Building, Moi Avenue, Nairobi CBD, with delivery across Kenya and worldwide on request. Congregations often present one as a consecration gift.",
      },
    ],
    productSlugs: ["bishops-ring", "apostolic-ring", "pectoral-cross-gold", "mitre", "staff-crozier-shepherd-rod"],
    shopHref: "/category/church-gifts",
    related: ["liturgical-colours", "cassock-measurements"],
  },
  {
    slug: "communion-bread-guide",
    title: "Communion Bread: Wafers, Hosts and How Much to Order",
    metaTitle: "Communion Bread in Kenya — Wafers, Hosts & How Much to Order",
    description:
      "Wafers vs hosts, how many pieces to order for your congregation, and how to store communion bread — a practical guide from Bethany House, Nairobi.",
    category: "The Lord's Table",
    published: "2026-09-02",
    updated: "2026-09-02",
    minutes: 4,
    intro: [
      "Every communion Sunday begins with a quiet piece of arithmetic: how much bread does the Table need? Order too little and servers are breaking wafers at the back; order too much and stock sits past its best. This guide settles the maths — and the vocabulary — once.",
    ],
    sections: [
      {
        h: "Wafers and hosts: the same bread, two words",
        p: [
          "Both words describe unleavened communion bread. \"Host\" is the older, more liturgical term (from the Latin hostia, an offering) and usually refers to the plain round white bread of the Eucharist — ours measure about 1⅛ inches across. \"Wafer\" is the everyday word for the same thing. Churches of every tradition use them interchangeably; nothing doctrinal hangs on the word.",
          "What matters practically is the count per pack. We stock communion bread in 200-piece, 500-piece and 1,000-piece packs, so a small fellowship and a congregation of thousands can both order exactly what a season needs.",
        ],
      },
      {
        h: "How much to order",
        p: [
          "The working rule is simple: one piece per communicant, plus a margin of about ten percent for visitors, servers and breakage. A congregation of 150 taking communion monthly uses roughly 165 pieces a service — a 200-piece pack per month, or a 1,000-piece box each half year. A church of 800 is better served by the 1,000-piece pack per service.",
          "If your church serves weekly, multiply accordingly and lean on the larger packs — the price per piece falls as the pack grows, and airtight boxes keep the later packs fresh.",
        ],
      },
      {
        h: "Storing communion bread well",
        p: [
          "Communion wafers keep best sealed, cool and dry. Ours ship in airtight boxes; between services, an airtight bread container keeps opened stock fresh and reverently covered from preparation through distribution, and stacks neatly in the vestry.",
        ],
      },
      {
        h: "The cup beside the bread",
        p: [
          "The Table needs both elements. We stock altar wine and unfermented grape juice side by side, so churches that use wine and churches that use juice are equally served — alongside trays, cups, chalices and refiller bottles that do the pouring without a drop spilled. If you are restocking bread, it is worth checking the cup's supplies in the same order and saving a delivery.",
        ],
      },
    ],
    faqs: [
      {
        q: "What is the difference between a communion wafer and a host?",
        a: "They are the same unleavened bread — \"host\" is the older liturgical term, \"wafer\" the everyday one. Ours are plain round white pieces about 1⅛ inches across, stocked in 200, 500 and 1,000-piece packs.",
      },
      {
        q: "How many communion wafers should our church order?",
        a: "One piece per communicant plus about ten percent margin. A congregation of 150 uses roughly 165 pieces per communion service; a church of 800 is best served by a 1,000-piece pack per service.",
      },
      {
        q: "How should communion bread be stored?",
        a: "Sealed, cool and dry. The packs ship in airtight boxes, and an airtight bread container keeps opened stock fresh between services.",
      },
      {
        q: "Do you deliver communion bread across Kenya?",
        a: "Yes — same-day or next-day within Nairobi for orders placed before 2 PM subject to stock, free in Nairobi CBD above KES 10,000, across Kenya and East Africa in 2–4 working days, and worldwide on request.",
      },
    ],
    productSlugs: ["communion-wafer-bread-200pcs", "holy-communion-bread-1000pcs", "communion-hosts", "bread-container", "altar-wine", "devai"],
    shopHref: "/category/communion-elements",
    related: ["communion-wine-guide", "covenant-rings"],
  },
  {
    slug: "communion-wine-guide",
    title: "Communion Wine or Grape Juice? What Churches Use and Why",
    metaTitle: "Communion Wine vs Grape Juice — What Churches Use & Why",
    description:
      "Why some churches pour communion wine and others grape juice, what Scripture calls the cup, and how much your congregation needs — from Bethany House, Nairobi.",
    category: "The Lord's Table",
    published: "2026-09-03",
    updated: "2026-09-03",
    minutes: 5,
    intro: [
      "Every church that comes to the Lord's Table answers one practical question first: what goes in the cup? Some congregations pour communion wine, others unfermented grape juice — and whoever keeps the vestry cupboard simply needs to know what to buy, how much, and why the traditions differ. Here is the whole picture.",
    ],
    sections: [
      {
        h: "What Scripture calls the cup",
        p: [
          "The Gospels never actually say \"wine\" at the Last Supper. Jesus takes the cup and calls it \"this fruit of the vine\" (Matthew 26:29; Mark 14:25; Luke 22:18) — the produce of the grape, given its meaning by his own words: \"this is my blood of the covenant.\" Paul hands the practice on with the same emphasis: as often as you drink the cup, you proclaim the Lord's death until he comes (1 Corinthians 11:26).",
          "Swahili Scripture reaches for the word divai — the wine of the wedding at Cana — which is why our locally made 1-litre communion wine carries the name Devai. The Bible's weight falls on what the cup proclaims rather than on its chemistry, which is exactly why sincere traditions have room to differ.",
        ],
      },
      {
        h: "Why many churches pour wine",
        p: [
          "Fermented grape wine is the historic practice: from the church's earliest centuries the communion cup held wine, and Catholic, Orthodox and many Anglican congregations require it to this day as continuity with the Last Supper itself. In these churches the wine fills the common cup or the tray of individual cups, as the liturgy directs.",
          "For congregations of this conviction we stock Devai — locally made from grapes in a generous 1-litre bottle that fills tray after tray — classic altar wine, and Efrat communion wine from Israel: wine carried from the land of the Bible, a meaningful pour for the great days such as confirmations, weddings and ordinations.",
        ],
      },
      {
        h: "Why many churches pour grape juice",
        p: [
          "The unfermented cup has its own history. In 1869 a Methodist communion steward named Thomas Welch pasteurised grape juice precisely so his congregation could receive the Lord's Supper without fermented wine, and the practice spread wherever churches shared that conviction. Today many Methodist, Baptist, Pentecostal and independent congregations use unfermented grape juice as a matter of teaching and of pastoral care — for members recovering from alcohol, for children, for whole-congregation participation with a clear conscience.",
          "It is still the fruit of the vine — the same sign, kept within the church's conviction. We stock unfermented grape juice alongside the wines, so either practice is fully served from one order.",
        ],
      },
      {
        h: "So which should your church buy?",
        p: [
          "The honest answer: your church has already answered this. Follow your tradition's teaching — a supplier's job is to serve the conviction, not to settle it, which is why both sides of the cupboard are stocked here. If you are newly responsible for the vestry, ask your bishop, pastor or church council which cup the congregation uses before ordering.",
          "Then match the bottle to the calendar. A 1-litre bottle like Devai is the workhorse for congregations that gather at the Table often; Efrat from Israel suits the great days where the story in the cup matters; and pre-packed communion cups — one sealed serving of bread and 10ml of wine — carry the Table to hospital beds, home visits, crusades and travel.",
        ],
      },
      {
        h: "How much does the cup need?",
        p: [
          "Individual communion cups take 10–15ml each — our refiller bottle dispenses exactly that, cup after cup, without a drop on the linen. The arithmetic follows: a 1-litre bottle fills roughly 66–100 individual cups, so one bottle of Devai can serve a congregation of that size in a single service, and a church of 300 should plan on three to four litres per communion Sunday.",
          "Order the cup's supplies with the bread's and save a delivery: trays, cups, a refiller and both elements travel well in one box — same-day or next-day within Nairobi for orders placed before 2 PM, across Kenya and East Africa in 2–4 working days, and worldwide on request.",
        ],
      },
    ],
    faqs: [
      {
        q: "Does communion wine contain alcohol?",
        a: "Traditionally yes — communion wine is a fermented grape wine, while grape juice is unfermented. Bottles vary, so tell us your church's requirement when ordering and we will point you to the right one, including unfermented options: both are stocked side by side.",
      },
      {
        q: "Is grape juice acceptable for Holy Communion?",
        a: "Many Methodist, Baptist, Pentecostal and independent churches use unfermented grape juice — a practice widespread since the nineteenth century — while Catholic, Orthodox and many Anglican churches require fermented wine. Scripture's own phrase is \"the fruit of the vine\" (Matthew 26:29); follow your church's teaching.",
      },
      {
        q: "How many cups does a bottle of communion wine fill?",
        a: "Individual cups take 10–15ml each, so a 1-litre bottle such as Devai fills roughly 66–100 cups. A refiller bottle makes the pouring fast and spill-free, and pre-packed cups carry bread and 10ml of wine in one sealed serving for visits and crusades.",
      },
      {
        q: "Can I buy communion wine in Nairobi?",
        a: "Yes — Devai (locally made, 1 litre), classic altar wine and Efrat communion wine from Israel, plus unfermented grape juice, at Sonalux Building, Moi Avenue, Nairobi CBD — delivered across Kenya, East Africa and worldwide.",
      },
    ],
    productSlugs: ["devai", "altar-wine", "efrat-communion-wine", "refiller", "pre-packed-communion-cups", "golden-communion-tray"],
    shopHref: "/category/communion-elements",
    related: ["communion-bread-guide", "liturgical-colours"],
  },
  {
    slug: "liturgical-colours",
    title: "Liturgical Colours: What Each Church Season Calls For",
    metaTitle: "Liturgical Colours Explained — Seasons, Meanings & Vestments",
    description:
      "Purple, white, red, green — what each liturgical colour means, when the church year calls for it, and how stoles and vestments carry the seasons.",
    category: "Clergy Apparel",
    published: "2026-09-02",
    updated: "2026-09-02",
    minutes: 5,
    intro: [
      "The church year preaches in colour before a word is spoken. The purple of waiting, the white of feast, the red of fire and blood, the green of growth — a vested minister carries the season into the room. Here is the map, and what it means for the vestry cupboard.",
    ],
    sections: [
      {
        h: "The four working colours",
        p: [
          "Purple (or violet) is the colour of preparation and penitence — Advent and Lent, the church's two seasons of waiting. White (with gold as its festal cousin) belongs to the great feasts: Christmas, Easter, weddings and celebration Sundays. Red is the colour of the Holy Spirit and of the martyrs — Pentecost above all, and in many churches ordinations, confirmations and commissioning services. Green is ordinary time: the long growing season of the church between the feasts.",
          "Traditions differ at the edges — some use blue in Advent, some wear red for Palm Sunday — so the final authority is always your own church's calendar. But the four working colours above serve the wide centre of practice across denominations.",
        ],
      },
      {
        h: "Where the colour actually appears",
        p: [
          "For most ministers the season rides on the stole — the long band of cloth that is the badge of ordained ministry. A full set of stoles in the four colours equips a minister for the whole year. Chasubles and copes carry the colour for celebrants and bishops at the Eucharist and in procession; cassocks beneath them usually stay constant (black most days, white for festal seasons and warm climates).",
        ],
      },
      {
        h: "Building a vestry wardrobe without buying everything",
        p: [
          "Two purchases stretch furthest. First, the double-sided stole: finished in a different liturgical colour on each face, it flips from one season to the next — two seasons, one stole. Second, made-to-measure basics: a well-fitted cassock or alb under changing stoles serves every season, and ours are sewn to your measurements in our Nairobi workshop in 5–7 days.",
          "From there, add by season of heaviest use: a red stole before ordination season, white before Christmas and Easter, and the chasuble when the church's liturgy calls for one.",
        ],
      },
    ],
    faqs: [
      {
        q: "What do the four liturgical colours mean?",
        a: "Purple marks preparation and penitence (Advent, Lent); white and gold mark the great feasts (Christmas, Easter, weddings); red marks the Holy Spirit and the martyrs (Pentecost, ordinations); green marks ordinary time between the feasts.",
      },
      {
        q: "What colour is worn for an ordination?",
        a: "Red is the most common choice, as the colour of the Holy Spirit — though some traditions use white. Follow your church's custom; we tailor ordination gowns and stoles in either.",
      },
      {
        q: "What is a double-sided stole?",
        a: "A stole finished on both faces in two different liturgical colours, worn either way round — one finely made stole that serves two seasons.",
      },
      {
        q: "Can I get stoles and vestments in my church's colours in Kenya?",
        a: "Yes — stoles in the liturgical colours, plus cassocks, gowns and chasubles made to measure in our Nairobi workshop in 5–7 days, delivered across Kenya and worldwide on request.",
      },
    ],
    productSlugs: ["single-sided-stole", "double-sided-stole", "self-print-stoles", "cassock", "white-cassock", "chasuble"],
    shopHref: "/category/clergy-vestments",
    related: ["cassock-measurements", "covenant-rings"],
  },
  {
    slug: "cassock-measurements",
    title: "How to Measure for a Cassock or Gown",
    metaTitle: "How to Measure for a Cassock or Clergy Gown — Made to Fit",
    description:
      "The measurements a tailor actually needs for a cassock, gown or alb, how to take them at home, and how made-to-measure works at Bethany House Nairobi.",
    category: "Clergy Apparel",
    published: "2026-09-02",
    updated: "2026-09-02",
    minutes: 5,
    intro: [
      "A cassock is worn standing, seated, processing and kneeling — for hours at a time, year after year. Ready-made sizes serve many ministers well, but nothing wears like a garment cut to your own numbers. The good news: taking those numbers needs ten minutes, a fabric tape measure and a friend.",
    ],
    sections: [
      {
        h: "The measurements that matter",
        p: [
          "A full-length garment is cut from a handful of numbers: chest (around the fullest part, tape level), waist, shoulder width (seam to seam across the back), sleeve length (shoulder seam to wrist over a slightly bent elbow), neck (where a collar sits — snug, one finger of ease), and the all-important length: from the nape of the neck down to where you want the hem, usually at the ankle. Your height and usual shirt or jacket size help the tailor sanity-check the rest.",
          "Measure over the clothing you will actually wear beneath the garment — a shirt for a cassock, ordinary clothes for a preaching gown — and stand naturally. A friend holding the tape beats guessing in a mirror every time.",
        ],
      },
      {
        h: "How made-to-measure works at Bethany House",
        p: [
          "Every vestment page on this site carries a measurement form — enter your numbers there and they travel with the order. Prefer a human? Send the measurements on WhatsApp (+254 727 891 989) and we will confirm each one back to you, or visit the store on Moi Avenue and be measured in person, Monday to Saturday.",
          "From confirmed measurements, your cassock, gown, alb or chasuble is sewn in our Nairobi workshop and ready in 5–7 working days, then delivered — across Kenya, East Africa, and worldwide on request. Ready-made sizes remain available off the rack when the calendar cannot wait.",
        ],
      },
      {
        h: "Ready-made or made-to-measure?",
        p: [
          "Choose ready-made when the need is this Sunday and your build matches standard sizes. Choose made-to-measure for ordinations and consecrations, for builds that fight standard sizing, and for the garments worn most — the daily cassock above all. The five-piece cassock sets many ministers order for ordination are cut to measure as a matter of course, so the day's photographs never show a borrowed fit.",
        ],
      },
    ],
    faqs: [
      {
        q: "What measurements are needed for a cassock?",
        a: "Chest, waist, shoulder width, sleeve length, neck, and the length from nape to hem — plus your height as a cross-check. Each vestment page on bethanyhouse.co.ke has a form for exactly these numbers.",
      },
      {
        q: "How long does a made-to-measure cassock take in Kenya?",
        a: "5–7 working days from confirmed measurements, sewn in our Nairobi workshop, then delivered anywhere in Kenya and beyond.",
      },
      {
        q: "What if I am not sure I measured correctly?",
        a: "Send your numbers on WhatsApp (+254 727 891 989) and we will guide you through checking each one — or visit the store at Sonalux Building, Moi Avenue for an in-person fitting, Mon–Sat 8:00 AM–5:00 PM.",
      },
      {
        q: "Can I order a made-to-measure vestment from outside Kenya?",
        a: "Yes — the measurement form works from anywhere, tailoring takes the same 5–7 days, and we quote shipping to your city before dispatch.",
      },
    ],
    productSlugs: ["cassock", "cassock-set", "preaching-gown-843glv9rc", "ordination-gown", "alb", "surplice"],
    shopHref: "/category/clergy-vestments",
    related: ["liturgical-colours", "covenant-rings"],
  },
];

const BY_SLUG = new Map(GUIDES.map((g) => [g.slug, g]));
export const guideBySlug = (slug: string) => BY_SLUG.get(slug);
