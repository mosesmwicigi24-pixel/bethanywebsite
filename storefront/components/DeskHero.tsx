"use client";

import Link from "next/link";
import { useCallback, useEffect, useRef, useState, useSyncExternalStore } from "react";
import { Money } from "./Money";

export interface HeroSlide {
  key: string;
  /** department label — "COMMUNION", "VESTMENTS" … */
  eyebrow: string;
  title: string;
  line: string;
  /** "From" price in KES; omitted when the catalogue has none */
  from?: number;
  href: string;
  collection: string;
  /** wide (2400×1000) designed photo + a portrait thumbnail for the stack */
  img: string;
  thumb: string;
  alt: string;
}

const DWELL = 7000;
/** Phones never download the slider's photos: their <source> only matches from 768px. */
const BLANK = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7";

const reducedStore = {
  sub: (cb: () => void) => {
    const mq = window.matchMedia("(prefers-reduced-motion:reduce)");
    mq.addEventListener("change", cb);
    return () => mq.removeEventListener("change", cb);
  },
  get: () => window.matchMedia("(prefers-reduced-motion:reduce)").matches,
};

/**
 * The tablet/desktop homepage slider — a blend of jojosfashion.com and
 * ke.oraimo.com in Bethany's colours: a full-width designed photo, the story
 * bottom-left (department · number, large Manrope title, "From" price, one
 * line, two square buttons), a "This week at the altar" stack of slide cards
 * on the right whose current card fills with a gold progress line, progress
 * bars with pause along the bottom, and a trust card overlapping the edge.
 *
 * Phones never render it (CSS), and never download its photos.
 */
