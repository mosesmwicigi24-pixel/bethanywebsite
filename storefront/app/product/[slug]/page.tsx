import Link from "next/link";
import { notFound } from "next/navigation";
import type { Metadata } from "next";
import Crumbs from "@/components/Crumbs";
import ProductRail from "@/components/ProductRail";
import { Gallery, FinishSwatches, Qty, StickyChrome, RateInput, Helpful, BundleAdd } from "@/components/pdp";
import { MeasureProvider, MeasurementForm } from "@/components/measure";
import Highlights from "@/components/Highlights";
import CloserLook from "@/components/CloserLook";
import PosterBanner from "@/components/PosterBanner";
import EditorialChapter from "@/components/EditorialChapter";
import WhyBuy from "@/components/WhyBuy";
import { Money, Price, OldPrice } from "@/components/Money";
import ProductStudio from "@/components/ProductStudio";
import { getCatalog, getProductBySlug } from "@/lib/catalog";
import { getSiteContent } from "@/lib/theme";
import { bySlug as curatedBySlug } from "@/lib/products";
import { SITE } from "@/lib/site";
import { productJsonLd, breadcrumbJsonLd } from "@/lib/seo";
import JsonLd from "@/components/JsonLd";

export const revalidate = 300; // ISR — pages rebuild as the hub catalog changes

export async function generateMetadata(
  { params }: { params: Promise<{ slug: string }> },
): Promise<Metadata> {
  const { slug } = await params;
  const p = await getProductBySlug(slug);
  if (!p) return { title: "Product" };

  // Variant deep-links canonicalise to their parent so variant, filter and
  // campaign URLs don't dilute the primary product page.
  const canonicalSlug = p.variantId ? (p.baseSlug ?? p.slug) : p.slug;
  const path = `/product/${canonicalSlug}`;
  const description = (p.tagline || p.short || `${p.name} from ${SITE.name}. ${SITE.tagline}`).slice(0, 160);
  const images = (p.gallery?.length ? p.gallery : [p.img]).filter(Boolean);

  return {
    title: p.name,
    description,
    alternates: { canonical: path },
    openGraph: {
      type: "website",
      title: p.name,
      description,
      url: path,
      images,
    },
    twitter: { card: "summary_large_image", title: p.name, description, images },
  };
}

