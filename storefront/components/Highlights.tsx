"use client";

import { useEffect, useState } from "react";

export interface Highlight {
  label: string;
  title: string;
  text: string;
  img: string;
}

/** Apple-style "Get the highlights" — tabbed auto-advancing feature strip
    that compresses the product story for skimmers, above the long scroll. */
export default function Highlights({ items }: { items: Highlight[] }) {
  const [i, setI] = useState(0);
  const [paused, setPaused] = useState(false);

  useEffect(() => {
    if (paused) return;
    if (typeof window !== "undefined" && window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;
    const t = setInterval(() => setI((v) => (v + 1) % items.length), 4500);
    return () => clearInterval(t);
  }, [paused, items.length]);

  const cur = items[i];

  return (
    <section
      className="hilite"
      aria-roledescription="carousel"
      aria-label="Product highlights"
      onMouseEnter={() => setPaused(true)}
      onMouseLeave={() => setPaused(false)}
    >
      <div className="hl-copy">
        <span className="hl-eyebrow">Get the highlights</span>
        <h3 key={cur.title} className="hl-title">{cur.title}</h3>
        <p key={cur.text}>{cur.text}</p>
      </div>
      <div className="hl-media">
        <img key={cur.img} src={cur.img} alt={cur.title} />
      </div>
      <div className="hl-tabs" role="tablist">
        {items.map((h, n) => (
          <button key={h.label} role="tab" aria-selected={i === n}
            className={i === n ? "on" : ""} onClick={() => setI(n)}>
            {h.label}
          </button>
        ))}
      </div>
    </section>
  );
}
