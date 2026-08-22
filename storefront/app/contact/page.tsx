import Link from "next/link";
import type { Metadata } from "next";
import Crumbs from "@/components/Crumbs";
import JsonLd from "@/components/JsonLd";
import { abs, breadcrumbJsonLd } from "@/lib/seo";
import { SITE } from "@/lib/site";

/* Contact page — every channel on one page, with the store's NAP
   (name / address / phone) exactly as it appears in the Organization
   schema, so engines see one consistent identity. */

export const metadata: Metadata = {
  title: "Contact Us",
  description: `WhatsApp, call, email or visit ${SITE.name} — ${SITE.address}, Nairobi CBD. Open ${SITE.hours}. ${SITE.phone}.`,
  alternates: { canonical: "/contact" },
  openGraph: {
    type: "website",
    title: `Contact Us | ${SITE.name}`,
    description: `WhatsApp ${SITE.phone}, visit ${SITE.address}, Nairobi — or email ${SITE.email}.`,
    url: `${SITE.url}/contact`,
  },
};

export default function ContactPage() {
  return (
    <main className="wrap cat-page">
      <JsonLd data={breadcrumbJsonLd([{ name: "Home", path: "/" }, { name: "Contact Us" }])} />
      <JsonLd data={{
        "@context": "https://schema.org",
        "@type": "ContactPage",
        name: `Contact ${SITE.name}`,
        url: abs("/contact"),
        mainEntity: { "@id": `${SITE.url}/#store` },
      }} />

      <Crumbs items={[{ label: "Home", href: "/" }, { label: "Contact Us" }]} />

      <header className="cat-head">
        <h1>Contact Bethany House</h1>
        <p>
          Real people, in a real store — {SITE.hours} (East Africa Time).
          WhatsApp is the fastest way to reach us: many churches simply send a
          photo or a list of what they need and get a quote back.
        </p>
        <div className="cat-facts">
          <span>{SITE.payments}</span>
          <span>{SITE.deliveryShort}</span>
          <span>Kenya · East Africa · worldwide on request</span>
        </div>
      </header>

      <section className="contact-grid">
        <div className="contact-card">
          <h2>WhatsApp — fastest</h2>
          <a className="big" href={SITE.whatsapp} target="_blank" rel="noopener">{SITE.phone}</a>
          <p>Send your list — items, sizes, quantities — and we reply with a quote and a delivery time.</p>
        </div>
        <div className="contact-card">
          <h2>Call us</h2>
          <div className="lines">
            <a href={SITE.phoneHref}>{SITE.phone}</a>
            <a href={SITE.phoneHref2}>{SITE.phone2}</a>
          </div>
          <p>{SITE.hours}. Parish accounts, quotations and engraving requests welcome.</p>
        </div>
        <div className="contact-card">
          <h2>Email</h2>
          <a className="big" href={`mailto:${SITE.email}`}>{SITE.email}</a>
          <p>For written quotations, parish and diocese accounts, and anything that needs paperwork.</p>
        </div>
        <div className="contact-card">
          <h2>Visit the store</h2>
          <p><b>{SITE.address}</b><br />{SITE.city}</p>
          <p>{SITE.landmarks}</p>
          <p>{SITE.hours}</p>
          <a className="big" href={SITE.mapsUrl} target="_blank" rel="noopener">Open in Google Maps ›</a>
        </div>
      </section>

      <section className="about-story">
        <h2>For parishes &amp; dioceses</h2>
        <p>
          We supply churches on recurring orders — communion elements restocked
          each season, vestments for new ordinands, engraved ware for
          anniversaries. Call or WhatsApp {SITE.phone} and ask for a parish
          account, and we will prepare a formal quotation for your committee.
        </p>
      </section>

      <section className="cat-related">
        <span>Quick answers:</span>
        <Link href="/faq">FAQs</Link>
        <Link href="/international">International Orders</Link>
        <Link href="/policies/shipping">Shipping &amp; Delivery</Link>
        <Link href="/policies/returns">Returns &amp; Refunds</Link>
        <Link href="/about">About Bethany House</Link>
      </section>
    </main>
  );
}
