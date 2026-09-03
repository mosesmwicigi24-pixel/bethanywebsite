"use client";

import { useEffect, useRef, useState } from "react";
import Img from "./Img";

/**
 * A product card's picture, which plays the product's clip when you look at it.
 *
 * The hub has converted a short silent MP4 per product for a while, and the
 * admin screen told the owner "shoppers see this on the product card and as the
 * first slide of the gallery" — while the storefront read no such field and
 * rendered no video anywhere. This is the card half of making that sentence
 * true.
 *
 * The still image is always rendered and never replaced: the video layers on
 * top and only becomes visible once it is actually playing. So a clip that is
 * slow, blocked, or broken costs the shopper nothing — they keep the photo they
 * would have had.
 *
 * Playing is driven by hover on a pointer device and by visibility on a phone,
 * where there is no hover to give. `preload="none"` means a rail of twelve
 * cards downloads twelve photos and zero videos until one is wanted.
 */
export default function CardMedia({
  src,
  video,
  alt,
  className,
}: {
  src: string;
  video?: string;
  alt?: string;
  className?: string;
}) {
  const hostRef = useRef<HTMLSpanElement | null>(null);
  const videoRef = useRef<HTMLVideoElement | null>(null);
  const [playing, setPlaying] = useState(false);
  const [dead, setDead] = useState(false);

  // A phone has no hover, so the clip plays while the card is on screen.
  // Respects reduced-motion by simply never starting.
  useEffect(() => {
    if (!video || dead) return;
    const host = hostRef.current;
    if (!host || typeof IntersectionObserver === "undefined") return;

    const fine = window.matchMedia?.("(hover: hover)")?.matches;
    const still = window.matchMedia?.("(prefers-reduced-motion: reduce)")?.matches;
    if (fine || still) return;

    const io = new IntersectionObserver(
      ([e]) => (e.isIntersecting ? play() : stop()),
      { threshold: 0.6 },
    );
    io.observe(host);

    return () => io.disconnect();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [video, dead]);

  const play = () => {
    const el = videoRef.current;
    if (!el || dead) return;
    // play() rejects on autoplay policy, a decode error, or a 404 — all of
    // which mean "keep showing the photo", not "show a broken box".
    el.play().then(() => setPlaying(true)).catch(() => setDead(true));
  };

  const stop = () => {
    const el = videoRef.current;
    setPlaying(false);
    if (el) {
      el.pause();
      try { el.currentTime = 0; } catch { /* not seekable yet */ }
    }
  };

  return (
    <span
      ref={hostRef}
      className={className}
      style={{ position: "relative", display: "block", overflow: "hidden" }}
      onMouseEnter={play}
      onMouseLeave={stop}
    >
      <Img src={src} alt={alt} />
      {video && !dead && (
        <video
          ref={videoRef}
          src={video}
          muted
          loop
          playsInline
          preload="none"
          aria-hidden="true"
          tabIndex={-1}
          onError={() => setDead(true)}
          style={{
            position: "absolute",
            inset: 0,
            width: "100%",
            height: "100%",
            objectFit: "cover",
            opacity: playing ? 1 : 0,
            transition: "opacity .25s ease",
            pointerEvents: "none",
          }}
        />
      )}
    </span>
  );
}