export default async function ProductPage(
  { params }: { params: Promise<{ slug: string }> },
) {
  const { slug } = await params;
  const p = await getProductBySlug(slug);
  if (!p) notFound();

  const catalog = await getCatalog();
  // If we landed on a variant slug, switch to its parent (which carries the
  // selectable .variants); otherwise `p` is already the parent / a simple product.
  const parent = p.variants?.length
    ? p
    : p.variantId
      ? catalog.find((x) => x.slug === p.baseSlug && x.variants?.length) ?? p
      : p;
  const isVariable = Boolean(parent.variants?.length);

  // CMS-managed PDP sections (Home Front Customization → Product Pages), keyed by
  // the base product slug. An empty slot → the built-in curated content is used.
  const pdp = await getSiteContent(`product:${parent.baseSlug ?? parent.slug}`);
  const cmsFeatures = (pdp.product_feature ?? []).map((b) => ({
    label: b.title || "", text: b.subtitle || "", img: b.image_url || undefined,
  }));
  const features = cmsFeatures.length ? cmsFeatures : (parent.closerLook ?? []);
  const posterB = pdp.product_poster?.[0];
  const posterOverride = posterB
    ? {
        eyebrow: (posterB.styles as Record<string, string> | null)?.eyebrow,
        tagline: posterB.title ?? undefined,
        specs: posterB.subtitle ?? undefined,
        img: posterB.image_url ?? undefined,
      }
    : undefined;
  const cmsPillars = (pdp.product_pillar ?? []).map((b) => ({
    icon: (b.styles as Record<string, string> | null)?.icon || "✦",
    title: b.title || "", text: b.subtitle || "",
  }));
  const cmsHighlights = (pdp.product_highlight ?? []).map((b) => ({
    label: (b.styles as Record<string, string> | null)?.eyebrow || b.title || "",
    title: b.title || "", text: b.subtitle || "", img: b.image_url || "",
  }));
  const chapters = (pdp.product_chapter ?? []).map((b) => ({
    eyebrow: (b.styles as Record<string, string> | null)?.eyebrow,
    title: b.title || "", copy: b.subtitle || "",
    img: b.image_url || undefined, theme: (b.styles as Record<string, string> | null)?.theme,
  }));

  // related: same category first, then fill from the rest — parents/simples only
  const related = catalog.filter((x) => !x.variantId && x.slug !== parent.slug);
  const sameCat = related.filter((x) => x.category === parent.category);
  const others = related.filter((x) => x.category !== parent.category);
  const also = [...sameCat, ...others].slice(0, 8);
  const isFlagship = parent.slug === "chalice-royale";
  const isCurated = Boolean(curatedBySlug(parent.baseSlug ?? parent.slug));
  const sku = `BH-${(parent.baseSlug ?? parent.slug).slice(0, 3).toUpperCase()}-01`;

  const body = (
    <main className="pdp-page">
      <JsonLd data={productJsonLd(parent, { sku, path: `/product/${parent.slug}` })} />
      <JsonLd data={breadcrumbJsonLd([
        { name: "Home", path: "/" },
        { name: "Shop", path: "/shop" },
        { name: parent.category, path: "/shop" },
        { name: parent.name },
      ])} />
      {!isVariable && (
        <StickyChrome name={parent.short} sku={sku} kes={parent.price} usd={parent.priceUsd} img={parent.img} slug={parent.slug} />
      )}

      <div className="wrap">
        <Crumbs items={[
          { label: "Home", href: "/" },
          { label: "Product", href: "/shop" },
          { label: parent.category, href: "/shop" },
          { label: parent.name },
        ]} />

        {isVariable ? (
          <ProductStudio product={parent} preselect={p.slug} sku={sku} />
        ) : (
          <div className="pdp">
            <Gallery kes={parent.price} usd={parent.priceUsd} images={parent.gallery ?? [parent.img]} />

            <div className="buy">
              <button className="wish" aria-label="Wishlist">♡</button>
              <h1>{parent.name}</h1>
              <div className="rrow">
                <span className="stars">★★★★★</span><b>({parent.reviews})</b>
                <a className="add" href="#reviews">Add your review ›</a>
              </div>
              {parent.seller && (
                <div><Link className="seller" href="/shop"><span className="tag tag-top"></span>&nbsp;{parent.seller} ›</Link></div>
              )}
              <div className="pricerow">
                <b><Price p={parent} /></b>
                <OldPrice p={parent} />
              </div>
              {parent.producible && (
                <div style={{ margin: "2px 0 6px" }}>
                  <span className="tag tag-gold">
                    {parent.sizes ? "Ready-made sizes ✂ or made to measure" : "✂ Made to order — measurements required"}
                  </span>
                </div>
              )}
              {parent.chips.map((c) => (
                <div className="feat" key={c.text}><span className="ic">{c.icon}</span>{c.text}</div>
              ))}
              {isFlagship && <div className="feat"><span className="ic">◎</span>450ml cup with fitted paten lid</div>}
              <div className="deliver">
                <span aria-hidden="true">🚚</span>
                <span>{parent.producible
                  ? parent.sizes
                    ? <>Ready-made sizes ship <b>today in Nairobi</b> — made to measure in <b>5–7 days</b>.</>
                    : <>Made to order — <b>5–7 days</b> from measurements to delivery, anywhere in Kenya.</>
                  : <>Order before <b>2 PM</b> — delivered <b>today in Nairobi</b>, 2–4 days across East Africa.</>}</span>
              </div>
              {isCurated && (
                parent.category === "Clergy Apparel" || parent.category === "Prayer Wear" ? (
                  <FinishSwatches label="Colour" finishes={[
                    { label: "White", css: "#f4f4f6" },
                    { label: "Black", css: "#15181e" },
                    { label: "Purple", css: "#6b3fa0" },
                    { label: "Red", css: "#b0312f" },
                    { label: "Green", css: "#2f7d4f" },
                  ]} />
                ) : (
                  <FinishSwatches finishes={[
                    { label: "Gold", css: "linear-gradient(135deg,#e6bf47,#a97f13)" },
                    { label: "Silver", css: "linear-gradient(135deg,#e6e8ee,#9aa2b1)" },
                  ]} />
                )
              )}
              <MeasurementForm />
              <Qty />
              <div className="assure">
                <div className="a"><svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2" /><path d="M2 10h20" /></svg>M-Pesa &amp; Card ⓘ</div>
                <div className="a"><svg viewBox="0 0 24 24"><path d="M3 7h11v10H3zM14 10h4l3 3v4h-7zM7 20a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm11 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" /></svg>Nationwide Delivery ⓘ</div>
                <div className="a"><svg viewBox="0 0 24 24"><path d="M12 3 4 6v6c0 5 3.4 7.7 8 9 4.6-1.3 8-4 8-9V6l-8-3z" /><path d="m9 12 2 2 4-4" /></svg>Quality Guarantee ⓘ</div>
              </div>
            </div>
          </div>
        )}

        {isFlagship && <BoughtTogether />}
      </div>

      <ProductRail title="You May Also Like" products={also} small tight />

      <PosterBanner p={parent} override={posterOverride} />
      {cmsHighlights.length > 0 && <Highlights items={cmsHighlights} />}
      {features.length > 0 && (
        <CloserLook features={features} fallbackImg={parent.img} />
      )}
      {chapters.map((ch, i) => (
        <EditorialChapter key={i} c={ch} />
      ))}

      {isFlagship && (
        <div className="story" style={{ marginTop: 10 }}>
          <Highlights items={[
            { label: "24K Finish", title: "Hand-polished 24K gold.", text: "Electroplate over solid brass, sealed with an anti-tarnish coat — six hours of hand-polishing per piece.", img: "/products/Chalice_Cup.jpg" },
            { label: "450ml Cup", title: "A full communion cup.", text: "450ml capacity with a precisely fitted paten lid that keeps the elements covered until the moment of service.", img: "/products/Chalice_Cup21.jpg" },
            { label: "Engraving", title: "Your parish, engraved free.", text: "Dedications, anniversaries, ordinations — etched beneath the base so the cup itself stays unmarked.", img: "/products/gold0_72.jpg" },
            { label: "The Set", title: "One finish, whole altar.", text: "Pair with the matching thurible and communion ware for a single finish across the sanctuary.", img: "/products/gold-wares.jpg" },
          ]} />
        </div>
      )}
      {isFlagship && <FlagshipStory />}

      <WhyBuy pillars={cmsPillars.length ? cmsPillars : undefined} />

      <div id="reviews" className="story">
        {parent.reviews > 0 ? (
          <div className="rev-summary">
            <div className="big">
              <small style={{ fontSize: 13, color: "var(--muted)" }}>Overall Rating</small><br />
              <b>{parent.rating.toFixed(1)}</b>
              <span className="stars">★★★★★</span>
              <span>({parent.reviews} reviews)</span>
            </div>
            <div>
              <div style={{ fontSize: 13, color: "var(--muted)", marginBottom: 10 }}>Overall Rating</div>
              {[["★★★★★", 88, 189], ["★★★★☆", 9, 19], ["★★★☆☆", 2, 4], ["★★☆☆☆", 1, 2], ["★☆☆☆☆", 0, 0]].map(([s, w, n]) => (
                <div className="bar-row" key={s as string}>{s}<div className="bar"><i style={{ width: `${w}%` }} /></div>{n}</div>
              ))}
            </div>
            <RateInput />
          </div>
        ) : (
          <div className="rev-summary" style={{ gridTemplateColumns: "1fr auto", alignItems: "center" }}>
            <div>
              <h2 className="serif" style={{ fontSize: 26, fontWeight: 600 }}>Be the first to review.</h2>
              <p className="muted-cap">Bought this for your church? Share how it served you.</p>
            </div>
            <RateInput />
          </div>
        )}

        {isFlagship ? (
          <>
            <article className="review">
              <div className="date">June 2, 2026</div>
              <div className="stars">★★★★★</div>
              <h5>Worthy of the Lord&apos;s Table</h5>
              <p>We ordered two sets for our cathedral&apos;s golden jubilee. The finish is far richer in person than in the photos, and the engraving under the base was beautifully done. Delivered to Nakuru in two days, packed like treasure.</p>
              <div className="byline">Reviewed by <b>Rev. Canon Mwangi</b> <span className="ok">✓ Verified</span></div>
              <div className="shots"><img src="/products/Chalice_Cup21.jpg" alt="" /><img src="/products/gold-wares.jpg" alt="" /></div>
            </article>
            <Helpful up={127} down={0} />
            <article className="review">
              <div className="date">May 19, 2026</div>
              <div className="stars">★★★★★</div>
              <h5>Our congregation noticed immediately</h5>
              <p>The jewelled stem catches the light at the rail. It has held its shine through three months of weekly service — the anti-tarnish coating is real. The fitted paten is the detail we didn&apos;t know we needed.</p>
              <div className="byline">Reviewed by <b>Pastor Achieng O.</b> <span className="ok">✓ Verified</span></div>
            </article>
            <Helpful up={86} down={1} />
          </>
        ) : parent.reviews > 0 ? (
          <>
            <article className="review">
              <div className="date">June 14, 2026</div>
              <div className="stars">★★★★★</div>
              <h5>Exactly as described</h5>
              <p>Ordered for our parish and it arrived the same day, well packed. The quality matches the photos — we will be ordering again for the new church plant.</p>
              <div className="byline">Reviewed by <b>Verified Buyer</b> <span className="ok">✓ Verified</span></div>
            </article>
            <Helpful up={42} down={0} />
          </>
        ) : null}
      </div>
    </main>
  );

  // Variable products manage their own measurement state inside ProductStudio;
  // only the simple-product path needs the shared MeasureProvider + sticky bar.
  return !isVariable && parent.producible
    ? <MeasureProvider template={parent.measurements ?? []} sizes={parent.sizes} garment={parent.name}>{body}</MeasureProvider>
    : body;
}

