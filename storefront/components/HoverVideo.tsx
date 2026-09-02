"use client";

import { useEffect, useRef, useState } from "react";

const PLACEHOLDER = "/brand/placeholder.svg";

/**
 * Product media that plays a short, silent clip when the shopper hovers the
 * card — the jojosfashion.com catalogue pattern.
 *
 * How it stays cheap:
 *   • The still <img> is always rendered (lazy, indexable, the LCP candidate).
 *     The <video> is only MOUNTED on the first hover/focus, so a grid of forty
 *     cards costs zero video bytes until the cursor actually lands on one.
 *   • A 120ms arm delay means sweeping the cursor across the grid doesn't
 *     start a download for every card it crosses.
 *   • The clip fades over the still only once the first frame is actually
 *     playing (no black flash, no layout shift); on leave it pauses, rewinds
 *     and fades back, so re-entering restarts from the top like jojo's cards.
 *   • Touch devices (no hover), reduced-motion and Save-Data users never load
 *     a clip. They see a small ▶ chip so they know a clip is waiting on the
 *     product page, which plays it inline.
 *
 * The hover target is the element that WRAPS this component (the card's `.ph`
 * link), so the whole tile — padding included — triggers playback, and the
 * link's keyboard focus plays it too.
 */
export default function HoverVideo({
  src,
  video,
  alt = "",
  className,
}: {
  src: string;
  video?: string;
  alt?: string;
  className?: string;
}) {
  const wrap = useRef<HTMLSpanElement>(null);
  const vid = useRef<HTMLVideoElement>(null);
  const timer = useRef<number | undefined>(undefined);
  const [failed, setFailed] = useState(false);       // still failed → placeholder
  const [broken, setBroken] = useState(false);       // clip failed → behave as image-only
  const [armed, setArmed] = useState(false);         // <video> mounted (first hover)
  const [hover, setHover] = useState(false);         // cursor/focus on the tile
  const [on, setOn] = useState(false);               // first frame playing → show clip

  const hasClip = Boolean(video) && !broken;

  useEffect(() => {
    const el = wrap.current;
    const host = el?.parentElement ?? el;
    if (!el || !host || !hasClip) return;

    const allowed = () =>
      window.matchMedia("(hover:hover)").matches &&
      !window.matchMedia("(prefers-reduced-motion:reduce)").matches &&
      !(navigator as Navigator & { connection?: { saveData?: boolean } }).connection?.saveData;

    const enter = () => {
      if (!allowed()) return;
      window.clearTimeout(timer.current);
      timer.current = window.setTimeout(() => { setArmed(true); setHover(true); }, 120);
    };
    const leave = () => {
      window.clearTimeout(timer.current);
      setHover(false);
      setOn(false);
      const v = vid.current;
      if (v) { v.pause(); try { v.currentTime = 0; } catch { /* not seekable yet */ } }
    };

    host.addEventListener("pointerenter", enter);
    host.addEventListener("pointerleave", leave);
    host.addEventListener("focusin", enter);
    host.addEventListener("focusout", leave);
    return () => {
      window.clearTimeout(timer.current);
      host.removeEventListener("pointerenter", enter);
      host.removeEventListener("pointerleave", leave);
      host.removeEventListener("focusin", enter);
      host.removeEventListener("focusout", leave);
    };
  }, [hasClip]);

  // Play (from the top) whenever the tile is hovered and the element exists.
  useEffect(() => {
    const v = vid.current;
    if (!hover || !v) return;
    try { v.currentTime = 0; } catch { /* metadata not loaded yet — starts at 0 anyway */ }
    v.play().catch(() => { /* autoplay blocked → the still simply stays */ });
  }, [hover, armed]);

  return (
    <span ref={wrap} className={`hv${on ? " on" : ""}${hasClip ? " has-clip" : ""}`}>
      <img
        src={failed ? PLACEHOLDER : src}
        alt={alt}
        className={className}
        loading="lazy"
        onError={() => { if (!failed) setFailed(true); }}
      />
      {armed && hasClip && (
        <video
          ref={vid}
          src={video}
          muted
          loop
          playsInline
          preload="auto"
          aria-hidden="true"
          tabIndex={-1}
          onPlaying={() => setOn(true)}
          onError={() => { setBroken(true); setOn(false); }}
        />
      )}
      {hasClip && <span className="hv-play" aria-hidden="true">▶</span>}
    </span>
  );
}
