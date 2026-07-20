"use client";

import { ReactNode, createContext, useContext, useMemo, useState } from "react";
import type { Measurement, Product } from "@/lib/products";
import { type Fit, FITS, FIT_LABEL, isGendered, sheetFor } from "@/lib/measureSheets";

/* Producible items are sold TWO ways (mirroring the shop floor):
     • Ready-made — standard sizes on the rack, ships today (stocked line)
     • Made to measure — customer's measurements, sewn in 5–7 days
       (production line; hub raises a production_order)
   The PDP wraps its buy surfaces in a MeasureProvider; the buy bar
   refuses to add until the chosen mode is complete (size picked, or
   every required measurement filled). */

export type BuyMode = "ready" | "custom";

export interface MeasureCtx {
  template: Measurement[];
  sizes: string[];
  mode: BuyMode;
  setMode: (m: BuyMode) => void;
  size: string | null;
  setSize: (s: string) => void;
  gendered: boolean;
  fit: Fit | null;
  setFit: (f: Fit) => void;
  values: Record<string, string>;
  set: (name: string, v: string) => void;
  missing: string[];
  valid: boolean;
  touched: boolean;
  markTouched: () => void;
}

const Ctx = createContext<MeasureCtx | null>(null);
/** Null on non-producible products — callers must handle both. */
export const useMeasure = () => useContext(Ctx);

export function MeasureProvider({ product, children }: {
  product: Product;
  children: ReactNode;
}) {
  const sizes = product.sizes ?? [];
  const hasReady = sizes.length > 0;
  const gendered = isGendered(product);
  const [mode, setMode] = useState<BuyMode>(hasReady ? "ready" : "custom");
  const [fit, setFitState] = useState<Fit | null>(null);
  const [size, setSize] = useState<string | null>(null);
  const [values, setValues] = useState<Record<string, string>>({});
  const [touched, setTouched] = useState(false);

  // Fields come straight from the hub; a gendered product filters by fit.
  const template = useMemo(() => sheetFor(product, fit), [product, fit]);

  const missing = useMemo(
    () => template.filter((m) => m.required && !values[m.name]?.trim()).map((m) => m.name),
    [template, values],
  );

  const valid = mode === "ready"
    ? size !== null
    : (!gendered || fit !== null) && missing.length === 0;

  const ctx: MeasureCtx = {
    template, sizes, mode,
    setMode: (m) => { setMode(m); setTouched(false); },
    size, setSize,
    gendered,
    fit,
    setFit: (f) => { setFitState(f); setTouched(false); },
    values,
    set: (name, v) => setValues((prev) => ({ ...prev, [name]: v })),
    missing, valid, touched,
    markTouched: () => setTouched(true),
  };

  return <Ctx.Provider value={ctx}>{children}</Ctx.Provider>;
}

/** The buy-mode selector + measurement form on producible product pages. */
export function MeasurementForm() {
  const m = useMeasure();
  if (!m) return null;
  const hasReady = m.sizes.length > 0;

  return (
    <div className={`measure ${m.touched && !m.valid ? "invalid" : ""}`} id="measurements">
      {hasReady && (
        <div className="mode-cards" role="radiogroup" aria-label="How would you like it made?">
          <button type="button" role="radio" aria-checked={m.mode === "ready"}
            className={`mode-card ${m.mode === "ready" ? "active" : ""}`} onClick={() => m.setMode("ready")}>
            <b>Ready-made</b>
            <span>Standard sizes · in stock · ships today</span>
          </button>
          <button type="button" role="radio" aria-checked={m.mode === "custom"}
            className={`mode-card ${m.mode === "custom" ? "active" : ""}`} onClick={() => m.setMode("custom")}>
            <b>✂ Made to measure</b>
            <span>Your measurements · sewn in Nairobi · 5–7 days</span>
          </button>
        </div>
      )}

      {m.mode === "ready" ? (
        <div className="size-pick">
          <div className="m-head">
            <b>Pick your size</b>
            <span>On the rack at Moi Avenue — same-day Nairobi delivery</span>
          </div>
          <div className="size-pills" role="radiogroup" aria-label="Size">
            {m.sizes.map((s) => (
              <button key={s} type="button" role="radio" aria-checked={m.size === s}
                className={m.size === s ? "on" : ""} onClick={() => m.setSize(s)}>
                {s}
              </button>
            ))}
          </div>
          {m.touched && !m.valid && <p className="m-warn">Please pick a size — or switch to made-to-measure.</p>}
          <p className="m-note">Between sizes? Choose ✂ Made to measure and we&apos;ll sew it to your numbers at no extra cost.</p>
        </div>
      ) : (
        <>
          {m.gendered && (
            <div className="fit-pick" role="radiogroup" aria-label="Who is it for?">
              <div className="m-head">
                <b>Who is it for?</b>
                <span>Men and ladies are measured differently</span>
              </div>
              <div className="fit-opts">
                {FITS.map((f) => (
                  <button key={f} type="button" role="radio" aria-checked={m.fit === f}
                    className={m.fit === f ? "on" : ""} onClick={() => m.setFit(f)}>
                    {FIT_LABEL[f]}
                  </button>
                ))}
              </div>
            </div>
          )}

          {!m.gendered || m.fit ? (
            <>
              <div className="m-head">
                <b>✂ {m.fit ? `${FIT_LABEL[m.fit]} — ` : ""}your measurements</b>
                <span>Sewn to these numbers in Nairobi · 5–7 days</span>
              </div>
              <div className="m-grid">
                {m.template.map((f, i) => (
                  <label key={`${f.name}-${i}`} className="m-field">
                    <span>{f.name}{f.required && <i aria-hidden="true"> *</i>}{f.unit && <em> ({f.unit})</em>}</span>
                    <input
                      inputMode="decimal"
                      placeholder="e.g. 42"
                      value={m.values[f.name] ?? ""}
                      onChange={(e) => m.set(f.name, e.target.value)}
                      aria-required={f.required}
                    />
                  </label>
                ))}
              </div>
              {m.touched && !m.valid && (
                <p className="m-warn">Please fill the required measurements: {m.missing.join(", ")}.</p>
              )}
              <p className="m-note">Not sure how to measure? Call +254 727 891 989 — or visit us on Moi Avenue and we&apos;ll take them for you.</p>
            </>
          ) : (
            m.touched && <p className="m-warn">Please choose Men or Ladies to see the measurements.</p>
          )}
        </>
      )}
    </div>
  );
}
