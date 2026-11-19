import { SITE } from "@/lib/site";

export interface Pillar {
  icon: string;
  title: string;
  text: string;
}

/** Funnel-positioned purchase-logistics cards (the Apple "best place to buy"
    pattern). Uses the four real Bethany House services by default, or the
    per-product pillars managed in the CMS when provided. */
export default function WhyBuy({ pillars }: { pillars?: Pillar[] }) {
  const items: Pillar[] = pillars && pillars.length ? pillars : [
    { icon: "⛪", title: "Parish & diocese accounts", text: `Quantity quotes, consolidated delivery and invoicing for churches and institutions. Call ${SITE.phone} to open one.` },
    { icon: "💳", title: "Pay the way your church prefers", text: `${SITE.payments.replace(/ · /g, ", ")} — the choice is yours at checkout.` },
    { icon: "🚚", title: "Delivery or pickup — your choice", text: `Same-day Nairobi delivery for orders before 2 PM, or collect from ${SITE.address}. ${SITE.hours}.` },
    { icon: "🪡", title: "With you after the sale", text: "Repairs or alterations on what you bought from us, within 14 days of receiving it and subject to assessment. Faulty items are replaced or refunded." },
  ];

  return (
    <section className="why whybuy">
      <div className="wrap">
        <div className="section-head"><h2>The best place to buy for your church.</h2></div>
        <div className="grid">
          {items.map((it, i) => (
            <article className="pillar" key={i}>
              <div className="ico">{it.icon}</div>
              <h3>{it.title}</h3>
              <p>{it.text}</p>
            </article>
          ))}
        </div>
      </div>
    </section>
  );
}
