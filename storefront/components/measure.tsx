"use client";

import { ReactNode, createContext, useContext, useMemo, useState } from "react";
import type { Measurement } from "@/lib/products";

/* Made-to-order measurement capture (hub: products.is_producible +
   products.measurements template [{name, unit, required}]). The PDP wraps
   its buy surfaces in a MeasureProvider; the sticky buy bar refuses to add
   until every required measurement is filled. */

export interface MeasureCtx {
  template: Measurement[];
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

export function MeasureProvider({ template, children }: { template: Measurement[]; children: ReactNode }) {
  const [values, setValues] = useState<Record<string, string>>({});
  const [touched, setTouched] = useState(false);

  const missing = useMemo(
    () => template.filter((m) => m.required && !values[m.name]?.trim()).map((m) => m.name),
    [template, values],
  );

  const ctx: MeasureCtx = {
    template,
    values,
    set: (name, v) => setValues((prev) => ({ ...prev, [name]: v })),
    missing,
    valid: missing.length === 0,
    touched,
    markTouched: () => setTouched(true),
  };

  return <Ctx.Provider value={ctx}>{children}</Ctx.Provider>;
}

/** The measurement form shown on producible product pages. */
export function MeasurementForm() {
  const m = useMeasure();
  if (!m) return null;
  return (
    <div className={`measure ${m.touched && !m.valid ? "invalid" : ""}`} id="measurements">
      <div className="m-head">
        <b>✂ Made to order — your measurements</b>
        <span>Sewn to these numbers in Nairobi · 5–7 days</span>
      </div>
      <div className="m-grid">
        {m.template.map((f) => (
          <label key={f.name} className="m-field">
            <span>{f.name}{f.required && <i aria-hidden="true"> *</i>}{f.unit && <em> ({f.unit})</em>}</span>
            <input
              inputMode="decimal"
              placeholder={f.unit ? `e.g. 96` : ""}
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
      <p className="m-note">Not sure how to measure? Call {`+254 727 891 989`} — or visit us on Moi Avenue and we&apos;ll take them for you.</p>
    </div>
  );
}