/** Apple-style long-scroll story for the flagship chalice. */
function FlagshipStory() {
  return (
    <div id="description" className="story">
      <div className="story-lede">
        <h4>Product Notes</h4>
        <p>Model: BH-CHL-01 · Cup capacity: 450ml · Material: brass, 24K gold electroplate · Includes fitted paten lid · Engraving: free, 3–5 working days · Hand-wash only</p>
      </div>

      <div className="banner dark">
        <div className="eyebrow">Craftsmanship</div>
        <h3>Set apart<br />for the sacred.</h3>
        <p className="sub">Hand-polished 24K gold plate over solid brass — made to hold the most important cup your church will ever serve.</p>
        <div className="art"><img src="/products/Chalice_Cup.jpg" alt="" /></div>
      </div>

      <div className="stats">
        <div className="s"><small>Finish</small><b>24K</b><span>gold electroplate</span></div>
        <div className="s"><small>Capacity</small><b>450ml</b><span>full communion cup</span></div>
        <div className="s"><small>Hand-polish</small><b>6h</b><span>per piece, by hand</span></div>
      </div>

      <div className="trio">
        <div className="cell"><h4>Jewelled node.</h4><p>Four hand-set stones mark the cross at the stem — a detail seen up close, at the rail.</p><img src="/products/Chalice_Cup21.jpg" alt="" /></div>
        <div className="cell"><h4>The full sanctuary set.</h4><p>Pair it with the matching thurible and communion ware — one finish across the whole altar.</p><img src="/products/gold-wares.jpg" alt="" /></div>
        <div className="cell wide">
          <div><h4>From one cup to the whole congregation.</h4><p>Add the matching 40-cup communion tray — and have every piece engraved with your parish name, free, etched beneath the base.</p></div>
          <img src="/products/gold0_72.jpg" alt="" />
        </div>
      </div>

      <div className="banner ivory">
        <div className="eyebrow gn">In Service</div>
        <h3>Serve with reverence.</h3>
        <div className="art"><img src="/products/gold-wares.jpg" alt="" /></div>
      </div>

      <div className="story-lede">
        <div className="lede-eyebrow bl">Care</div>
        <h4>Built for every Sunday</h4>
        <p>The gold electroplate is sealed with an anti-tarnish coat, so weekly service — and weekly washing — keeps its lustre. A soft cloth is all it needs.</p>
      </div>

      <div className="compare">
        <table>
          <tbody>
            <tr><th></th><th className="hl">Chalice Royale</th><th>Chalice Classic</th></tr>
            <tr className="imgrow"><td></td><td className="hl"><img src="/products/Chalice_Cup.jpg" alt="" /></td><td><img src="/products/Chalice_Cup21.jpg" alt="" /></td></tr>
            <tr><td className="f">Finish</td><td className="hl">24K gold electroplate</td><td>Brushed gold tone</td></tr>
            <tr><td className="f">Capacity</td><td className="hl">450ml</td><td>350ml</td></tr>
            <tr><td className="f">Fitted paten lid</td><td className="hl ok">✓</td><td className="no">✕</td></tr>
            <tr><td className="f">Jewelled stem</td><td className="hl ok">✓</td><td className="no">✕</td></tr>
            <tr><td className="f">Free engraving</td><td className="hl ok">✓</td><td className="ok">✓</td></tr>
            <tr><td className="f">Anti-tarnish seal</td><td className="hl ok">✓</td><td className="ok">✓</td></tr>
            <tr><td className="f">Price</td><td className="hl">KES 18,500</td><td>KES 9,800</td></tr>
          </tbody>
        </table>
      </div>

      <div className="inbox">
        <h4>What&apos;s in the Box?</h4>
        <p>Chalice Royale gold chalice ×1<br />Fitted paten lid ×1<br />Velvet-lined presentation case ×1<br />Polishing cloth ×1<br />Care card ×1</p>
      </div>

      <div className="faqs">
        <h4>Questions, answered.</h4>
        <details>
          <summary>How does free engraving work?</summary>
          <p>Add your parish name or dedication at checkout. Engraving is etched beneath the base — the cup itself stays unmarked — and adds 3–5 working days before dispatch.</p>
        </details>
        <details>
          <summary>How should the chalice be cleaned?</summary>
          <p>Hand-wash with warm water and a soft cloth only. The anti-tarnish seal keeps the lustre through weekly service; avoid abrasives and dishwashers.</p>
        </details>
        <details>
          <summary>Can our diocese order in quantity?</summary>
          <p>Yes — parish and diocese accounts get quantity quotes, consolidated delivery and invoicing. Call +254 727 891 989 or ask in-store on Moi Avenue.</p>
        </details>
        <details>
          <summary>What if it arrives damaged?</summary>
          <p>Every piece is inspected and packed in a velvet-lined case. If anything arrives less than perfect, we replace it — that is the quality guarantee.</p>
        </details>
      </div>
    </div>
  );
}

