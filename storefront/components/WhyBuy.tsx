import { SITE } from "@/lib/site";

/** Funnel-positioned purchase-logistics cards (the Apple "best place to
    buy" pattern) — answers last-mile hesitations near the buy decision.
    Every service listed is real: parish accounts, engraving, delivery
    options, and after-sales are things Bethany House actually does. */
export default function WhyBuy() {
  return (
    <section className="why whybuy">
      <div className="wrap">
        <div className="section-head"><h2>The best place to buy for your church.</h2></div>
        <div className="grid">
          <article className="pillar">
            <div className="ico">⛪</div>
            <h3>Parish &amp; diocese accounts</h3>
            <p>Quantity quotes, consolidated delivery and invoicing for churches and institutions. Call {SITE.phone} to open one.</p>
          </article>
          <article className="pillar">
            <div className="ico">✍️</div>
            <h3>Free engraving</h3>
            <p>Parish names, dedications and anniversaries — engraved free on communion ware, etched beneath the base.</p>
          </article>
          <article className="pillar">
            <div className="ico">🚚</div>
            <h3>Delivery or pickup — your choice</h3>
            <p>Same-day Nairobi delivery for orders before 2 PM, or collect from {SITE.address}. {SITE.hours}.</p>
          </article>
          <article className="pillar">
            <div className="ico">🪡</div>
            <h3>With you after the sale</h3>
            <p>Repairs, alterations and re-polishing — vestments and ware are maintained for the life of their service.</p>
          </article>
        </div>
      </div>
    </section>
  );
}