export default function DeskHero({ slides }: { slides: HeroSlide[] }) {
  const N = slides.length;
  const [i, setI] = useState(0);
  const [paused, setPaused] = useState(false);
  const [hover, setHover] = useState(false);
  const [away, setAway] = useState(false);
  const [round, setRound] = useState(0);
  const root = useRef<HTMLElement>(null);
  const reduced = useSyncExternalStore(reducedStore.sub, reducedStore.get, () => false);
  const running = !paused && !hover && !away && !reduced && N > 1;

  const go = useCallback((n: number) => {
    setI(((n % N) + N) % N);
    setRound((r) => r + 1);
  }, [N]);

  // Off-screen / hidden tab stops the clock; also tells the header whether it
  // floats over the slider (transparent) or sits past it (solid). Only acts
  // while the slider is actually displayed (it is display:none on phones).
  useEffect(() => {
    const el = root.current;
    if (!el) return;
    const html = document.documentElement;
    const io = new IntersectionObserver(([e]) => {
      if (!el.getClientRects().length) return;
      setAway(!e.isIntersecting);
      if (e.intersectionRatio < 0.12) html.dataset.reelPast = "1";
      else delete html.dataset.reelPast;
    }, { threshold: [0, 0.12, 0.5] });
    io.observe(el);
    const onVis = () => setAway(document.hidden);
    document.addEventListener("visibilitychange", onVis);
    return () => { io.disconnect(); document.removeEventListener("visibilitychange", onVis); };
  }, []);

  const onKey = (e: React.KeyboardEvent) => {
    if (e.key === "ArrowRight") { e.preventDefault(); go(i + 1); }
    else if (e.key === "ArrowLeft") { e.preventDefault(); go(i - 1); }
  };

  if (!N) return null;
  const s = slides[i];
  const num = (n: number) => String(n + 1).padStart(2, "0");

  return (
    <section
      ref={root}
      className="dhero"
      aria-roledescription="carousel"
      aria-label="This week at Bethany House"
      onKeyDown={onKey}
      onMouseEnter={() => setHover(true)}
      onMouseLeave={() => setHover(false)}
    >
      <div className="dh-stage">
        {slides.map((sl, n) => (
          <picture key={sl.key} className={`dh-photo${n === i ? " on" : ""}`} aria-hidden={n !== i}>
            <source media="(min-width:768px)" srcSet={sl.img} />
            <img src={BLANK} alt={n === i ? sl.alt : ""} fetchPriority={n === 0 ? "high" : "low"}
              loading={n === 0 ? "eager" : "lazy"} decoding="async" draggable={false} />
          </picture>
        ))}
        <span className="dh-scrim" aria-hidden="true" />
      </div>

      <div className="dh-copy" key={`copy-${i}`} aria-live="polite">
        <span className="dh-eyebrow">{s.eyebrow}<i aria-hidden="true" />{num(i)}</span>
        <h2 className="dh-title">{s.title}</h2>
        {s.from ? <p className="dh-from">From <Money kes={s.from} /></p> : null}
        <p className="dh-line">{s.line}</p>
        <div className="dh-ctas">
          <Link className="dh-btn dh-gold" href={s.href}>
            View product
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z" /><circle cx="12" cy="12" r="3" /></svg>
          </Link>
          <Link className="dh-btn dh-ghost" href={s.collection}>Browse collection</Link>
        </div>
      </div>

      <aside className="dh-stack" aria-label="Choose a slide">
        <div className="dh-stack-h">
          <span>This week at the altar <b>{num(i)}/{String(N).padStart(2, "0")}</b></span>
        </div>
        {slides.map((sl, n) => (
          <button key={sl.key} type="button" className={`dh-card${n === i ? " on" : ""}`}
            aria-current={n === i} onClick={() => go(n)}>
            <picture>
              <source media="(min-width:768px)" srcSet={sl.thumb} />
              <img src={BLANK} alt="" loading="lazy" decoding="async" />
            </picture>
            <span className="dh-card-t"><small>{sl.eyebrow}</small><b>{sl.title}</b></span>
            {n === i ? (
              <i key={`p-${i}-${round}`} className="dh-prog"
                style={{ animationDuration: `${DWELL}ms`, animationPlayState: running ? "running" : "paused" }}
                onAnimationEnd={() => go(i + 1)} aria-hidden="true" />
            ) : null}
          </button>
        ))}
      </aside>

      <button type="button" className="dh-arrow prev" aria-label="Previous slide" onClick={() => go(i - 1)}>
        <svg viewBox="0 0 24 24"><path d="m15 5-7 7 7 7" /></svg>
      </button>
      <button type="button" className="dh-arrow next" aria-label="Next slide" onClick={() => go(i + 1)}>
        <svg viewBox="0 0 24 24"><path d="m9 5 7 7-7 7" /></svg>
      </button>

      <div className="dh-bars">
        {slides.map((sl, n) => (
          <button key={sl.key} type="button" aria-label={`Slide ${n + 1}: ${sl.title}`}
            className={n < i ? "done" : n === i ? "now" : ""} onClick={() => go(n)}>
            {n === i ? (
              <i key={`b-${i}-${round}`}
                style={{ animationDuration: `${DWELL}ms`, animationPlayState: running ? "running" : "paused" }} />
            ) : <i />}
          </button>
        ))}
        <button type="button" className="dh-pause" aria-pressed={paused}
          aria-label={paused ? "Play slides" : "Pause slides"} onClick={() => setPaused((p) => !p)}>
          {paused
            ? <svg viewBox="0 0 24 24"><path d="M8 5.5v13l11-6.5z" /></svg>
            : <svg viewBox="0 0 24 24"><path d="M8 5h3v14H8zM13 5h3v14h-3z" /></svg>}
        </button>
      </div>

      <ul className="dh-trust" aria-label="Why shop with Bethany House">
        <li><svg viewBox="0 0 24 24"><path d="M3 7h11v10H3zM14 10h4l3 3v4h-7zM7 20a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm11 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" /></svg>Same-day Nairobi delivery</li>
        <li><svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2" /><path d="M2 10h20" /></svg>M-Pesa, card &amp; cash on delivery</li>
        <li><svg viewBox="0 0 24 24"><path d="M4 20h4L19 9l-4-4L4 16zM13.5 6.5l4 4" /></svg>Free engraving on communion ware</li>
        <li><svg viewBox="0 0 24 24"><path d="M12 3 4 6v6c0 5 3.4 7.7 8 9 4.6-1.3 8-4 8-9V6l-8-3z" /><path d="m9 12 2 2 4-4" /></svg>Sanctuary-grade guarantee</li>
      </ul>
    </section>
  );
}
