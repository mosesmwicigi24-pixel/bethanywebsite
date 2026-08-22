import Link from "next/link";
import type { Metadata } from "next";
import Crumbs from "@/components/Crumbs";
import JsonLd from "@/components/JsonLd";
import Reveal from "@/components/Reveal";
import { abs, breadcrumbJsonLd, faqPageJsonLd } from "@/lib/seo";
import { SITE } from "@/lib/site";

/* International orders hub — the page behind "worldwide on request".
   Written for two readers: clergy abroad ordering for their own church,
   and diaspora buyers ordering for a church back home in Kenya. Every
   commitment restates the Shipping & Delivery policy (lib/legal.ts) or
   lib/site.ts — quotes before dispatch, KES charging with USD/ZMW display,
   2–4 working days across East Africa, 5–7 day tailoring. Nothing here
   promises a carrier, a flat rate or a delivery time we haven't published. */

const FAQS: { q: string; a: string }[] = [
  {
    q: "Do you ship to my country?",
    a: "We ship worldwide from Nairobi — every order is quoted for its destination. Send your city and country with your list and we reply with the shipping cost and the estimated delivery time.",
  },
  {
    q: "How long does international delivery take?",
    a: "It depends on the destination and the service chosen, so we quote the estimated delivery time together with the freight cost before dispatch — you confirm both before anything ships.",
  },
  {
    q: "Who pays customs duties and import taxes?",
    a: "Freight and any duties are quoted before dispatch. Import taxes charged by the destination country are the recipient's responsibility, as set out in our Shipping & Delivery policy.",
  },
  {
    q: "How do I pay from outside Kenya?",
    a: "Visa or Mastercard on the website, or M-Pesa if you have it. Orders are charged in Kenyan Shillings; product pages can display prices in USD or ZMW for reference.",
  },
  {
    q: "Can vestments be made to measure from abroad?",
    a: "Yes — every vestment page has a measurement form, and if you are unsure we will guide you through measuring on WhatsApp. Tailoring takes 5–7 working days in our Nairobi workshop, and the finished garment ships with the rest of your order.",
  },
  {
    q: "I live abroad — can I order for a church in Kenya?",
    a: "Yes — pay by card from wherever you are and we deliver to the church at home: free in Nairobi CBD for orders above KES 10,000, and by trusted courier countrywide and across East Africa, typically 2–4 working days.",
  },
];

export const metadata: Metadata = {
  title: "International Orders",
  description:
    "Communion supplies, clergy vestments and church gifts shipped worldwide from Nairobi, Kenya. Freight, duties and delivery time quoted before dispatch — pay by Visa, Mastercard or M-Pesa. Diaspora orders delivered to churches in Kenya.",
  alternates: { canonical: "/international" },
  openGraph: {
    type: "website",
    title: `International Orders | ${SITE.name}`,
    description:
      "Church supplies shipped worldwide from Nairobi — quoted for your destination before dispatch.",
    url: `${SITE.url}/international`,
  },
};

export default function InternationalPage() {
  return (
    <main className="wrap cat-page">
      <JsonLd data={breadcrumbJsonLd([{ name: "Home", path: "/" }, { name: "International Orders" }])} />
      <JsonLd data={faqPageJsonLd(FAQS)} />
      <JsonLd data={{
        "@context": "https://schema.org",
        "@type": "WebPage",
        name: `International Orders | ${SITE.name}`,
        url: abs("/international"),
        about: { "@id": `${SITE.url}/#organization` },
      }} />

      <Crumbs items={[{ label: "Home", href: "/" }, { label: "International Orders" }]} />

      <header className="cat-head">
        <h1>We ship worldwide — from Nairobi to your church</h1>
        <p>
          Bethany House supplies churches far beyond Kenya. Communion ware,
          made-to-measure vestments, wafers and church gifts leave our Moi
          Avenue store for congregations across East Africa and, on request,
          anywhere in the world.
        </p>
        <p>
          If you serve a church abroad — or you are in the diaspora buying for
          a church back home — this is how it works.
        </p>
        <div className="cat-facts">
          <span>Ships worldwide from Nairobi</span>
          <span>Freight &amp; duties quoted before dispatch</span>
          <span>Visa · Mastercard · M-Pesa</span>
          <span>East Africa in 2–4 working days</span>
        </div>
      </header>

      <Reveal as="section" className="intl-steps">
        <div className="intl-step">
          <h3>Send your list</h3>
          <p>WhatsApp {SITE.phone} or email {SITE.email} with the items, quantities and sizes you need — and your city and country.</p>
        </div>
        <div className="intl-step">
          <h3>Get the full quote first</h3>
          <p>Items, freight and any duties, plus the estimated delivery time to your destination. You confirm before we dispatch — no surprises.</p>
        </div>
        <div className="intl-step">
          <h3>Pay from anywhere</h3>
          <p>Visa or Mastercard, or M-Pesa. Orders are charged in Kenyan Shillings; prices can be viewed in USD or ZMW for reference.</p>
        </div>
        <div className="intl-step">
          <h3>We dispatch from Nairobi</h3>
          <p>Your order leaves our store, and we stay reachable on WhatsApp until it is in your hands.</p>
        </div>
      </Reveal>

      <section className="about-story">
        <h2>What churches order from abroad</h2>
        <p>
          <b>Communion ware and elements.</b> Chalices, trays, cups, ciboria
          and wafers travel well and make up much of what we send out of
          Kenya. A few items — altar wine and other liquids — depend on the
          destination country&rsquo;s rules, so when we quote, we confirm
          exactly what can ship to you.
        </p>
        <p>
          <b>Vestments, made to measure from anywhere.</b> Every vestment page
          has a measurement form; if you are unsure, we guide you through
          measuring on WhatsApp. Your cassock, gown or chasuble is sewn in our
          Nairobi workshop in 5–7 working days, then shipped with the rest of
          your order.
        </p>
        <p>
          <b>Gifts for a church back home.</b> Many diaspora customers pay by
          card from abroad and have us deliver in Kenya — an engraved chalice
          for an ordination, trays for a parish anniversary. Delivery is free
          in Nairobi CBD for orders above KES 10,000, and trusted couriers
          reach the rest of Kenya and East Africa — Uganda, Tanzania, Rwanda,
          Zambia and beyond — typically in 2–4 working days.
        </p>
      </section>

      <section className="faqs cat-faqs">
        <h4>International orders — common questions</h4>
        {FAQS.map((f) => (
          <details key={f.q}>
            <summary>{f.q}</summary>
            <p>{f.a}</p>
          </details>
        ))}
      </section>

      <section className="cat-related">
        <span>See also:</span>
        <Link href="/policies/shipping">Shipping &amp; Delivery policy</Link>
        <Link href="/shop">Browse the catalog</Link>
        <Link href="/faq">All FAQs</Link>
        <Link href="/contact">Contact Us</Link>
      </section>

      <section className="cat-cta">
        <div>
          <b>Send your city and country — get a quote before anything ships.</b>
          <p>{SITE.shipWorldwide}</p>
        </div>
        <div className="cta-row">
          <a className="pill pill-gold" href={SITE.whatsapp} target="_blank" rel="noopener">WhatsApp {SITE.phone}</a>
          <a className="pill pill-ghost" href={`mailto:${SITE.email}`}>Email {SITE.email}</a>
        </div>
      </section>
    </main>
  );
}