/** Frequently-bought-together bundle for the flagship. */
function BoughtTogether() {
  const items = ["chalice-royale", "altar-wine", "communion-hosts"]
    .map(curatedBySlug)
    .filter((p): p is NonNullable<typeof p> => Boolean(p));
  const total = items.reduce((s, p) => s + p.price, 0);
  const was = items.reduce((s, p) => s + (p.oldPrice ?? p.price), 0);
  const totalUsd = items.reduce((s, p) => s + p.priceUsd, 0);
  const wasUsd = items.reduce((s, p) => s + (p.oldPriceUsd ?? p.priceUsd), 0);

  return (
    <section className="bundle">
      <h4>Frequently bought together</h4>
      <div className="row">
        {items.map((p, i) => (
          <div style={{ display: "flex", alignItems: "center", gap: 8 }} key={p.slug}>
            {i > 0 && <span className="plus-sign">+</span>}
            <Link className="bitem" href={`/product/${p.slug}`}>
              <span className="im"><img src={p.img} alt="" /></span>
              <b>{p.short}</b>
              <span><Price p={p} /></span>
            </Link>
          </div>
        ))}
        <div className="sum">
          <small>The Lord&apos;s Table, complete</small>
          <div className="tot"><Money kes={total} usd={totalUsd} /></div>
          <div className="was"><Money kes={was} usd={wasUsd} /></div>
          <BundleAdd slugs={items.map((p) => p.slug)} />
        </div>
      </div>
    </section>
  );
}
