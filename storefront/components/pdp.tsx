"use client";

import { useCallback, useEffect, useRef, useState } from "react";
import { useRouter } from "next/navigation";
import { useCart } from "@/lib/cart";
import { useMeasure } from "./measure";
import { Money } from "./Money";
import { openNeema } from "@/lib/ui-events";

/* Client-side pieces of the product page. */

type Slide = { kind: "video" | "image"; src: string };

export function Gallery({ images, video, name }: { images: string[]; video?: string; name?: string }) {
  // The clip leads the gallery when the product has one (as on jojosfashion.com):
  // it is the richest view of the piece, and the first still is its poster so
  // the frame never flashes black. Every still keeps its own thumbnail.
  const slides: Slide[] = [
    ...(video ? [{ kind: "video" as const, src: video }] : []),
    ...images.map((src) => ({ kind: "image" as const, src })),
  ];
  const N = slides.length;
  const main = useRef<HTMLDivElement>(null);
  const track = useRef<HTMLDivElement>(null);
  const [active, setActive] = useState(0);
  const [paused, setPaused] = useState(false);

  // The frame takes the first photo's own shape (--r = width / height), so a
  // portrait garment fills a phone screen and a square piece stays square.
  // Photos (and the clip) close to that shape fill the frame edge to edge;
  // one that differs a lot is shown whole over a soft wash of itself.
  // All set straight on the elements: no re-render, and it also covers
  // photos that finished loading before hydration.
  const fit = useCallback(() => {
    const box = main.current;
    if (!box) return;
    // The first slide's wash is always the first still and loads eagerly, even
    // when the slide itself shows the clip, so the shape never waits on a lazy photo.
    const first = box.querySelector<HTMLImageElement>(".gslide:first-child .gwash");
    if (!first || !first.naturalWidth || first.src.endsWith("placeholder.svg")) return;
    const r = Math.min(1.2, Math.max(0.62, first.naturalWidth / first.naturalHeight));
    box.style.setProperty("--r", r.toFixed(3));
    box.querySelectorAll<HTMLImageElement | HTMLVideoElement>(".gphoto, video").forEach((el) => {
      const w = el instanceof HTMLVideoElement ? el.videoWidth : el.naturalWidth;
      const h = el instanceof HTMLVideoElement ? el.videoHeight : el.naturalHeight;
      if (!w || !h) return;
      const close = Math.abs(w / h - r) / r <= 0.28;
      if (close) el.dataset.fit = "fill"; else delete el.dataset.fit;
    });
  }, []);
  useEffect(() => { fit(); }, [fit, active]);

  const go = useCallback((n: number) => {
    const el = track.current;
    if (!el || !N) return;
    const idx = ((n % N) + N) % N;
    const smooth = !window.matchMedia("(prefers-reduced-motion:reduce)").matches;
    el.scrollTo({ left: idx * el.clientWidth, behavior: smooth ? "smooth" : "auto" });
  }, [N]);

  const onScroll = () => {
    const el = track.current;
    if (!el) return;
    const idx = Math.round(el.scrollLeft / el.clientWidth);
    if (idx !== active && idx >= 0 && idx < N) { setActive(idx); setPaused(false); }
  };

  const toggle = () => {
    const v = track.current?.querySelector("video");
    if (!v) return;
    if (v.paused) { v.play().catch(() => {}); setPaused(false); } else { v.pause(); setPaused(true); }
  };

  const onKey = (e: React.KeyboardEvent) => {
    if (e.key === "ArrowRight") { e.preventDefault(); go(active + 1); }
    else if (e.key === "ArrowLeft") { e.preventDefault(); go(active - 1); }
  };

  return (
    <div className={`gallery${N > 1 ? " multi" : ""}`}>
      <div className="main" ref={main}>
        {/* Swipe (or trackpad / arrow keys) through the photos — no arrows over the picture. */}
        <div className="gtrack" ref={track} onScroll={onScroll} onKeyDown={onKey} tabIndex={0}
          role="group" aria-roledescription="carousel" aria-label={name ? `${name} — photos` : "Product photos"}>
          {slides.map((s, i) => {
            const still = s.kind === "video" ? images[0] : s.src;
            return (
              <div className="gslide" key={`${s.kind}:${s.src}`} aria-hidden={i !== active}>
                {/* a soft wash of the same photo fills whatever the photo's shape leaves */}
                <img className="gwash" src={still} alt="" aria-hidden="true" loading={i === 0 ? "eager" : "lazy"} decoding="async"
                  onLoad={i === 0 ? fit : undefined} />
                {s.kind === "video" && i === active ? (
                  <video
                    src={s.src}
                    poster={images[0]}
                    muted
                    autoPlay
                    loop
                    playsInline
                    preload="metadata"
                    onLoadedMetadata={fit}
                    aria-label={name ? `${name} — product video` : "Product video"}
                  />
                ) : (
                  <img
                    className="gphoto"
                    src={still}
                    alt={i === active ? name || "Product view" : ""}
                    loading={i === 0 ? "eager" : "lazy"}
                    fetchPriority={i === 0 ? "high" : "auto"}
                    decoding="async"
                    draggable={false}
                    onLoad={fit}
                    onError={(e) => { const t = e.currentTarget; if (!t.src.endsWith("placeholder.svg")) t.src = "/brand/placeholder.svg"; }}
                  />
                )}
              </div>
            );
          })}
        </div>

        {N > 1 && (
          <>
            {/* pointer devices: the picture's left and right halves step back and forward */}
            <button type="button" className="gzone prev" aria-label="Previous photo" tabIndex={-1} onClick={() => go(active - 1)} />
            <button type="button" className="gzone next" aria-label="Next photo" tabIndex={-1} onClick={() => go(active + 1)} />
            <div className="gsegs" role="tablist" aria-label="Photos">
              {slides.map((s, i) => (
                <button key={`${s.kind}:${s.src}`} type="button" role="tab" aria-selected={i === active}
                  aria-label={s.kind === "video" ? "Product video" : `Photo ${i + 1} of ${N}`}
                  className={i === active ? "on" : ""} onClick={() => go(i)} />
              ))}
            </div>
            <span className="gcount" aria-hidden="true">{active + 1} / {N}</span>
          </>
        )}
        {slides[active]?.kind === "video" && (
          <button type="button" className="gplay" onClick={toggle} aria-label={paused ? "Play video" : "Pause video"}>
            {paused
              ? <svg viewBox="0 0 24 24"><path d="M8 5.5v13l11-6.5z" /></svg>
              : <svg viewBox="0 0 24 24"><path d="M8 5h3v14H8zM13 5h3v14h-3z" /></svg>}
          </button>
        )}
      </div>
      {N > 1 && (
        <div className="thumbs">
          {slides.map((s, i) => (
            <button
              key={`${s.kind}:${s.src}`}
              className={`${i === active ? "active" : ""}${s.kind === "video" ? " vthumb" : ""}`}
              aria-label={s.kind === "video" ? "Play product video" : `Show photo ${i + 1}`}
              onClick={() => go(i)}
            >
              <img src={s.kind === "video" ? images[0] : s.src} alt="" loading="lazy" decoding="async" />
            </button>
          ))}
        </div>
      )}
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
export function StickyChrome({ name, sku, kes, usd, img, slug, variant, inStock }: {
  name: string; sku: string; kes: number; usd: number; img: string; slug: string;
  inStock?: boolean;
  /** The chosen variant's name, shown ahead of the mode on a variable product. */
  variant?: string;
}) {
  const router = useRouter();
  const measure = useMeasure();
  const tryAdd = useTryAdd(slug);
  const [saved, setSaved] = useState(false);
  const modeLook = !measure ? (inStock === false ? "Out of stock" : "In stock · ships today")
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
            <span style={{ minWidth: 0 }}><b>{name}</b><span className="bb-meta"><Money kes={kes} usd={usd} /> · {look}</span></span>
          </div>
          <div className="bb-ctas">
            <button className="bb-ico" aria-label="Add to cart" onClick={tryAdd}>
              <svg viewBox="0 0 24 24"><path d="M5 8h14l-1.2 11.2a2 2 0 0 1-2 1.8H8.2a2 2 0 0 1-2-1.8L5 8z" /><path d="M9 10V6.5a3 3 0 0 1 6 0V10" /></svg>
            </button>
            <button className="bb-ico bb-neema" aria-label={`Ask Neema about the ${name}`} onClick={() => openNeema(`Tell me about the ${name}`)}>
              <img src="/brand/mark-gold.png" alt="" />
            </button>
            <button className="pill pill-ghost bb-add" onClick={tryAdd}>Add to cart</button>
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
