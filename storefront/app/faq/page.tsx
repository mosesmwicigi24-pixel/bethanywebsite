import Link from "next/link";
import type { Metadata } from "next";
import Crumbs from "@/components/Crumbs";
import JsonLd from "@/components/JsonLd";
import { breadcrumbJsonLd, faqPageJsonLd } from "@/lib/seo";
import { SITE } from "@/lib/site";

/* Sitewide FAQ — the trust page behind the footer/nav "Support" links.
   Every answer restates a commitment already published elsewhere on the
   site (lib/site.ts, lib/legal.ts policies, category/product copy) so the
   page can never promise something the store hasn't. The FAQPage JSON-LD
   below mirrors exactly the questions rendered here, per Google policy. */

const GROUPS: { h: string; items: { q: string; a: string }[] }[] = [
  {
    h: "Ordering & payment",
    items: [
      {
        q: "How do I place an order?",
        a: "Order on the website — add to cart and check out — or send your list on WhatsApp (+254 727 891 989): many churches simply message the items, sizes and quantities they need and receive a quote back. You can also visit the store on Moi Avenue and order over the counter.",
      },
      {
        q: "Which payment methods do you accept?",
        a: "M-Pesa, Visa, Mastercard and cash on delivery where offered. Orders are charged in Kenyan Shillings; every product page can also show prices in USD or ZMW for reference.",
      },
      {
        q: "Can our parish or diocese open a standing account?",
        a: "Yes. We supply parishes and dioceses on recurring orders and prepare formal quotations for church committees — call or WhatsApp +254 727 891 989 and ask for a parish account.",
      },
    ],
  },
  {
    h: "Delivery & shipping",
    items: [
      {
        q: "How fast is delivery in Nairobi?",
        a: "Same-day or next-day within Nairobi for orders placed before 2 PM, subject to stock. Delivery is free in Nairobi CBD for orders above KES 10,000; a small fee applies below that or outside the CBD, confirmed at dispatch.",
      },
      {
        q: "Do you deliver across Kenya and East Africa?",
        a: "Yes — countrywide and across East Africa via trusted couriers, typically 2–4 working days depending on destination, with the fee quoted before dispatch.",
      },
      {
        q: "Do you ship internationally?",
        a: "We ship worldwide from Nairobi. Share your city and country and we will quote the shipping cost and estimated delivery time. Freight and any duties are quoted before dispatch; import taxes in the destination country are the recipient's responsibility.",
      },
      {
        q: "How do I track my order?",
        a: "Open the My Orders page on this site with the order number from your confirmation, or message us on WhatsApp and we will check for you.",
      },
    ],
  },
  {
    h: "Made-to-measure & engraving",
    items: [
      {
        q: "How long does a made-to-measure cassock or gown take?",
        a: "5–7 working days from confirmed measurements, sewn in our Nairobi workshop, then delivered like any other order. Ready-made sizes ship immediately while stocks last.",
      },
      {
        q: "How do I send my measurements?",
        a: "Each vestment's product page has a measurement form — enter your numbers there, or pick a ready-made size instead. If you are unsure how to measure, WhatsApp us and we will guide you through it.",
      },
      {
        q: "Do you engrave?",
        a: "Yes — engraving on communion ware is free: parish names, dedications and anniversaries, etched beneath the base so the piece itself stays unmarked.",
      },
      {
        q: "Do you stock both communion wine and non-alcoholic grape juice?",
        a: "Yes, both — churches that use wine and churches that use unfermented grape juice are equally served.",
      },
    ],
  },
  {
    h: "The store & returns",
    items: [
      {
        q: "Where is the store?",
        a: "Sonalux Building, 7th Floor, Room 18, Moi Avenue, Nairobi CBD — near Nairobi Sports House, opposite Family Bank. Open Mon–Sat, 8:00 AM – 5:00 PM.",
      },
      {
        q: "What is your returns policy?",
        a: "Unused ready-made goods in their original condition and packaging may be returned within 7 days of delivery for an exchange or refund of the item price. If anything arrives damaged, faulty or incorrect, tell us within 48 hours with a photo and we will arrange a replacement, repair or full refund including return delivery.",
      },
      {
        q: "How do I reach a real person?",
        a: "WhatsApp or call +254 727 891 989 or +254 785 490 805, email info@bethanyhouse.co.ke, or visit the store — Mon–Sat, 8:00 AM – 5:00 PM. Neema, the site's chat assistant, can also help any time.",
      },
    ],
  },
];

export const metadata: Metadata = {
  title: "FAQs",
  description:
    "Answers about ordering, M-Pesa and card payment, Nairobi and Kenya-wide delivery, worldwide shipping, made-to-measure vestments, free engraving and returns at Bethany House.",
  alternates: { canonical: "/faq" },
  openGraph: {
    type: "website",
    title: `FAQs | ${SITE.name}`,
    description: "Ordering, delivery, made-to-measure vestments, engraving and returns — answered.",
    url: `${SITE.url}/faq`,
  },
};

export default function FaqPage() {
  return (
    <main className="wrap cat-page">
      <JsonLd data={breadcrumbJsonLd([{ name: "Home", path: "/" }, { name: "FAQs" }])} />
      <JsonLd data={faqPageJsonLd(GROUPS.flatMap((g) => g.items))} />

      <Crumbs items={[{ label: "Home", href: "/" }, { label: "FAQs" }]} />

      <header className="cat-head">
        <h1>Frequently asked questions</h1>
        <p>
          Straight answers about ordering, delivery, made-to-measure vestments
          and the store. If your question is not here, WhatsApp {SITE.phone}
          {" "}and a person will answer.
        </p>
      </header>

      {GROUPS.map((g) => (
        <section key={g.h} className="faqs faq-group">
          <h2>{g.h}</h2>
          {g.items.map((f) => (
            <details key={f.q}>
              <summary>{f.q}</summary>
              <p>{f.a}</p>
            </details>
          ))}
        </section>
      ))}

      <section className="cat-related">
        <span>Full policies:</span>
        <Link href="/guides">Guides</Link>
        <Link href="/policies/shipping">Shipping &amp; Delivery</Link>
        <Link href="/policies/returns">Returns &amp; Refunds</Link>
        <Link href="/policies/terms">Terms of Service</Link>
        <Link href="/international">International Orders</Link>
        <Link href="/contact">Contact Us</Link>
      </section>

      <section className="cat-cta">
        <div>
          <b>Still stuck? Ask a person.</b>
          <p>{SITE.hours}. Or visit us — {SITE.address}, Nairobi.</p>
        </div>
        <div className="cta-row">
          <a className="pill pill-gold" href={SITE.whatsapp} target="_blank" rel="noopener">WhatsApp us</a>
          <a className="pill pill-ghost" href={SITE.phoneHref}>Call {SITE.phone}</a>
        </div>
      </section>
    </main>
  );
}
