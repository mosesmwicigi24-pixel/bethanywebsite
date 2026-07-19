"use client";

import { useMemo, useState } from "react";
import { useRouter } from "next/navigation";
import { useCart } from "@/lib/cart";
import { Gallery } from "./pdp";
import { Money } from "./Money";
import type { Product, VariantOption } from "@/lib/products";

/**
 * Single-page product experience for a product with saved variants — the
 * Alibaba model. One main product, its variant attributes listed and selected
 * in place (no standalone variant pages): choosing a value swaps the gallery,
 * price and SKU without navigating. Measurements live in a right-hand column
 * on desktop and collapse into a tap-to-open tab on phones.
 *
 * Each pick adds the selected variant (its `parent--v{id}` slug) to the cart,
 * so the existing cart → hub bridge (slug + variant_id) is untouched.
 */
export default function ProductStudio({ product, preselect, sku }: {
  product: Product;
  preselect?: string;
  sku: string;
}) {
  const { add } = useCart();
  const router = useRouter();
  const variants = useMemo(() => product.variants ?? [], [product.variants]);

  const [active, setActive] = useState<VariantOption | undefined>(
    () => variants.find((v) => v.slug === preselect) ?? cheapest(variants) ?? variants[0],
  );
  const [qty, setQty] = useState(1);
  const [values, setValues] = useState<Record<string, string>>({});
  const [size, setSize] = useState<string | null>(null);
  const hasReady = (product.sizes?.length ?? 0) > 0;
  const [mode, setMode] = useState<"ready" | "custom">(hasReady ? "ready" : "custom");
  const [touched, setTouched] = useState(false);
  const [measureOpen, setMeasureOpen] = useState(false);
  const [added, setAdded] = useState(false);

  // Attribute axes (Colour, Size, …) in first-seen order, distinct values each.
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

  if (!active) return null; // no selectable variants — nothing to render

  // Some hub variants lack a KES/USD price row — fall back to the parent's
  // "from" price so the customer never sees KES 0 (the hub reprices on order).
  const kesShown = active.price > 0 ? active.price : product.price;
  const usdShown = active.priceUsd > 0 ? active.priceUsd : product.priceUsd;

  const template = product.measurements ?? [];
  const producible = Boolean(product.producible);
  const missing = producible && mode === "custom"
    ? template.filter((f) => f.required && !values[f.name]?.trim()).map((f) => f.name)
    : [];
  const detailsOk = !producible || (mode === "ready" ? size !== null : missing.length === 0);

  /** Pick a value on one axis → snap to the saved variant that best keeps the
      other current selections. */
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

  const commit = (): boolean => {
    if (!detailsOk) {
      setTouched(true);
      setMeasureOpen(true);
      document.getElementById("ps-measure")?.scrollIntoView({ behavior: "smooth", block: "center" });
      return false;
    }
    if (producible && mode === "custom") add(active.slug, qty, values);
    else if (producible && mode === "ready") add(active.slug, qty, undefined, size ?? undefined);
    else add(active.slug, qty);
    return true;
  };

  const addToCart = () => {
    if (!commit()) return;
    setAdded(true);
    setTimeout(() => setAdded(false), 1600);
  };
  const buyNow = () => { if (commit()) router.push("/checkout"); };

  return (
    <div className={`pstudio ${producible ? "has-measure" : ""}`}>
      <Gallery key={active.slug} images={active.gallery} kes={active.price} usd={active.priceUsd} />

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
          <div style={{ margin: "2px 0 6px" }}>
            <span className="tag tag-gold">
              {hasReady ? "Ready-made sizes ✂ or made to measure" : "✂ Made to order — measurements required"}
            </span>
          </div>
        )}

        {axes.map(([axis, vals]) => (
          <div className="ps-axis" key={axis}>
            <div className="ps-axis-head">{axis}: <b>{active.attributes[axis]}</b></div>
            <div className="ps-axis-vals">
              {vals.map((val) => (
                <button
                  key={val}
                  type="button"
                  className={active.attributes[axis] === val ? "on" : ""}
                  onClick={() => chooseValue(axis, val)}
                >
                  {val}
                </button>
              ))}
            </div>
          </div>
        ))}

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

      {producible && (
        <aside id="ps-measure" className={`ps-measure ${measureOpen ? "open" : ""} ${touched && !detailsOk ? "invalid" : ""}`}>
          <button type="button" className="ps-measure-tab" onClick={() => setMeasureOpen((o) => !o)} aria-expanded={measureOpen}>
            <span>✂ Your measurements</span>
            <em>{detailsOk ? "✓ ready" : "tap to fill"}</em>
            <i className="chev" aria-hidden="true">⌄</i>
          </button>

          <div className="ps-measure-body">
            <p className="ps-measure-sub">
              {mode === "ready" ? "Standard sizes on the rack — same-day Nairobi." : "Sewn to these numbers in Nairobi · 5–7 days."}
            </p>

            {hasReady && (
              <div className="vs-mode" role="radiogroup" aria-label="How it's made">
                <button role="radio" aria-checked={mode === "ready"} className={mode === "ready" ? "on" : ""} onClick={() => setMode("ready")}>Ready-made size</button>
                <button role="radio" aria-checked={mode === "custom"} className={mode === "custom" ? "on" : ""} onClick={() => setMode("custom")}>✂ Made to measure</button>
              </div>
            )}

            {mode === "ready" && hasReady ? (
              <div className="size-pills" role="radiogroup" aria-label="Size">
                {product.sizes!.map((s) => (
                  <button key={s} role="radio" aria-checked={size === s} className={size === s ? "on" : ""} onClick={() => setSize(s)}>{s}</button>
                ))}
              </div>
            ) : (
              <div className="m-grid ps-mgrid">
                {template.map((f, i) => (
                  <label key={`${f.name}-${i}`} className="m-field">
                    <span>{f.name}{f.required && <i aria-hidden="true"> *</i>}{f.unit && <em> ({f.unit})</em>}</span>
                    <input inputMode="decimal" placeholder="e.g. 42" value={values[f.name] ?? ""}
                      onChange={(e) => setValues((p) => ({ ...p, [f.name]: e.target.value }))} />
                  </label>
                ))}
              </div>
            )}

            {touched && !detailsOk && (
              <p className="m-warn">{mode === "ready" ? "Please pick a size." : `Fill the required measurements: ${missing.join(", ")}.`}</p>
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
