"use client";

import { useEffect, useState } from "react";

/* Client-side pieces of the product page. */

export function Gallery({ images }: { images: string[] }) {
  const [active, setActive] = useState(0);
  const step = (d: number) => setActive((a) => (a + d + images.length) % images.length);
  return (
    <div className="gallery">
      <div className="main">
        <img src={images[active]} alt="Product view" />
        <button className="gnav prev" aria-label="Previous image" onClick={() => step(-1)}>‹</button>
        <button className="gnav next" aria-label="Next image" onClick={() => step(1)}>›</button>
      </div>
      <div className="thumbs">
        {images.map((src, i) => (
          <button key={src} className={i === active ? "active" : ""} onClick={() => setActive(i)}>
            <img src={src} alt="" />
          </button>
        ))}
      </div>
    </div>
  );
}

export function FinishSwatches({ finishes }: { finishes: { label: string; css: string }[] }) {
  const [active, setActive] = useState(0);
  return (
    <div className="opt">Finish
      <div className="swatches">
        {finishes.map((f, i) => (
          <button key={f.label} aria-label={f.label} style={{ background: f.css }}
            className={i === active ? "active" : ""} onClick={() => setActive(i)} />
        ))}
      </div>
    </div>
  );
}

export function Qty() {
  const [n, setN] = useState(1);
  return (
    <div className="opt">Qty
      <div className="qty">
        <button aria-label="Decrease" onClick={() => setN((v) => Math.max(1, v - 1))}>‹</button>
        <input value={n} readOnly />
        <button aria-label="Increase" onClick={() => setN((v) => v + 1)}>›</button>
      </div>
    </div>
  );
}

/** Sticky sub-header + bottom buy bar, revealed on scroll. */
export function StickyChrome({ name, sku }: { name: string; sku: string }) {
  const [scrolled, setScrolled] = useState(0);
  const [inReviews, setInReviews] = useState(false);

  useEffect(() => {
    const onScroll = () => {
      setScrolled(window.scrollY);
      const rev = document.getElementById("reviews");
      setInReviews(Boolean(rev && window.scrollY + 200 >= rev.offsetTop));
    };
    window.addEventListener("scroll", onScroll, { passive: true });
    onScroll();
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  return (
    <>
      <div className={`pheader ${scrolled > 480 ? "show" : ""}`}>
        <div className="wrap">
          <span className="name">{name}</span><span className="sku">{sku}</span>
          <div className="tabs">
            <a href="#description" className={inReviews ? "" : "active"}>Description</a>
            <a href="#reviews" className={inReviews ? "active" : ""}>Reviews</a>
          </div>
        </div>
      </div>
      <div className={`buybar ${scrolled > 300 ? "show" : ""}`}>
        <div className="wrap">
          <button className="pill pill-ghost">Add to Cart</button>
          <button className="pill pill-solid">Buy It Now</button>
        </div>
      </div>
    </>
  );
}

export function RateInput() {
  const [hover, setHover] = useState(-1);
  return (
    <div className="rate-input">
      <h5>Review this product</h5>
      <div className="boxes" onMouseLeave={() => setHover(-1)}>
        {[0, 1, 2, 3, 4].map((i) => (
          <button key={i} onMouseEnter={() => setHover(i)}>{i <= hover ? "★" : "☆"}</button>
        ))}
      </div>
    </div>
  );
}

export function Helpful({ up, down }: { up: number; down: number }) {
  const [votes, setVotes] = useState({ up, down });
  const [voted, setVoted] = useState(false);
  const vote = (key: "up" | "down") => {
    if (voted) return;
    setVotes((v) => ({ ...v, [key]: v[key] + 1 }));
    setVoted(true);
  };
  return (
    <div className="helpful">Helpful?
      <button onClick={() => vote("up")} style={voted ? { color: "var(--navy-700)" } : undefined}>👍 ({votes.up})</button>
      <button onClick={() => vote("down")}>👎 ({votes.down})</button>
    </div>
  );
}
