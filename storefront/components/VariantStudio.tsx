"use client";

import Link from "next/link";
import { useEffect, useState } from "react";
import { useCart } from "@/lib/cart";
import { Money } from "./Money";
import type { Product, Measurement } from "@/lib/products";

/**
 * Alibaba-style variant experience for products that ship in several
 * designs/colours (each hub variant is its own Product).
 *
 *   1. A rich swatch grid — every option shown at once, named, the current
 *      one ringed. Picking one deep-links to that variant's page.
 *   2. A "Select variations & quantity" drawer — the church buyer's power
 *      tool: order any MIX of designs in one go with per-design quantity
 *      steppers and a live subtotal. For made-to-order garments one set of
 *      measurements (the wearer's) is sewn into every design picked; for
 *      ready-made, one size applies to the set. "Add all to cart" drops each
 *      picked design in as its own line.
 */
export default function VariantStudio({ current, variants, template, sizes, producible }: {
  current: string;
  variants: Product[];
  template: Measurement[];
  sizes?: string[];
  producible?: boolean;
}) {
  const { add } = useCart();
  const [open, setOpen] = useState(false);
  const [qty, setQty] = useState<Record<string, number>>({});
  const [values, setValues] = useState<Record<string, string>>({});
  const [size, setSize] = useState<string | null>(null);
  const hasReady = (sizes?.length ?? 0) > 0;
  const [mode, setMode] = useState<"ready" | "custom">(hasReady ? "ready" : "custom");
  const [touched, setTouched] = useState(false);
  const [added, setAdded] = useState(false);

  const label = variantLabel(variants);
  const selected = variants.find((v) => v.slug === current) ?? variants[0];
  const nameOf = (v: Product) => (v.variantAttributes ? Object.values(v.variantAttributes).join(" · ") : v.short);

  const totalQty = variants.reduce((s, v) => s + (qty[v.slug] ?? 0), 0);
  const subKes = variants.reduce((s, v) => s + (qty[v.slug] ?? 0) * v.price, 0);
  const subUsd = variants.reduce((s, v) => s + (qty[v.slug] ?? 0) * v.priceUsd, 0);

  const missing = producible && mode === "custom"
    ? template.filter((m) => m.required && !values[m.name]?.trim()).map((m) => m.name)
    : [];
  const detailsOk = !producible || (mode === "ready" ? size !== null : missing.length === 0);
  const canAdd = totalQty > 0 && detailsOk;

  useEffect(() => {
    if (!open) return;
    const onKey = (e: KeyboardEvent) => { if (e.key === "Escape") setOpen(false); };
    window.addEventListener("keydown", onKey);
    document.body.style.overflow = "hidden";
    return () => { window.removeEventListener("keydown", onKey); document.body.style.overflow = ""; };
  }, [open]);

  const bump = (slug: string, d: number) =>
    setQty((q) => ({ ...q, [slug]: Math.max(0, (q[slug] ?? 0) + d) }));

  const addAll = () => {
    if (!canAdd) { setTouched(true); return; }
    for (const v of variants) {
      const n = qty[v.slug] ?? 0;
      if (n <= 0) continue;
      if (producible && mode === "custom") add(v.slug, n, values);
      else if (producible && mode === "ready") add(v.slug, n, undefined, size ?? undefined);
      else add(v.slug, n);
    }
    setAdded(true);
    setTimeout(() => { setAdded(false); setOpen(false); setQty({}); setTouched(false); }, 1500);
  };

  return (
    <div className="opt vstudio">
      <div className="vs-head">
        <span className="vs-label">{label}: <b>{nameOf(selected)}</b></span>
        <span className="vs-count">{variants.length} options</span>
      </div>

      <div className="vs-grid">
        {variants.map((v) => (
          <Link
            key={v.slug}
            href={`/product/${v.slug}`}
            aria-current={v.slug === current}
            className={`vs-swatch ${v.slug === current ? "on" : ""}`}
            title={nameOf(v)}
          >
            <span className="vs-thumb"><img src={v.img} alt={v.short} /></span>
            <span className="vs-name">{nameOf(v)}</span>
          </Link>
        ))}
      </div>

      {variants.length > 1 && (
        <button type="button" className="vs-open" onClick={() => setOpen(true)}>
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16M4 12h16M4 19h10" /></svg>
          Order several {label.toLowerCase()}s at once
        </button>
      )}

      {open && (
        <>
          <div className="vs-overlay" onClick={() => setOpen(false)} />
          <aside className="vs-drawer" role="dialog" aria-label="Select variations and quantity">
            <div className="vs-drawer-head">
              <div>
                <h3>Select {label.toLowerCase()}s &amp; quantity</h3>
                <p>Any mix — one delivery{producible ? ", one set of measurements" : ""}.</p>
              </div>
              <button className="vs-x" aria-label="Close" onClick={() => setOpen(false)}>✕</button>
            </div>

            <div className="vs-drawer-body">
              {producible && hasReady && (
                <div className="vs-mode" role="radiogroup" aria-label="How it's made">
                  <button role="radio" aria-checked={mode === "ready"} className={mode === "ready" ? "on" : ""} onClick={() => setMode("ready")}>Ready-made size</button>
                  <button role="radio" aria-checked={mode === "custom"} className={mode === "custom" ? "on" : ""} onClick={() => setMode("custom")}>✂ Made to measure</button>
                </div>
              )}

              {producible && mode === "ready" && hasReady && (
                <div className="vs-block">
                  <div className="vs-sub">Size <em>applies to the whole set</em></div>
                  <div className="size-pills" role="radiogroup" aria-label="Size">
                    {sizes!.map((s) => (
                      <button key={s} role="radio" aria-checked={size === s} className={size === s ? "on" : ""} onClick={() => setSize(s)}>{s}</button>
                    ))}
                  </div>
                </div>
              )}

              {producible && mode === "custom" && (
                <div className="vs-block">
                  <div className="vs-sub">✂ Measurements <em>sewn into every design you pick</em></div>
                  <div className="m-grid vs-mgrid">
                    {template.map((f, i) => (
                      <label key={`${f.name}-${i}`} className="m-field">
                        <span>{f.name}{f.required && <i aria-hidden="true"> *</i>}{f.unit && <em> ({f.unit})</em>}</span>
                        <input inputMode="decimal" placeholder="e.g. 42" value={values[f.name] ?? ""}
                          onChange={(e) => setValues((p) => ({ ...p, [f.name]: e.target.value }))} />
                      </label>
                    ))}
                  </div>
                </div>
              )}

              <div className="vs-sub">Choose your {label.toLowerCase()}s</div>
              <div className="vs-rows">
                {variants.map((v) => {
                  const n = qty[v.slug] ?? 0;
                  return (
                    <div className={`vs-row ${n > 0 ? "picked" : ""}`} key={v.slug}>
                      <span className="vs-row-im"><img src={v.img} alt="" /></span>
                      <span className="vs-row-info">
                        <b>{nameOf(v)}</b>
                        <span><Money kes={v.price} usd={v.priceUsd} /></span>
                      </span>
                      <span className="vs-stepper">
                        <button aria-label={`Fewer ${nameOf(v)}`} onClick={() => bump(v.slug, -1)} disabled={n <= 0}>−</button>
                        <input value={n} readOnly aria-label={`${nameOf(v)} quantity`} />
                        <button aria-label={`More ${nameOf(v)}`} onClick={() => bump(v.slug, 1)}>+</button>
                      </span>
                    </div>
                  );
                })}
              </div>

              {touched && !detailsOk && (
                <p className="vs-warn">
                  {mode === "ready" ? "Pick a size for the set." : `Fill the required measurements: ${missing.join(", ")}.`}
                </p>
              )}
            </div>

            <div className="vs-drawer-foot">
              <div className="vs-tot">
                <span>{totalQty} item{totalQty === 1 ? "" : "s"}</span>
                <b><Money kes={subKes} usd={subUsd} /></b>
              </div>
              <button className="pill pill-gold" disabled={!canAdd} onClick={addAll}>
                {added ? "✓ Added to cart" : totalQty > 0 ? `Add ${totalQty} to cart` : "Choose quantities"}
              </button>
            </div>
          </aside>
        </>
      )}
    </div>
  );
}

/** Human label for the variant axis: the single attribute name when there's
    one axis (Colour / Size / …), else the neutral "Option". */
function variantLabel(variants: Product[]): string {
  const keys = new Set<string>();
  for (const v of variants) if (v.variantAttributes) Object.keys(v.variantAttributes).forEach((k) => keys.add(k));
  if (keys.size === 1) { const k = [...keys][0]; return k === "Color" ? "Colour" : k; }
  return "Option";
}
