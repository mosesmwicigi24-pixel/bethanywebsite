"use client";

import { useState } from "react";

export interface LookFeature {
  label: string;
  text: string;
  img?: string;
}

/** Apple "Take a closer look" — expandable feature chips on the left,
    the product image swapping on the right as you explore. */
export default function CloserLook({ features, fallbackImg }: { features: LookFeature[]; fallbackImg: string }) {
  const [open, setOpen] = useState<number | null>(0);
  const img = (open !== null && features[open]?.img) || fallbackImg;

  return (
    <section className="closer wrap" aria-label="Take a closer look">
      <h2 className="closer-title">Take a closer look.</h2>
      <div className="closer-panel">
        <div className="closer-rail">
          {features.map((f, i) => (
            <div key={f.label} className={`cl-chiprow ${open === i ? "open" : ""}`}>
              <button
                className="cl-chip"
                aria-expanded={open === i}
                onClick={() => setOpen(open === i ? null : i)}
              >
                <i>{open === i ? "–" : "+"}</i>{f.label}
              </button>
              {open === i && (
                <p className="cl-text"><b>{f.label}.</b> {f.text}</p>
              )}
            </div>
          ))}
        </div>
        <div className="closer-media">
          <img key={img} src={img} alt="" />
        </div>
      </div>
    </section>
  );
}
