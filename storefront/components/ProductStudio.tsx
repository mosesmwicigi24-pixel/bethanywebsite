"use client";

import { ReactNode, useMemo, useState } from "react";
import { MeasureProvider } from "./measure";
import { Gallery, StickyChrome, BuyButtons, ReadMore } from "./pdp";
import LookCard from "./LookCard";
import { Money } from "./Money";
import type { Product, VariantOption } from "@/lib/products";

/**
 * Product page for a product with saved variants — the same hybrid page as a
 * simple product, with the variant picker as the FIRST row of the "Choose
 * your look" card. Picking a tile swaps the gallery to that variant's photos,
 * the price, the SKU and the running total; nothing navigates. Made-to-order
 * garments then offer Ready-made (the variant as pictured) or Made to measure
 * in the rows below, through the same MeasureProvider the simple page uses,
 * so the cart → hub bridge (variant slug + measurements ⇒ production order)
 * is unchanged.
 */
export default function ProductStudio({ product, preselect, sku }: {
  product: Product;
  preselect?: string;
  sku: string;
}) {
  const inner = <Studio product={product} preselect={preselect} sku={sku} />;
  return product.producible ? (
    <MeasureProvider
      template={product.measurements ?? []}
      sizes={product.sizes ?? []}
      readyWithoutSize={!product.sizes?.length}
      garment={product.name}
    >
      {inner}
    </MeasureProvider>
  ) : inner;
}

