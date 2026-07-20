"use client";

import { ChangeEvent, ReactNode, createContext, useContext, useMemo, useRef, useState } from "react";
import type { Measurement } from "@/lib/products";

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
  garment?: string;
  mode: BuyMode;
  setMode: (m: BuyMode) => void;
  size: string | null;
  setSize: (s: string) => void;
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

export function MeasureProvider({ template, sizes = [], garment, children }: {
  template: Measurement[];
  sizes?: string[];
  garment?: string;
  children: ReactNode;
}) {
  const hasReady = sizes.length > 0;
  const [mode, setMode] = useState<BuyMode>(hasReady ? "ready" : "custom");
  const [size, setSize] = useState<string | null>(null);
  const [values, setValues] = useState<Record<string, string>>({});
  const [touched, setTouched] = useState(false);

  const missing = useMemo(
    () => template.filter((m) => m.required && !values[m.name]?.trim()).map((m) => m.name),
    [template, values],
  );

  const valid = mode === "ready" ? size !== null : missing.length === 0;

  const ctx: MeasureCtx = {
    template, sizes, garment, mode,
    setMode: (m) => { setMode(m); setTouched(false); },
    size, setSize,
    values,
    set: (name, v) => setValues((prev) => ({ ...prev, [name]: v })),
    missing, valid, touched,
    markTouched: () => setTouched(true),
  };

  return <Ctx.Provider value={ctx}>{children}</Ctx.Provider>;
}

/** Downscale a chosen photo to a small JPEG data URL before upload — keeps
    the request light and the vision cost low. Falls back to the raw file. */
async function toDownscaledDataUrl(file: File, max = 1024, quality = 0.85): Promise<string> {
  try {
    const bitmap = await createImageBitmap(file);
    const scale = Math.min(1, max / Math.max(bitmap.width, bitmap.height));
    const w = Math.max(1, Math.round(bitmap.width * scale));
    const h = Math.max(1, Math.round(bitmap.height * scale));
    const canvas = document.createElement("canvas");
    canvas.width = w;
    canvas.height = h;
    const ctx = canvas.getContext("2d");
    if (!ctx) throw new Error("no canvas context");
    ctx.drawImage(bitmap, 0, 0, w, h);
    bitmap.close?.();
    return canvas.toDataURL("image/jpeg", quality);
  } catch {
    return await new Promise<string>((resolve, reject) => {
      const fr = new FileReader();
      fr.onload = () => resolve(String(fr.result));
      fr.onerror = () => reject(new Error("read failed"));
      fr.readAsDataURL(file);
    });
  }
}

/** Photo → measurement estimates. Neema prefills the caller's fields; the
    customer confirms and edits each value before ordering (advisory §5.1).
    Prop-driven so both the simple form and ProductStudio can share it. */
export function MeasurePhoto({ garment, fields, onApply }: {
  garment?: string;
  fields: string[];
  onApply: (name: string, value: string) => void;
}) {
  const inputRef = useRef<HTMLInputElement>(null);
  const [status, setStatus] = useState<"idle" | "working" | "done" | "guided" | "error">("idle");
  const [note, setNote] = useState("");
  const [count, setCount] = useState(0);

  async function onFile(e: ChangeEvent<HTMLInputElement>) {
    const file = e.target.files?.[0];
    e.target.value = ""; // allow re-picking the same file
    if (!file || !file.type.startsWith("image/")) return;
    setStatus("working");
    setNote("");
    try {
      const image = await toDownscaledDataUrl(file);
      let sid = "anon";
      try { sid = localStorage.getItem("bh-neema-sid") || "anon"; } catch { /* ignore */ }
      const res = await fetch("/api/neema/measure", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ image, garment, template: fields, sessionId: sid }),
      });
      const data = await res.json();
      if (res.ok && data.available && Array.isArray(data.estimates) && data.estimates.length) {
        let applied = 0;
        for (const est of data.estimates as { name: string; value: string }[]) {
          const field = fields.find((f) => f.toLowerCase() === String(est.name).toLowerCase());
          if (field && est.value) { onApply(field, String(est.value)); applied += 1; }
        }
        setCount(applied);
        setNote(typeof data.notes === "string" ? data.notes : "");
        setStatus(applied ? "done" : "guided");
        if (!applied) setNote(data.notes || data.guidance || "I couldn't match those to the fields — please enter them below.");
      } else {
        setNote(data.notes || data.guidance || "Please enter your measurements below.");
        setStatus("guided");
      }
    } catch {
      setNote("I couldn't process that photo — please enter your measurements below.");
      setStatus("error");
    }
  }

  return (
    <div className="m-photo">
      <input ref={inputRef} type="file" accept="image/*" capture="environment" hidden onChange={onFile} />
      <button type="button" className="m-photo-btn" onClick={() => inputRef.current?.click()} disabled={status === "working"}>
        {status === "working" ? "Reading your photo…" : "📷 Estimate from a photo"}
      </button>
      <span className="m-photo-hint">Neema estimates your size from a full-length photo — you confirm each number before ordering.</span>
      {status === "done" && (
        <p className="m-photo-ok">✓ Estimated {count} measurement{count === 1 ? "" : "s"} — please check each number below.{note ? ` ${note}` : ""}</p>
      )}
      {(status === "guided" || status === "error") && <p className="m-photo-note">{note}</p>}
    </div>
  );
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
          <div className="m-head">
            <b>✂ Made to order — your measurements</b>
            <span>Sewn to these numbers in Nairobi · 5–7 days</span>
          </div>
          <MeasurePhoto garment={m.garment} fields={m.template.map((t) => t.name)} onApply={(name, value) => m.set(name, value)} />
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
      )}
    </div>
  );
}
