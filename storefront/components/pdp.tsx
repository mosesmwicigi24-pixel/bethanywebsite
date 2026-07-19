"use client";

import { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import { useCart } from "@/lib/cart";
import { useMeasure } from "./measure";
import { Money } from "./Money";

/* Client-side pieces of the product page. */

export function Gallery({ images, kes, usd }: { images: string[]; kes?: number; usd?: number }) {
  const [active, setActive] = useState(0);
  const [zoom, setZoom] = useState<{ x: number; y: number } | null>(null);
  const step = (d: number) => setActive((a) => (a + d + images.length) % images.length);

  const onMove = (e: React.MouseEvent<HTMLDivElement>) => {
    const r = e.currentTarget.getBoundingClientRect();
    setZoom({ x: ((e.clientX - r.left) / r.width) * 100, y: ((e.clientY - r.top) / r.height) * 100 });
  };

  return (
    <div className="gallery">
      <div className="main" onMouseMove={onMove} onMouseLeave={() => setZoom(null)}>
        <img
          src={images[active]}
          alt="Product view"
          onError={(e) => { const t = e.currentTarget; if (!t.src.endsWith("placeholder.svg")) t.src = "/brand/placeholder.svg"; }}
          style={zoom ? { transform: "scale(1.7)", transformOrigin: `${zoom.x}% ${zoom.y}%` } : undefined}
        />
        <button className="gnav prev" aria-label="Previous image" onClick={() => step(-1)}>‹</button>
        <button className="gnav next" aria-label="Next image" onClick={() => step(1)}>›</button>
        {kes !== undefined && usd !== undefined && (
          <span className="price-chip">From <b><Money kes={kes} usd={usd} /></b></span>
        )}
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

export function FinishSwatches({ finishes, label = "Finish" }: { finishes: { label: string; css: string }[]; label?: string }) {
  const [active, setActive] = useState(0);
  return (
    <div className="opt">{label}
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
        <input id="pdp-qty" value={n} readOnly />
        <button aria-label="Increase" onClick={() => setN((v) => v + 1)}>›</button>
      </div>
    </div>
  );
}

const readQty = () => {
  const el = document.getElementById("pdp-qty") as HTMLInputElement | null;
  return Math.max(1, Number(el?.value) || 1);
};

/** Add a whole bundle to the cart. */
export function BundleAdd({ slugs }: { slugs: string[] }) {
  const [added, setAdded] = useState(false);
  const { add } = useCart();
  const onAdd = () => {
    if (added) return;
    slugs.forEach((s) => add(s));
    setAdded(true);
    setTimeout(() => setAdded(false), 2200);
  };
  return (
    <button className="pill pill-gold" onClick={onAdd} style={{ width: "100%" }}>
      {added ? "✓ Added to cart" : `Add all ${slugs.length} to Cart`}
    </button>
  );
}

/** Sticky sub-header + bottom buy bar, revealed on scroll. */
export function StickyChrome({ name, sku, kes, usd, img, slug }: { name: string; sku: string; kes: number; usd: number; img: string; slug: string }) {
  const { add } = useCart();
  const router = useRouter();
  const measure = useMeasure();

  const tryAdd = (): boolean => {
    if (measure && !measure.valid) {
      measure.markTouched();
      document.getElementById("measurements")?.scrollIntoView({ behavior: "smooth", block: "center" });
      return false;
    }
    if (measure?.mode === "ready") {
      add(slug, readQty(), undefined, measure.size ?? undefined);
    } else {
      add(slug, readQty(), measure?.values);
    }
    return true;
  };
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
          <button className="pill pill-gold ph-buy" onClick={() => { if (tryAdd()) router.push("/checkout"); }}>
            Buy · <Money kes={kes} usd={usd} />
          </button>
        </div>
      </div>
      <div className={`buybar ${scrolled > 300 ? "show" : ""}`}>
        <div className="wrap">
          <div className="bb-info">
            <span className="im"><img src={img} alt="" /></span>
            <span style={{ minWidth: 0 }}><b>{name}</b><span><Money kes={kes} usd={usd} /> · Free Nairobi CBD delivery</span></span>
          </div>
          <div className="bb-ctas">
            <button className="pill pill-ghost" onClick={tryAdd}>Add to Cart</button>
            <button className="pill pill-solid" onClick={() => { if (tryAdd()) router.push("/checkout"); }}>Buy It Now</button>
          </div>
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
