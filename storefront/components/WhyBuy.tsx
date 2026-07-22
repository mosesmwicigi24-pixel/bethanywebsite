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
    { icon: "✍️", title: "Free engraving", text: "Parish names, dedications and anniversaries — engraved free on communion ware, etched beneath the base." },
    { icon: "🚚", title: "Delivery or pickup — your choice", text: `Same-day Nairobi delivery for orders before 2 PM, or collect from ${SITE.address}. ${SITE.hours}.` },
    { icon: "🪡", title: "With you after the sale", text: "Repairs, alterations and re-polishing — vestments and ware are maintained for the life of their service." },
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
