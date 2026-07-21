"use client";

import Link from "next/link";
import { useCallback, useEffect, useRef, useState } from "react";
import { Money } from "./Money";

const N = 3;
const INTERVAL = 6000;

/** Auto-rotating campaign hero, oraimo-style: native scroll-snap slides
    (a slide can never rest half-visible), swipe on touch, arrows + pill
    dots, hover-pause, prefers-reduced-motion aware. */
export default function HeroCarousel() {
  const track = useRef<HTMLDivElement>(null);
  const [i, setI] = useState(0);
  const [paused, setPaused] = useState(false);
  const iRef = useRef(0);
  iRef.current = i;
  // The hero is an x-only scroller (overflow-y:hidden on .hero-track), so a
  // vertical wheel/touch over it chains to the document natively — no JS wheel
  // forwarding (that ran on the main thread and made page scroll feel sluggish).
  // `track` still drives snap position (go / onScroll) below.

  const go = useCallback((n: number) => {
    const el = track.current;
    if (!el) return;
    const idx = ((n % N) + N) % N;
    const reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    el.scrollTo({ left: idx * el.clientWidth, behavior: reduce ? "auto" : "smooth" });
  }, []);

  // Track which slide is settled in view (native scroll → dots stay honest)
  const onScroll = useCallback(() => {
    const el = track.current;
    if (!el) return;
    const idx = Math.round(el.scrollLeft / el.clientWidth);
    if (idx !== iRef.current && idx >= 0 && idx < N) setI(idx);
  }, []);

  useEffect(() => {
    if (paused) return;
    if (typeof window !== "undefined" && window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;
    const t = setInterval(() => go(iRef.current + 1), INTERVAL);
    return () => clearInterval(t);
  }, [paused, go]);

  return (
    <section
      className="hero-car"
      aria-roledescription="carousel"
      onMouseEnter={() => setPaused(true)}
      onMouseLeave={() => setPaused(false)}
      onTouchStart={() => setPaused(true)}
      onTouchEnd={() => setPaused(false)}
    >
      <div className="hero-track" ref={track} onScroll={onScroll}>

        {/* Slide 1 — cathedral */}
        <div className="hero-slide" aria-hidden={i !== 0}>
          <div className="hero-cath">
            <div className="wrap">
              <div className="hero-copy">
                <span className="eyebrow">Nairobi · Serving churches across East Africa</span>
                <h1>Everything the <em>altar</em> calls for.</h1>
                <p className="sub">
                  Holy Communion elements, tailored clergy apparel and Christian gifts —
                  chosen with reverence, delivered with care.
                </p>
                <div className="ctas">
                  <Link className="pill pill-gold" href="/shop">Shop Communion</Link>
                  <Link className="pill pill-ghost-dark" href="/shop">Explore Clergy Apparel</Link>
                </div>
                <div className="marks">
                  <div className="mark"><b>Same-day</b>Nairobi delivery</div>
                  <div className="mark"><b>Made-to-measure</b>vestments &amp; gowns</div>
                  <div className="mark"><b>M-Pesa &amp; Card</b>secure checkout</div>
                </div>
              </div>
              <div className="arch">
                <img src="/products/Chalice_Cup.jpg" alt="Chalice Royale — gold chalice and paten set" />
                <div className="plate">
                  <span className="nm">Chalice Royale</span>
                  <span className="pr"><Money kes={18500} usd={142} /></span>
                  <Link href="/product/chalice-royale">View</Link>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Slide 2 — Holy Week bundle (light) */}
        <div className="hero-slide" aria-hidden={i !== 1}>
          <div className="hero-cath light">
            <div className="wrap">
              <div className="hero-copy">
                <span className="eyebrow">Holy Week Offer</span>
                <h1>The Lord&apos;s Table, <em>complete</em>.</h1>
                <p className="sub">
                  Chalice, altar wine and 1,000 hosts — one bundle, one delivery,
                  ready before Holy Week. From KES 21,800.
                </p>
                <div className="ctas">
                  <Link className="pill pill-gold" href="/product/chalice-royale">Shop the bundle</Link>
                  <Link className="pill pill-ghost" href="/shop">See communion sets</Link>
                </div>
                <div className="marks">
                  <div className="mark"><b>Save 15%</b>vs buying separately</div>
                  <div className="mark"><b>Free engraving</b>on the chalice</div>
                  <div className="mark"><b>Guaranteed</b>pre-Easter delivery</div>
                </div>
              </div>
              <div className="arch">
                <img src="/products/gold-wares.jpg" alt="Communion ware set" />
                <div className="plate">
                  <span className="nm">Communion Bundle</span>
                  <span className="pr">from <Money kes={21800} usd={170} /></span>
                  <Link href="/product/chalice-royale">View</Link>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Slide 3 — made to measure (slate) */}
        <div className="hero-slide" aria-hidden={i !== 2}>
          <div className="hero-cath slate">
            <div className="wrap">
              <div className="hero-copy">
                <span className="eyebrow">Made to Measure</span>
                <h1>Tailored for the <em>pulpit</em>.</h1>
                <p className="sub">
                  Preaching gowns, cassocks and chasubles — measured in Nairobi,
                  sewn to your order, ready in 5–7 days.
                </p>
                <div className="ctas">
                  <Link className="pill pill-gold" href="/shop">Book a fitting</Link>
                  <Link className="pill pill-ghost-dark" href="/shop">Browse apparel</Link>
                </div>
                <div className="marks">
                  <div className="mark"><b>5–7 days</b>measure to delivery</div>
                  <div className="mark"><b>Every colour</b>of the liturgical year</div>
                  <div className="mark"><b>Repairs</b>&amp; alterations after</div>
                </div>
              </div>
              <div className="arch">
                <img src="/products/preaching_gown1.jpg" alt="Tailored preaching gown" />
                <div className="plate">
                  <span className="nm">Preaching Gown</span>
                  <span className="pr"><Money kes={13000} usd={130} /></span>
                  <Link href="/product/preaching-gown">View</Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <button className="hero-nav prev" aria-label="Previous slide" onClick={() => go(i - 1)}>‹</button>
      <button className="hero-nav next" aria-label="Next slide" onClick={() => go(i + 1)}>›</button>
      <div className="hero-dots" role="tablist" aria-label="Slides">
        {[0, 1, 2].map((n) => (
          <button key={n} role="tab" aria-selected={i === n} aria-label={`Slide ${n + 1}`}
            className={i === n ? "on" : ""} onClick={() => go(n)} />
        ))}
      </div>
    </section>
  );
}
