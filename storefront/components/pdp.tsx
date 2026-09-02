"use client";

import { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import { useCart } from "@/lib/cart";
import { useMeasure } from "./measure";
import { Money } from "./Money";

/* Client-side pieces of the product page. */

type Slide = { kind: "video" | "image"; src: string };

export function Gallery({ images, video, kes, usd }: { images: string[]; video?: string; kes?: number; usd?: number }) {
  // The clip leads the gallery when the product has one (as on jojosfashion.com):
  // it is the richest view of the piece, and the first still is its poster so
  // the frame never flashes black. Every still keeps its own thumbnail.
  const slides: Slide[] = [
    ...(video ? [{ kind: "video" as const, src: video }] : []),
    ...images.map((src) => ({ kind: "image" as const, src })),
  ];
  const [active, setActive] = useState(0);
  const step = (d: number) => setActive((a) => (a + d + slides.length) % slides.length);
  const cur = slides[Math.min(active, slides.length - 1)];

  return (
    <div className="gallery">
      <div className="main">
        {cur.kind === "video" ? (
          <video
            key={cur.src}
            src={cur.src}
            poster={images[0]}
            controls
            muted
            autoPlay
            loop
            playsInline
            preload="metadata"
            aria-label="Product video"
          />
        ) : (
          <img
            src={cur.src}
            alt="Product view"
            onError={(e) => { const t = e.currentTarget; if (!t.src.endsWith("placeholder.svg")) t.src = "/brand/placeholder.svg"; }}
          />
        )}
        {slides.length > 1 && (
          <>
            <button className="gnav prev" aria-label="Previous image" onClick={() => step(-1)}>‹</button>
            <button className="gnav next" aria-label="Next image" onClick={() => step(1)}>›</button>
          </>
        )}
        {kes !== undefined && usd !== undefined && (
          <span className="price-chip"><b><Money kes={kes} usd={usd} /></b></span>
        )}
      </div>
      <div className="thumbs">
        {slides.map((s, i) => (
          <button
            key={`${s.kind}:${s.src}`}
            className={`${i === active ? "active" : ""}${s.kind === "video" ? " vthumb" : ""}`}
            aria-label={s.kind === "video" ? "Play product video" : undefined}
            onClick={() => setActive(i)}
          >
            <img src={s.kind === "video" ? images[0] : s.src} alt="" />
          </button>
        ))}
      </div>
    </div>
  );
}

export function FinishSwatches({ finishes, label = "Finish", bare = false, onChange }: {
  finishes: { label: string; css: string }[];
  label?: string;
  /** Render just the swatch row (the caller draws the label). */
  bare?: boolean;
  onChange?: (label: string) => void;
}) {
  const [active, setActive] = useState(0);
  const pick = (i: number) => { setActive(i); onChange?.(finishes[i].label); };
  const row = (
    <div className="swatches" role="radiogroup" aria-label={label}>
      {finishes.map((f, i) => (
        <button key={f.label} type="button" role="radio" aria-checked={i === active} aria-label={f.label} title={f.label}
          style={{ background: f.css }} className={i === active ? "active" : ""} onClick={() => pick(i)} />
      ))}
    </div>
  );
  return bare ? row : <div className="opt">{label}{row}</div>;
}

/** Quantity is owned by <LookCard>, which renders the #pdp-qty input. */
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

/** Add the product to the cart with the chosen quantity, size or
    measurements — refusing (and scrolling to the card) while a producible
    item's choice is incomplete. Shared by the inline buttons and the bar. */
function useTryAdd(slug: string) {
  const { add } = useCart();
  const measure = useMeasure();
  return (): boolean => {
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
}

/** Add to cart / Buy now, in the buy column (the page used to rely on the
    scroll-revealed bar alone — at rest there was nothing to click). */
export function BuyButtons({ slug }: { slug: string }) {
  const tryAdd = useTryAdd(slug);
  const router = useRouter();
  const [added, setAdded] = useState(false);
  return (
    <div className="buy-actions">
      <button type="button" className="pill pill-solid" onClick={() => { if (tryAdd()) { setAdded(true); setTimeout(() => setAdded(false), 2000); } }}>
        {added ? "✓ Added to cart" : "Add to cart"}
      </button>
      <button type="button" className="pill pill-gold" onClick={() => { if (tryAdd()) router.push("/checkout"); }}>Buy now</button>
    </div>
  );
}

/** Product description, collapsed behind "Read more" past a few lines. */
export function ReadMore({ text, limit = 180 }: { text: string; limit?: number }) {
  const [open, setOpen] = useState(false);
  const long = text.length > limit;
  const shown = open || !long ? text : text.slice(0, limit).replace(/\s+\S*$/, "") + "…";
  return (
    <div className="desc" id="description">
      <p>{shown}</p>
      {long && (
        <button type="button" className="desc-more" onClick={() => setOpen((o) => !o)} aria-expanded={open}>
          {open ? "Read less" : "Read more"}
        </button>
      )}
    </div>
  );
}

/** Sticky sub-header + bottom buy bar, revealed on scroll. */
export function StickyChrome({ name, sku, kes, usd, img, slug, variant }: {
  name: string; sku: string; kes: number; usd: number; img: string; slug: string;
  /** The chosen variant's name, shown ahead of the mode on a variable product. */
  variant?: string;
}) {
  const router = useRouter();
  const measure = useMeasure();
  const tryAdd = useTryAdd(slug);
  const [saved, setSaved] = useState(false);
  const modeLook = !measure ? "In stock · ships today"
    : measure.mode === "ready" ? (measure.size ? `Ready-made · ${measure.size}` : "Ready-made")
    : "Made to measure";
  const look = [variant, modeLook].filter(Boolean).join(" · ");
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
            <span style={{ minWidth: 0 }}><b>{name}</b><span><Money kes={kes} usd={usd} /> · Look: {look}</span></span>
          </div>
          <div className="bb-ctas">
            <button className="pill pill-ghost" onClick={tryAdd}>Add to cart</button>
            <button className="pill pill-ghost bb-save" aria-pressed={saved} onClick={() => setSaved((v) => !v)}>{saved ? "♥ Saved" : "♡ Save"}</button>
            <button className="pill pill-gold" onClick={() => { if (tryAdd()) router.push("/checkout"); }}>Buy now</button>
          </div>
        </div>
      </div>
    </>
  );
}

export function RateInput({ slug }: { slug: string }) {
  const [hover, setHover] = useState(-1);
  const [rating, setRating] = useState(0);
  const [name, setName] = useState("");
  const [text, setText] = useState("");
  const [state, setState] = useState<"idle" | "sending" | "done" | "error">("idle");

  const submit = async () => {
    if (!rating || state === "sending") return;
    setState("sending");
    try {
      const r = await fetch("/api/neema/review", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          slug,
          rating,
          author: name.trim() || undefined,
          body: text.trim() || undefined,
        }),
      });
      const d = await r.json().catch(() => ({}));
      setState(r.ok && d?.ok !== false ? "done" : "error");
    } catch {
      setState("error");
    }
  };

  if (state === "done") {
    const first = name.trim().split(/\s+/)[0];
    return (
      <div className="rate-input rate-done">
        <div className="rate-tick" aria-hidden="true">✓</div>
        <h5>Thank you{first ? `, ${first}` : ""}!</h5>
        <p>Your {rating}-star review has been received. It appears here once verified.</p>
      </div>
    );
  }

  // While hovering, preview that star; otherwise show the committed rating.
  const shown = hover >= 0 ? hover : rating - 1;
  return (
    <div className="rate-input">
      <h5>Review this product</h5>
      <div className="boxes" onMouseLeave={() => setHover(-1)}>
        {[0, 1, 2, 3, 4].map((i) => (
          <button
            key={i}
            aria-label={`${i + 1} star${i ? "s" : ""}`}
            className={i <= shown ? "on" : ""}
            onMouseEnter={() => setHover(i)}
            onClick={() => setRating(i + 1)}
          >
            {i <= shown ? "★" : "☆"}
          </button>
        ))}
      </div>
      {rating > 0 && (
        <div className="rate-form">
          <input
            type="text"
            placeholder="Your name (e.g. Rev. Mwangi)"
            value={name}
            maxLength={80}
            onChange={(e) => setName(e.target.value)}
          />
          <textarea
            placeholder="How did it serve your church? (optional)"
            value={text}
            maxLength={2000}
            rows={3}
            onChange={(e) => setText(e.target.value)}
          />
          <button className="rate-send" onClick={submit} disabled={state === "sending"}>
            {state === "sending" ? "Sending…" : `Submit ${rating}-star review`}
          </button>
          {state === "error" && (
            <span className="rate-err">Couldn’t send just now — please try again.</span>
          )}
        </div>
      )}
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