function Studio({ product, preselect, sku }: { product: Product; preselect?: string; sku: string }) {
  const variants = useMemo(() => product.variants ?? [], [product.variants]);
  const [active, setActive] = useState<VariantOption | undefined>(
    () => variants.find((v) => v.slug === preselect) ?? cheapest(variants) ?? variants[0],
  );

  // Attribute axes (Colour, Size, …) and the ones that actually differ — they name the tiles.
  const axes = useMemo(() => {
    const m = new Map<string, string[]>();
    for (const v of variants) {
      for (const [k, val] of Object.entries(v.attributes)) {
        if (!m.has(k)) m.set(k, []);
        const arr = m.get(k)!;
        if (!arr.includes(val)) arr.push(val);
      }
    }
    return [...m.entries()];
  }, [variants]);
  // Colour and Size lead (tiles, then pills); any other axes follow in hub order.
  const LEAD = ["colour", "color", "size"];
  const varyingKeys = axes
    .filter(([, vals]) => vals.length > 1)
    .map(([k]) => k)
    .sort((a, b) => {
      const ia = LEAD.indexOf(a.toLowerCase()), ib = LEAD.indexOf(b.toLowerCase());
      return (ia === -1 ? 99 : ia) - (ib === -1 ? 99 : ib);
    });
  const axisLabel = varyingKeys.length === 1 ? varyingKeys[0] : "Variant";
  const multiAxis = varyingKeys.length >= 2;

  /** The variant matching `want` on every varying axis; else the one matching
      the changed axis plus as many of the others as possible. Never null when
      the changed value exists on some variant. */
  const resolve = (want: Record<string, string>, changed: string): VariantOption | undefined => {
    const exact = variants.find((v) => varyingKeys.every((k) => v.attributes[k] === want[k]));
    if (exact) return exact;
    const pool = variants.filter((v) => v.attributes[changed] === want[changed]);
    return pool
      .map((v) => ({ v, score: varyingKeys.filter((k) => v.attributes[k] === want[k]).length }))
      .sort((a, b) => b.score - a.score)[0]?.v;
  };
  /** Does any variant carry axis=value alongside the other current picks? */
  const combinable = (axis: string, value: string, current: Record<string, string>) =>
    variants.some((v) => v.attributes[axis] === value && varyingKeys.every((k) => k === axis || v.attributes[k] === current[k]));
  /** A representative image for an axis value (prefer a variant with its own photos). */
  const imageFor = (axis: string, value: string) => {
    const pool = variants.filter((v) => v.attributes[axis] === value);
    return (pool.find((v) => v.gallery.length && v.img !== product.img) ?? pool[0])?.img;
  };
  const cardName = (v: VariantOption) =>
    (varyingKeys.length ? varyingKeys.map((k) => v.attributes[k]).filter(Boolean).join(" · ") : "")
    || Object.values(v.attributes).join(" · ") || v.name;
  const pricesDiffer = new Set(variants.map((v) => v.price)).size > 1;

  if (!active) return null;

  const kes = active.price > 0 ? active.price : product.price;
  const usd = active.priceUsd > 0 ? active.priceUsd : product.priceUsd;
  const oldKes = active.oldPrice && active.oldPrice > kes ? active.oldPrice : undefined;
  const gallery = active.gallery.length ? active.gallery : [active.img];
  const producible = Boolean(product.producible);
  const plural = (n: number, w: string) => {
    const word = w.toLowerCase();
    return `${n} ${n === 1 || word.endsWith("s") ? word : `${word}s`}`;
  };

  const pickAxis = (axis: string, value: string) => {
    const next = resolve({ ...active.attributes, [axis]: value }, axis);
    if (next) setActive(next);
  };

  // Two or more varying axes (Colour × Size …): one row per axis — the first
  // as photo tiles, the rest as pills — instead of every combination as a tile.
  const variantRow: ReactNode = multiAxis ? (
    <>
      {varyingKeys.map((axis, i) => {
        const values = axes.find(([k]) => k === axis)?.[1] ?? [];
        const cur = active.attributes[axis];
        return (
          <div className="look-sec" key={axis}>
            <div className="look-lbl">
              <span>{axis}{i === 0 ? ` · ${plural(variants.length, "saved variant")}` : ""}</span>
              <span className="val">{cur}{i === varyingKeys.length - 1 && active.sku ? ` · ${active.sku}` : ""}</span>
            </div>
            {i === 0 ? (
              <div className="vtiles" role="radiogroup" aria-label={axis}>
                {values.map((val) => {
                  const on = val === cur;
                  const ok = combinable(axis, val, active.attributes);
                  return (
                    <button key={val} type="button" role="radio" aria-checked={on} className={`vtile${on ? " on" : ""}${ok ? "" : " dim"}`} onClick={() => pickAxis(axis, val)}>
                      <span className="im"><img src={imageFor(axis, val)} alt="" /></span>
                      <b>{val}</b>
                      {on && <i className="tick" aria-hidden="true">✓</i>}
                    </button>
                  );
                })}
              </div>
            ) : (
              <div className="size-pills vpills" role="radiogroup" aria-label={axis}>
                {values.map((val) => {
                  const on = val === cur;
                  const ok = combinable(axis, val, active.attributes);
                  return (
                    <button key={val} type="button" role="radio" aria-checked={on} className={`${on ? "on" : ""}${ok ? "" : " dim"}`} title={ok ? undefined : `Not offered in ${active.attributes[varyingKeys[0]]}`} onClick={() => pickAxis(axis, val)}>
                      {val}
                    </button>
                  );
                })}
              </div>
            )}
          </div>
        );
      })}
    </>
  ) : (
    <div className="look-sec">
      <div className="look-lbl">
        <span>{axisLabel} · {plural(variants.length, "saved variant")}</span>
        <span className="val">{cardName(active)}{active.sku ? ` · ${active.sku}` : ""}</span>
      </div>
      <div className="vtiles" role="radiogroup" aria-label={axisLabel}>
        {variants.map((v) => {
          const on = v.slug === active.slug;
          return (
            <button key={v.slug} type="button" role="radio" aria-checked={on}
              className={`vtile${on ? " on" : ""}`} onClick={() => setActive(v)}>
              <span className="im"><img src={v.img} alt="" /></span>
              <b>{cardName(v)}</b>
              {pricesDiffer && <span><Money kes={v.price} usd={v.priceUsd} /></span>}
              {on && <i className="tick" aria-hidden="true">✓</i>}
            </button>
          );
        })}
      </div>
      <p className="vtiles-note">Each {axisLabel.toLowerCase()} is its own item with its own photos and stock — the gallery follows your choice.</p>
    </div>
  );

  return (
    <>
      <StickyChrome name={product.name} sku={active.sku || sku} kes={kes} usd={usd} img={active.img} slug={active.slug} variant={cardName(active)} />

      <div className="pdp">
        <Gallery key={active.slug} images={gallery} video={product.video} kes={kes} usd={usd} />

        <div className="buy">
          <div className="crumb-pills">
            <span className="cp cp-gold">{product.category}</span>
            <span className="cp">
              {multiAxis
                ? varyingKeys.slice(0, 2).map((k) => plural(axes.find(([a]) => a === k)?.[1].length ?? 0, k)).join(" × ")
                : plural(variants.length, axisLabel)} · {producible
                ? product.sizes?.length ? "ready-made & made to measure" : "ready-made or made to measure"
                : "in stock"}
            </span>
          </div>
          <div className="buy-title">
            <h1>{product.name}</h1>
            <button className="wish" aria-label="Save to wishlist">♡</button>
          </div>
          <div className="rrow">
            {product.reviews > 0 && (
              <><span className="stars">{"★★★★★".slice(0, Math.round(product.rating))}</span><b>{product.rating.toFixed(1)}</b><span className="muted">({product.reviews})</span></>
            )}
            <a className="add" href="#reviews">{product.reviews > 0 ? "Read reviews ›" : "Be the first to review ›"}</a>
          </div>
          <div className="pricerow">
            <b><Money kes={kes} usd={usd} /></b>
            {oldKes && <s><Money kes={oldKes} usd={active.oldPriceUsd ?? oldKes} /></s>}
            {oldKes && <span className="save-tag">Save {Math.round((1 - kes / oldKes) * 100)}%</span>}
          </div>

          <LookCard
            kes={kes}
            usd={usd}
            producible={producible}
            before={variantRow}
            subtitle={multiAxis
              ? `Pick ${varyingKeys.slice(0, 2).map((k) => k.toLowerCase()).join(" and ")} — the photos and price follow — then ${producible ? "how it is made and " : ""}quantity.`
              : `Pick a ${axisLabel.toLowerCase()} — the photos and price follow — then ${producible ? "how it is made and " : ""}quantity.`}
            footnote={producible
              ? <>Ready-made ships <b>today in Nairobi</b> · made to measure in <b>5–7 days</b> · free Nairobi CBD delivery over KES 10,000</>
              : <>Order before <b>2 PM</b> for same-day Nairobi delivery · free in the CBD over KES 10,000</>}
          />

          {product.chips.map((c) => (
            <div className="feat" key={c.text}><span className="ic">{c.icon}</span>{c.text}</div>
          ))}
          <div className="deliver">
            <span aria-hidden="true">🚚</span>
            <span>{producible
              ? <>Ready-made variants ship <b>today in Nairobi</b> — made to measure in <b>5–7 days</b>, anywhere in Kenya.</>
              : <>Order before <b>2 PM</b> — delivered <b>today in Nairobi</b>, 2–4 days across East Africa.</>}</span>
          </div>

          <BuyButtons slug={active.slug} />

          {(() => {
            const text = [product.short, product.tagline]
              .find((t) => t && t.trim() && t.trim().toLowerCase() !== product.name.trim().toLowerCase());
            return text ? <ReadMore text={text} /> : null;
          })()}

          <div className="assure">
            <div className="a"><svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2" /><path d="M2 10h20" /></svg>M-Pesa &amp; Card</div>
            <div className="a"><svg viewBox="0 0 24 24"><path d="M3 7h11v10H3zM14 10h4l3 3v4h-7zM7 20a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm11 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" /></svg>Nationwide Delivery</div>
            <div className="a"><svg viewBox="0 0 24 24"><path d="M12 3 4 6v6c0 5 3.4 7.7 8 9 4.6-1.3 8-4 8-9V6l-8-3z" /><path d="m9 12 2 2 4-4" /></svg>Quality Guarantee</div>
          </div>
        </div>
      </div>
    </>
  );
}

function cheapest(variants: VariantOption[]): VariantOption | undefined {
  const priced = variants.filter((v) => v.price > 0);
  const pool = priced.length ? priced : variants;
  return pool.reduce<VariantOption | undefined>((a, b) => (!a || b.price < a.price ? b : a), undefined);
}
