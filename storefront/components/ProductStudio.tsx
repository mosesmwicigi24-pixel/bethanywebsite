"use client";

import { useMemo, useState } from "react";
import { useRouter } from "next/navigation";
import { useCart } from "@/lib/cart";
import { Gallery } from "./pdp";
import { Money } from "./Money";
import type { Product, VariantOption } from "@/lib/products";

/**
 * Single-page product experience for a product with saved variants — the
 * Alibaba model. One main product, its variant attributes (Size, Colour, …)
 * selected in place (no standalone variant pages): choosing a value swaps the
 * gallery, price and SKU without navigating.
 *
 * Every made-to-order garment sells two ways, Ready-made first:
 *   • Ready-made (default) — buy the standard size/colour off the rack; no
 *     measurements, quicker to dispatch.
 *   • Made to order — only then does the measurements column appear (a right-
 *     hand column on desktop, a tap-to-open tab on phones); sewn to the
 *     customer's numbers.
 *
 * Either way the selected variant's `parent--v{id}` slug goes to the cart, so
 * the cart → hub bridge (slug + variant_id, +measurements ⇒ production order)
 * is untouched.
 */
export default function ProductStudio({ product, preselect, sku }: {
  product: Product;
  preselect?: string;
  sku: string;
}) {
  const { add } = useCart();
  const router = useRouter();
  const variants = useMemo(() => product.variants ?? [], [product.variants]);
  const producible = Boolean(product.producible);
  const template = product.measurements ?? [];

  const [active, setActive] = useState<VariantOption | undefined>(
    () => variants.find((v) => v.slug === preselect) ?? cheapest(variants) ?? variants[0],
  );
  const [qty, setQty] = useState(1);
  const [mode, setMode] = useState<"ready" | "custom">("ready"); // ready-made is the default
  const [values, setValues] = useState<Record<string, string>>({});
  const [touched, setTouched] = useState(false);
  const [measureOpen, setMeasureOpen] = useState(false);
  const [added, setAdded] = useState(false);

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

  if (!active) return null;

  // Some hub variants lack a KES/USD row — fall back to the parent's price.
  const kesShown = active.price > 0 ? active.price : product.price;
  const usdShown = active.priceUsd > 0 ? active.priceUsd : product.priceUsd;

  const showMeasure = producible && mode === "custom";
  const missing = showMeasure
    ? template.filter((f) => f.required && !values[f.name]?.trim()).map((f) => f.name)
    : [];
  const detailsOk = !producible || mode === "ready" || missing.length === 0;

  // In made-to-order the fit comes from the measurements, so the base "Size"
  // axis is hidden — only the design / colour axes remain.
  const shownAxes = axes.filter(([k]) => mode === "ready" || !/size/i.test(k));

  const nameOf = (v: VariantOption) => Object.values(v.attributes).join(" · ") || v.name;

  const chooseValue = (axis: string, value: string) => {
    const cands = variants.filter((v) => v.attributes[axis] === value);
    if (!cands.length) return;
    let best = cands[0], bestScore = -1;
    for (const c of cands) {
      let score = 0;
      for (const [k, val] of Object.entries(active.attributes)) {
        if (k !== axis && c.attributes[k] === val) score++;
      }
      if (score > bestScore) { bestScore = score; best = c; }
    }
    setActive(best);
  };

  const chooseCustom = () => { setMode("custom"); setMeasureOpen(true); };

  const commit = (): boolean => {
    if (!detailsOk) {
      setTouched(true);
      setMeasureOpen(true);
      document.getElementById("ps-measure")?.scrollIntoView({ behavior: "smooth", block: "center" });
      return false;
    }
    if (showMeasure) add(active.slug, qty, values);
    else add(active.slug, qty);
    return true;
  };

  const addToCart = () => { if (commit()) { setAdded(true); setTimeout(() => setAdded(false), 1600); } };
  const buyNow = () => { if (commit()) router.push("/checkout"); };

  void nameOf; // (kept for future variant-name display)

  return (
    <div className={`pstudio ${showMeasure ? "has-measure" : ""}`}>
      <Gallery key={active.slug} images={active.gallery} kes={kesShown} usd={usdShown} />

      <div className="ps-info">
        <h1>{product.name}</h1>
        <div className="rrow">
          <span className="stars">★★★★★</span><b>({product.reviews})</b>
          <a className="add" href="#reviews">Add your review ›</a>
        </div>
        <div className="pricerow">
          <b><Money kes={kesShown} usd={usdShown} /></b>
          {active.oldPrice && active.oldPrice > kesShown && (
            <s><Money kes={active.oldPrice} usd={active.oldPriceUsd ?? active.oldPrice} /></s>
          )}
        </div>

        {producible && (
          <div className="mode-cards" role="radiogroup" aria-label="How would you like it made?">
            <button type="button" role="radio" aria-checked={mode === "ready"}
              className={`mode-card ${mode === "ready" ? "active" : ""}`} onClick={() => setMode("ready")}>
              <b>Ready-made</b>
              <span>Standard size · quicker turnaround</span>
            </button>
            <button type="button" role="radio" aria-checked={mode === "custom"}
              className={`mode-card ${mode === "custom" ? "active" : ""}`} onClick={chooseCustom}>
              <b>✂ Made to order</b>
              <span>Your measurements · 5–7 days</span>
            </button>
          </div>
        )}

        {shownAxes.map(([axis, vals]) => (
          <div className="ps-axis" key={axis}>
            <div className="ps-axis-head">{axis}: <b>{active.attributes[axis]}</b></div>
            <div className="ps-axis-vals">
              {vals.map((val) => (
                <button key={val} type="button" className={active.attributes[axis] === val ? "on" : ""} onClick={() => chooseValue(axis, val)}>
                  {val}
                </button>
              ))}
            </div>
          </div>
        ))}

        {producible && (
          <div className="deliver">
            <span aria-hidden="true">🚚</span>
            <span>{mode === "ready"
              ? <>Ready-made in a standard size — <b>quicker to dispatch</b>.</>
              : <>Made to order — <b>5–7 days</b>, sewn to your measurements, anywhere in Kenya.</>}</span>
          </div>
        )}

        <div className="ps-qty">
          <span>Qty</span>
          <div className="qty">
            <button aria-label="Decrease" onClick={() => setQty((n) => Math.max(1, n - 1))}>‹</button>
            <input value={qty} readOnly />
            <button aria-label="Increase" onClick={() => setQty((n) => n + 1)}>›</button>
          </div>
          <span className="ps-sku">{active.sku || sku}</span>
        </div>

        <div className="ps-cta">
          <button className="pill pill-ghost" onClick={addToCart}>{added ? "✓ Added" : "Add to Cart"}</button>
          <button className="pill pill-gold" onClick={buyNow}>Buy It Now</button>
        </div>

        <div className="assure">
          <div className="a"><svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2" /><path d="M2 10h20" /></svg>M-Pesa &amp; Card</div>
          <div className="a"><svg viewBox="0 0 24 24"><path d="M3 7h11v10H3zM14 10h4l3 3v4h-7zM7 20a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm11 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" /></svg>Nationwide Delivery</div>
          <div className="a"><svg viewBox="0 0 24 24"><path d="M12 3 4 6v6c0 5 3.4 7.7 8 9 4.6-1.3 8-4 8-9V6l-8-3z" /><path d="m9 12 2 2 4-4" /></svg>Quality Guarantee</div>
        </div>
      </div>

      {showMeasure && (
        <aside id="ps-measure" className={`ps-measure ${measureOpen ? "open" : ""} ${touched && !detailsOk ? "invalid" : ""}`}>
          <button type="button" className="ps-measure-tab" onClick={() => setMeasureOpen((o) => !o)} aria-expanded={measureOpen}>
            <span>✂ Your measurements</span>
            <em>{detailsOk ? "✓ ready" : "tap to fill"}</em>
            <i className="chev" aria-hidden="true">⌄</i>
          </button>
          <div className="ps-measure-body">
            <p className="ps-measure-sub">Sewn to these numbers in Nairobi · 5–7 days.</p>
            <div className="m-grid ps-mgrid">
              {template.map((f, i) => (
                <label key={`${f.name}-${i}`} className="m-field">
                  <span>{f.name}{f.required && <i aria-hidden="true"> *</i>}{f.unit && <em> ({f.unit})</em>}</span>
                  <input inputMode="decimal" placeholder="e.g. 42" value={values[f.name] ?? ""}
                    onChange={(e) => setValues((p) => ({ ...p, [f.name]: e.target.value }))} />
                </label>
              ))}
            </div>
            {touched && !detailsOk && (
              <p className="m-warn">Fill the required measurements: {missing.join(", ")}.</p>
            )}
            <p className="m-note">Not sure how to measure? Call +254 727 891 989 — or visit us on Moi Avenue and we&apos;ll take them for you.</p>
          </div>
        </aside>
      )}
    </div>
  );
}

function cheapest(variants: VariantOption[]): VariantOption | undefined {
  const priced = variants.filter((v) => v.price > 0);
  const pool = priced.length ? priced : variants;
  return pool.reduce<VariantOption | undefined>((a, b) => (!a || b.price < a.price ? b : a), undefined);
}
