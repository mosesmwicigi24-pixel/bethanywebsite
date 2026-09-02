"use client";

import Link from "next/link";
import { useRouter } from "next/navigation";
import { useCallback, useEffect, useRef, useState, useSyncExternalStore } from "react";
import { useCart } from "@/lib/cart";
import { Money } from "./Money";
import { CartIcon } from "./icons";

export interface ClipItem {
  slug: string;
  name: string;
  category: string;
  video: string;
  img: string;
  price: number;
  priceUsd: number;
  oldPrice?: number;
  chip?: string;
  producible: boolean;
}

/**
 * Vertical, full-screen, snap-scrolling clip feed — one product per screen,
 * the clip playing muted on loop while its screen is in view, with the
 * product's name, price and buy actions over it.
 *
 * Data discipline (most shoppers are on mobile data): only the current clip
 * and its two neighbours are mounted; the current one preloads fully, the
 * neighbours metadata only; everything else is just its poster. Reduced-
 * motion visitors get the poster and a play button instead of autoplay.
 */
export default function ClipFeed({ items }: { items: ClipItem[] }) {
  const router = useRouter();
  const { add } = useCart();
  const scroller = useRef<HTMLDivElement>(null);
  const slides = useRef<(HTMLElement | null)[]>([]);
  const videos = useRef<(HTMLVideoElement | null)[]>([]);
  const [current, setCurrent] = useState(0);
  const [paused, setPaused] = useState(false);
  // prefers-reduced-motion, read without a setState-in-effect (SSR snapshot: false).
  const reduced = useSyncExternalStore(
    (cb) => { const mq = window.matchMedia("(prefers-reduced-motion:reduce)"); mq.addEventListener("change", cb); return () => mq.removeEventListener("change", cb); },
    () => window.matchMedia("(prefers-reduced-motion:reduce)").matches,
    () => false,
  );
  const [saved, setSaved] = useState<Record<string, boolean>>({});
  const [added, setAdded] = useState<string | null>(null);
  const [toast, setToast] = useState<string | null>(null);

  const close = useCallback(() => {
    if (window.history.length > 1) router.back();
    else router.push("/");
  }, [router]);

  // Which screen is in view → the current clip.
  useEffect(() => {
    const els = slides.current.filter((el): el is HTMLElement => Boolean(el));
    if (!els.length) return;
    const io = new IntersectionObserver(
      (entries) => {
        for (const e of entries) {
          if (e.isIntersecting && e.intersectionRatio >= 0.6) {
            setCurrent(Number((e.target as HTMLElement).dataset.idx));
            setPaused(false);
          }
        }
      },
      { root: scroller.current, threshold: [0.6] },
    );
    els.forEach((el) => io.observe(el));
    return () => io.disconnect();
  }, [items.length]);

  // Play the current clip, pause the rest.
  useEffect(() => {
    videos.current.forEach((v, i) => {
      if (!v) return;
      if (i === current && !paused && !reduced) {
        v.play().catch(() => { /* autoplay blocked → tap to play */ });
      } else {
        v.pause();
      }
    });
  }, [current, paused, reduced]);

  const goTo = useCallback((i: number) => {
    const el = slides.current[i];
    if (el) el.scrollIntoView({ behavior: "smooth", block: "start" });
  }, []);

  // Keyboard: arrows / j / k step, Escape closes.
  useEffect(() => {
    const onKey = (e: KeyboardEvent) => {
      if (e.key === "ArrowDown" || e.key === "j") { e.preventDefault(); goTo(Math.min(items.length - 1, current + 1)); }
      else if (e.key === "ArrowUp" || e.key === "k") { e.preventDefault(); goTo(Math.max(0, current - 1)); }
      else if (e.key === "Escape") close();
    };
    window.addEventListener("keydown", onKey);
    return () => window.removeEventListener("keydown", onKey);
  }, [current, items.length, goTo, close]);

  const flash = (msg: string) => { setToast(msg); window.setTimeout(() => setToast(null), 1600); };

  const share = async (it: ClipItem) => {
    const url = `${window.location.origin}/product/${it.slug}`;
    try {
      if (navigator.share) await navigator.share({ title: it.name, url });
      else { await navigator.clipboard.writeText(url); flash("Link copied"); }
    } catch { /* cancelled */ }
  };

  const addToCart = (it: ClipItem) => {
    if (it.producible) { router.push(`/product/${it.slug}#look`); return; }
    add(it.slug);
    setAdded(it.slug);
    window.setTimeout(() => setAdded(null), 1600);
  };

  if (!items.length) {
    return (
      <div className="clips">
        <div className="clips-empty">
          <h1>No clips yet.</h1>
          <p>Product clips appear here as they are added. Browse the shop in the meantime.</p>
          <Link className="pill pill-gold" href="/shop">Browse the shop</Link>
          <button type="button" className="clips-close" aria-label="Close" onClick={close}>×</button>
        </div>
      </div>
    );
  }

  return (
    <div className="clips" role="region" aria-label="Product clips">
      <div className="clips-top">
        <button type="button" className="clips-close" aria-label="Close clips" onClick={close}>×</button>
        <span className="clips-title">Clips</span>
        <span className="clips-count">{current + 1} / {items.length}</span>
      </div>

      <div className="clips-scroller" ref={scroller}>
        {items.map((it, i) => {
          const near = Math.abs(i - current) <= 1;
          const isCur = i === current;
          return (
            <section
              key={it.slug}
              className="clip"
              data-idx={i}
              ref={(el) => { slides.current[i] = el; }}
              aria-label={it.name}
            >
              <div className="clip-stage" onClick={() => { if (isCur) setPaused((p) => !p); }}>
                <img className="clip-bg" src={it.img} alt="" aria-hidden="true" />
                {near ? (
                  <video
                    ref={(el) => { videos.current[i] = el; }}
                    src={it.video}
                    poster={it.img}
                    muted
                    loop
                    playsInline
                    preload={isCur ? "auto" : "metadata"}
                    aria-label={`${it.name} clip`}
                  />
                ) : (
                  <img className="clip-poster" src={it.img} alt="" />
                )}
                {isCur && (paused || reduced) && (
                  <span className="clip-play" aria-hidden="true">▶</span>
                )}

                <div className="clip-info">
                  <span className="cp cp-gold">{it.category}</span>
                  <h2>{it.name}</h2>
                  <div className="clip-price">
                    <b><Money kes={it.price} usd={it.priceUsd} /></b>
                    {it.oldPrice && it.oldPrice > it.price && <s><Money kes={it.oldPrice} usd={Math.round(it.oldPrice / 100)} /></s>}
                  </div>
                  {it.chip && <p>{it.chip}</p>}
                  <div className="clip-ctas">
                    <Link className="pill pill-gold" href={`/product/${it.slug}`} onClick={(e) => e.stopPropagation()}>View product</Link>
                    <button type="button" className="pill pill-ghost" onClick={(e) => { e.stopPropagation(); addToCart(it); }}>
                      <CartIcon />{added === it.slug ? "Added" : it.producible ? "Choose & buy" : "Add to cart"}
                    </button>
                  </div>
                </div>

                <div className="clip-rail">
                  <button type="button" aria-label={saved[it.slug] ? "Remove from wishlist" : "Save to wishlist"} aria-pressed={Boolean(saved[it.slug])}
                    className={saved[it.slug] ? "on" : ""}
                    onClick={(e) => { e.stopPropagation(); setSaved((s) => ({ ...s, [it.slug]: !s[it.slug] })); }}>
                    {saved[it.slug] ? "♥" : "♡"}<span>Save</span>
                  </button>
                  <button type="button" aria-label="Share" onClick={(e) => { e.stopPropagation(); share(it); }}>
                    <svg viewBox="0 0 24 24"><path d="M12 3v12M7 8l5-5 5 5M5 14v5a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-5" /></svg><span>Share</span>
                  </button>
                </div>
              </div>
            </section>
          );
        })}
      </div>

      {toast && <div className="clips-toast" role="status">{toast}</div>}
    </div>
  );
}
