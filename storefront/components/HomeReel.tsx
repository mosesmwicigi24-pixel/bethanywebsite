"use client";

import Link from "next/link";
import { useRouter } from "next/navigation";
import { useCallback, useEffect, useMemo, useRef, useState, useSyncExternalStore } from "react";
import { useCart } from "@/lib/cart";
import { Money } from "./Money";

/** One full-screen frame of the home reel: a product, or a CMS campaign. */
export interface ReelFrame {
  key: string;
  kind: "product" | "campaign";
  /** cart key — products only */
  slug?: string;
  href: string;
  eyebrow: string;
  title: string;
  sub?: string;
  img: string;
  alt: string;
  video?: string;
  price?: number;
  oldPrice?: number;
  producible?: boolean;
  /** campaign button text */
  cta?: string;
}

const STILL_MS = 6000;
const CLIP_MS = 9000;

/* Wishlist ("Like"): a slug list in localStorage, read as an external store so
   the server render (nothing liked) and the client agree on first paint. */
const WISH_KEY = "bh-wish-v1";
const WISH_EVENT = "bh:wish";
const wishStore = {
  sub: (cb: () => void) => {
    window.addEventListener("storage", cb);
    window.addEventListener(WISH_EVENT, cb);
    return () => { window.removeEventListener("storage", cb); window.removeEventListener(WISH_EVENT, cb); };
  },
  get: () => { try { return localStorage.getItem(WISH_KEY) ?? ""; } catch { return ""; } },
  set: (slugs: string[]) => {
    try { localStorage.setItem(WISH_KEY, JSON.stringify(slugs)); } catch { /* blocked storage */ }
    window.dispatchEvent(new Event(WISH_EVENT));
  },
};
const parseWish = (raw: string): string[] => {
  try { const v = JSON.parse(raw); return Array.isArray(v) ? v : []; } catch { return []; }
};

/** "*word*" → gold italic emphasis, as in the CMS hero headlines. */
function headline(t: string) {
  return t.split(/\*([^*]+)\*/).map((part, i) => (i % 2 === 1 ? <em key={i}>{part}</em> : part));
}

const reducedStore = {
  sub: (cb: () => void) => {
    const mq = window.matchMedia("(prefers-reduced-motion:reduce)");
    mq.addEventListener("change", cb);
    return () => mq.removeEventListener("change", cb);
  },
  get: () => window.matchMedia("(prefers-reduced-motion:reduce)").matches,
};

/**
 * The homepage's opening screen, shaped like a shopping app's feed
 * (jojosfashion.com on a phone): one product fills the screen edge to edge,
 * its name and price sit bottom-left, and a rail of round actions — Like,
 * View, Share, Buy Now — sits on the right. Swipe sideways for the next
 * product; story-style segments at the top count down to the auto-advance.
 *
 * Portrait and full-bleed under ~768px; from tablets up the same frame turns
 * landscape — a blurred wash of the photo across the stage, the product as a
 * tall poster on the right, the story on the left.
 *
 * Only the current frame mounts its clip; every other frame is its still.
 */
