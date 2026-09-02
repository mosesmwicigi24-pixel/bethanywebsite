"use client";

import { ReactNode, useState } from "react";
import { Money } from "./Money";
import { MeasurementForm, useMeasure } from "./measure";
import { FinishSwatches } from "./pdp";

/**
 * "Choose your look" — the one card that gathers everything a shopper picks
 * before buying (how it's made, size or measurements, colour, quantity),
 * with the running price in its header. Replaces the controls that used to
 * sit loose down the buy column. The measurement state itself still lives
 * in <MeasureProvider>; this card only lays it out and owns the quantity.
 *
 * The quantity input keeps id="pdp-qty" — the buy buttons and sticky bar
 * read it from the DOM (see readQty in pdp.tsx).
 */
export default function LookCard({
  kes, usd, swatches, swatchLabel = "Colour", producible, footnote,
}: {
  kes: number;
  usd: number;
  swatches?: { label: string; css: string }[];
  swatchLabel?: string;
  producible?: boolean;
  footnote?: ReactNode;
}) {
  const m = useMeasure();
  const [qty, setQty] = useState(1);
  const [colour, setColour] = useState(swatches?.[0]?.label);

  const modeLabel = !m ? null
    : m.mode === "ready" ? (m.size ? `Ready-made · ${m.size}` : "Ready-made")
    : "Made to measure";
  const sub = producible
    ? "Pick how it is made, add your size or measurements, choose a colour."
    : "Pick a finish and quantity, then add to cart.";

  return (
    <section className="look" id="look" aria-label="Choose your look">
      <div className="look-head">
        <div>
          <span className="look-eyebrow">Choose your look</span>
          <span className="look-sub">{sub}</span>
        </div>
        <b className="look-total"><Money kes={kes * qty} usd={usd * qty} /></b>
      </div>

      {m && (
        <div className="look-sec">
          <div className="look-lbl"><span>How it&apos;s made</span><span className="val">{modeLabel}</span></div>
          <MeasurementForm />
        </div>
      )}

      {swatches && swatches.length > 0 && (
        <div className="look-sec">
          <div className="look-lbl"><span>{swatchLabel}</span><span className="val">{colour}</span></div>
          <FinishSwatches finishes={swatches} bare onChange={setColour} />
        </div>
      )}

      <div className="look-sec look-qty">
        <div className="look-lbl"><span>Quantity</span></div>
        <div className="qty">
          <button type="button" aria-label="Decrease" onClick={() => setQty((v) => Math.max(1, v - 1))}>‹</button>
          <input id="pdp-qty" value={qty} readOnly aria-label="Quantity" />
          <button type="button" aria-label="Increase" onClick={() => setQty((v) => v + 1)}>›</button>
        </div>
      </div>

      {footnote && <div className="look-foot">{footnote}</div>}
    </section>
  );
}