export default function HomeReel({ frames }: { frames: ReelFrame[] }) {
  const router = useRouter();
  const { add } = useCart();
  const root = useRef<HTMLElement>(null);
  const track = useRef<HTMLDivElement>(null);
  const [i, setI] = useState(0);
  const iRef = useRef(0);
  useEffect(() => { iRef.current = i; }, [i]);
  const [round, setRound] = useState(0); // restarts the current segment's countdown
  const [hold, setHold] = useState(false); // finger down / pointer over
  const [away, setAway] = useState(false); // scrolled off-screen or tab hidden
  const wishRaw = useSyncExternalStore(wishStore.sub, wishStore.get, () => "");
  const wish = useMemo(() => parseWish(wishRaw), [wishRaw]);
  const [burst, setBurst] = useState<{ x: number; y: number; n: number } | null>(null);
  const [toast, setToast] = useState<string | null>(null);
  const [added, setAdded] = useState<string | null>(null);
  const [nudge, setNudge] = useState(false);
  const reduced = useSyncExternalStore(reducedStore.sub, reducedStore.get, () => false);
  const N = frames.length;
  const f = frames[i] ?? frames[0];

  const go = useCallback((n: number) => {
    const el = track.current;
    if (!el || !N) return;
    const idx = ((n % N) + N) % N;
    const smooth = !window.matchMedia("(prefers-reduced-motion:reduce)").matches;
    el.scrollTo({ left: idx * el.clientWidth, behavior: smooth ? "smooth" : "auto" });
    if (idx === iRef.current) setRound((r) => r + 1);
  }, [N]);

  const onScroll = useCallback(() => {
    const el = track.current;
    if (!el) return;
    const idx = Math.round(el.scrollLeft / el.clientWidth);
    if (idx !== iRef.current && idx >= 0 && idx < N) {
      setI(idx);
      setRound((r) => r + 1);
    }
  }, [N]);

  // Off-screen or hidden tab → stop the clock and the clip. Also tells the
  // nav whether it is floating over the reel (transparent) or past it (solid).
  useEffect(() => {
    const el = root.current;
    if (!el) return;
    const html = document.documentElement;
    const io = new IntersectionObserver(([e]) => {
      setAway(!e.isIntersecting);
      if (e.intersectionRatio < 0.12) html.dataset.reelPast = "1";
      else delete html.dataset.reelPast;
    }, { threshold: [0, 0.12, 0.5] });
    io.observe(el);
    const onVis = () => setAway(document.hidden);
    document.addEventListener("visibilitychange", onVis);
    return () => {
      io.disconnect();
      document.removeEventListener("visibilitychange", onVis);
      delete html.dataset.reelPast;
    };
  }, []);

  // Keep the current frame aligned when the stage resizes (rotation, URL bar).
  useEffect(() => {
    const el = track.current;
    if (!el) return;
    const ro = new ResizeObserver(() => { el.scrollLeft = iRef.current * el.clientWidth; });
    ro.observe(el);
    return () => ro.disconnect();
  }, []);

  // First visit on a phone: nudge the track so the swipe is discoverable.
  useEffect(() => {
    const el = track.current;
    if (!el || N < 2) return;
    if (!window.matchMedia("(max-width:767px) and (prefers-reduced-motion:no-preference)").matches) return;
    try { if (localStorage.getItem("bh-reel-nudged")) return; localStorage.setItem("bh-reel-nudged", "1"); } catch { return; }
    // A transform, not scrollTo: mandatory snapping would cancel a small scroll.
    const on = window.setTimeout(() => { if (el.scrollLeft === 0) setNudge(true); }, 1800);
    const off = window.setTimeout(() => setNudge(false), 2900);
    return () => { window.clearTimeout(on); window.clearTimeout(off); };
  }, [N]);

  const running = !hold && !away && !reduced && N > 1;

  // Clip plays only while the reel is live.
  const video = useRef<HTMLVideoElement | null>(null);
  useEffect(() => {
    const v = video.current;
    if (!v) return;
    if (!away && !reduced) v.play().catch(() => { /* autoplay refused — the still shows */ });
    else v.pause();
  }, [i, away, reduced]);

  const flash = (msg: string) => {
    setToast(msg);
    window.setTimeout(() => setToast(null), 1700);
  };

  const like = (fr: ReelFrame, force?: boolean) => {
    const id = fr.slug ?? fr.key;
    const on = force ?? !wish.includes(id);
    if (on === wish.includes(id)) return;
    wishStore.set(on ? [...wish, id] : wish.filter((s) => s !== id));
  };

  const share = async (fr: ReelFrame) => {
    const url = `${window.location.origin}${fr.href}`;
    try {
      if (navigator.share) await navigator.share({ title: fr.title.replace(/\*/g, ""), url });
      else { await navigator.clipboard.writeText(url); flash("Link copied"); }
    } catch { /* share sheet dismissed */ }
  };

  const buy = (fr: ReelFrame) => {
    if (fr.kind !== "product" || !fr.slug) { router.push(fr.href); return; }
    // Made-to-measure pieces need sizes first — the product page's fitting step.
    if (fr.producible) { router.push(`${fr.href}#look`); return; }
    add(fr.slug);
    setAdded(fr.slug);
    window.setTimeout(() => setAdded(null), 1600);
  };

  // Double-tap the photo to like it — the feed-app gesture.
  const lastTap = useRef(0);
  const onStageTap = (e: React.MouseEvent<HTMLDivElement>, fr: ReelFrame) => {
    const now = e.timeStamp;
    if (now - lastTap.current < 320) {
      const box = e.currentTarget.getBoundingClientRect();
      setBurst({ x: e.clientX - box.left, y: e.clientY - box.top, n: now });
      like(fr, true);
      lastTap.current = 0;
    } else lastTap.current = now;
  };

  const onKey = (e: React.KeyboardEvent) => {
    if (e.key === "ArrowRight") { e.preventDefault(); go(i + 1); }
    else if (e.key === "ArrowLeft") { e.preventDefault(); go(i - 1); }
  };

  if (!N) return null;
  const isLiked = wish.includes(f.slug ?? f.key);
  const dur = f.video ? CLIP_MS : STILL_MS;

  return (
    <section
      ref={root}
      className="reel"
      aria-roledescription="carousel"
      aria-label="Featured from Bethany House"
      onKeyDown={onKey}
      onPointerEnter={(e) => { if (e.pointerType === "mouse") setHold(true); }}
      onPointerLeave={(e) => { if (e.pointerType === "mouse") setHold(false); }}
    >
      <div
        className={`reel-track${nudge ? " nudge" : ""}`}
        ref={track}
        onScroll={onScroll}
        onTouchStart={() => setHold(true)}
        onTouchEnd={() => setHold(false)}
        onTouchCancel={() => setHold(false)}
      >
        {frames.map((fr, idx) => {
          const near = Math.abs(idx - i) <= 1 || (i === N - 1 && idx === 0);
          const cur = idx === i;
          return (
            <article
              key={fr.key}
              className={`reel-frame${cur ? " cur" : ""}${fr.kind === "campaign" ? " camp" : ""}`}
              aria-roledescription="slide"
              aria-label={`${idx + 1} of ${N}: ${fr.title.replace(/\*/g, "")}`}
              aria-hidden={!cur}
            >
              <div className="reel-stage" onClick={(e) => onStageTap(e, fr)}>
                <img className="reel-wash" src={fr.img} alt="" aria-hidden="true"
                  loading={near ? "eager" : "lazy"} decoding="async" />
                <div className="reel-poster">
                  <img
                    className="reel-img"
                    src={fr.img}
                    alt={fr.alt}
                    loading={near ? "eager" : "lazy"}
                    fetchPriority={idx === 0 ? "high" : "auto"}
                    decoding={idx === 0 ? "sync" : "async"}
                    draggable={false}
                  />
                  {cur && fr.video && !reduced ? (
                    <video
                      ref={video}
                      className="reel-vid"
                      src={fr.video}
                      poster={fr.img}
                      muted
                      loop
                      playsInline
                      autoPlay
                      preload="auto"
                      aria-hidden="true"
                    />
                  ) : null}
                </div>
                {cur && burst ? (
                  <span key={burst.n} className="reel-burst" style={{ left: burst.x, top: burst.y }} aria-hidden="true">
                    <HeartIcon on />
                  </span>
                ) : null}
              </div>

              <div className="reel-info">
                <span className="reel-eyebrow">{fr.eyebrow}</span>
                <h2 className="reel-title">
                  <Link href={fr.href} tabIndex={cur ? 0 : -1}>{headline(fr.title)}</Link>
                </h2>
                {fr.sub ? <p className="reel-sub">{fr.sub}</p> : null}
                {fr.price ? (
                  <div className="reel-price">
                    <b><Money kes={fr.price} /></b>
                    {fr.oldPrice && fr.oldPrice > fr.price ? (
                      <>
                        <s><Money kes={fr.oldPrice} /></s>
                        <span className="reel-save">−{Math.round((1 - fr.price / fr.oldPrice) * 100)}%</span>
                      </>
                    ) : null}
                  </div>
                ) : null}
                <div className="reel-ctas">
                  <button type="button" className="pill pill-gold" tabIndex={cur ? 0 : -1} onClick={() => buy(fr)}>
                    {fr.kind === "campaign" ? fr.cta || "Shop now"
                      : added === fr.slug ? "Added to cart ✓"
                      : fr.producible ? "Choose size & buy" : "Buy now"}
                  </button>
                  <Link className="pill reel-ghost" href={fr.href} tabIndex={cur ? 0 : -1}>
                    {fr.kind === "campaign" ? "Learn more" : "View details"}
                  </Link>
                </div>
              </div>
            </article>
          );
        })}
      </div>

      {/* story segments — the current one fills over its dwell time */}
      <div className="reel-segs" aria-hidden="true">
        {frames.map((fr, idx) => (
          <span key={fr.key} className={idx < i ? "done" : idx === i ? "now" : ""}>
            {idx === i && !reduced ? (
              <i
                key={`${i}-${round}`}
                style={{ animationDuration: `${dur}ms`, animationPlayState: running ? "running" : "paused" }}
                onAnimationEnd={() => go(i + 1)}
              />
            ) : <i />}
          </span>
        ))}
      </div>

      {/* action rail — acts on the frame in view */}
      <div className="reel-rail">
        <button type="button" className={`rr${isLiked ? " on" : ""}`} aria-pressed={isLiked}
          aria-label={isLiked ? "Remove from wishlist" : "Like — save to wishlist"} onClick={() => like(f)}>
          <span className="rr-ico"><HeartIcon on={isLiked} /></span>
          <span className="rr-lbl">{isLiked ? "Liked" : "Like"}</span>
        </button>
        <Link className="rr" href={f.href} aria-label={`View ${f.title.replace(/\*/g, "")}`}>
          <span className="rr-ico">
            <svg viewBox="0 0 24 24"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z" /><circle cx="12" cy="12" r="3" /></svg>
          </span>
          <span className="rr-lbl">View</span>
        </Link>
        <button type="button" className="rr" aria-label="Share" onClick={() => share(f)}>
          <span className="rr-ico">
            <svg viewBox="0 0 24 24"><path d="M12 15V3M7 8l5-5 5 5M5 13v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6" /></svg>
          </span>
          <span className="rr-lbl">Share</span>
        </button>
        <button type="button" className={`rr rr-buy${added && added === f.slug ? " ok" : ""}`} onClick={() => buy(f)}
          aria-label={f.kind === "campaign" ? f.cta || "Shop now" : `Buy ${f.title}`}>
          <span className="rr-ico">
            {added && added === f.slug ? (
              <svg viewBox="0 0 24 24"><path d="m5 12.5 4.5 4.5L19 7.5" /></svg>
            ) : (
              <svg viewBox="0 0 24 24"><path d="M5 8h14l-1.2 11.2a2 2 0 0 1-2 1.8H8.2a2 2 0 0 1-2-1.8L5 8z" /><path d="M9 10V6.5a3 3 0 0 1 6 0V10" /></svg>
            )}
          </span>
          <span className="rr-lbl">{f.kind === "campaign" ? "Shop" : added === f.slug ? "Added" : "Buy Now"}</span>
        </button>
      </div>

      {/* larger screens: arrows, counter and the up-next strip */}
      <div className="reel-deck">
        <span className="reel-count"><b>{String(i + 1).padStart(2, "0")}</b> / {String(N).padStart(2, "0")}</span>
        <button type="button" className="reel-arrow" aria-label="Previous" onClick={() => go(i - 1)}>
          <svg viewBox="0 0 24 24"><path d="m15 5-7 7 7 7" /></svg>
        </button>
        <button type="button" className="reel-arrow" aria-label="Next" onClick={() => go(i + 1)}>
          <svg viewBox="0 0 24 24"><path d="m9 5 7 7-7 7" /></svg>
        </button>
        <div className="reel-next" role="tablist" aria-label="Choose a frame">
          {frames.map((fr, idx) => (
            <button key={fr.key} type="button" role="tab" aria-selected={idx === i}
              aria-label={fr.title.replace(/\*/g, "")} className={idx === i ? "on" : ""} onClick={() => go(idx)}>
              <img src={fr.img} alt="" loading="lazy" decoding="async" />
            </button>
          ))}
        </div>
      </div>

      {toast ? <div className="reel-toast" role="status">{toast}</div> : null}
    </section>
  );
}

function HeartIcon({ on }: { on?: boolean }) {
  return (
    <svg viewBox="0 0 24 24" className={on ? "fill" : ""}>
      <path d="M12 20.5s-7.5-4.6-9.3-9.4C1.4 7.6 3.6 4 7.2 4c2 0 3.6 1.1 4.8 2.8C13.2 5.1 14.8 4 16.8 4c3.6 0 5.8 3.6 4.5 7.1-1.8 4.8-9.3 9.4-9.3 9.4z" />
    </svg>
  );
}
