"use client";

import Link from "next/link";
import { useCallback, useEffect, useRef, useState } from "react";
import { Money } from "./Money";
import type { ContentBlock } from "@/lib/theme";

const INTERVAL = 6000;

/** A single CMS-managed hero slide (home_hero banner). Uses the same cathedral
    layout as the built-in slides; styles JSON carries the eyebrow, theme and an
    optional price plate. */
function CmsSlide({ s, active }: { s: ContentBlock; active: boolean }) {
  const st = (s.styles ?? {}) as Record<string, string>;
  const theme = st.theme === "light" ? " light" : st.theme === "slate" ? " slate" : "";
  const cta2 = st.cta2_text && st.cta2_url;
  return (
    <div className="hero-slide" aria-hidden={!active}>
      <div className={`hero-cath${theme}`}>
        <div className="wrap">
          <div className="hero-copy">
            {st.eyebrow ? <span className="eyebrow">{st.eyebrow}</span> : null}
            {s.title ? <h1>{s.title}</h1> : null}
            {s.subtitle ? <p className="sub">{s.subtitle}</p> : null}
            <div className="ctas">
              {s.link_url ? (
                <Link className="pill pill-gold" href={s.link_url}>{s.link_text || "Shop now"}</Link>
              ) : null}
              {cta2 ? <Link className="pill pill-ghost-dark" href={st.cta2_url}>{st.cta2_text}</Link> : null}
            </div>
          </div>
          {s.image_url ? (
            <div className="arch">
              <img src={s.image_url} alt={s.title || ""} />
              {st.plate_name || st.plate_price ? (
                <div className="plate">
                  {st.plate_name ? <span className="nm">{st.plate_name}</span> : null}
                  {st.plate_price ? <span className="pr">{st.plate_price}</span> : null}
                  {s.link_url ? <Link href={s.link_url}>View</Link> : null}
                </div>
              ) : null}
            </div>
          ) : null}
        </div>
      </div>
    </div>
  );
}

/** Auto-rotating campaign hero, oraimo-style: native scroll-snap slides, swipe
    on touch, arrows + pill dots, hover-pause, prefers-reduced-motion aware.
    Renders CMS slides (home_hero) when the CMS has them, else the built-in
    slides below — so editing/adding slides in the admin never breaks the site. */
export default function HeroCarousel({ cmsSlides }: { cmsSlides?: ContentBlock[] }) {
  const slides = cmsSlides && cmsSlides.length ? cmsSlides : null; // null → built-in fallback
  const N = slides ? slides.length : 3;
  const nRef = useRef(N);
  nRef.current = N;

  const track = useRef<HTMLDivElement>(null);
  const [i, setI] = useState(0);
  const [paused, setPaused] = useState(false);
  const iRef = useRef(0);
  iRef.current = i;

  const go = useCallback((n: number) => {
    const el = track.current;
    if (!el) return;
    const count = nRef.current;
    const idx = ((n % count) + count) % count;
    const reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    el.scrollTo({ left: idx * el.clientWidth, behavior: reduce ? "auto" : "smooth" });
  }, []);

  const onScroll = useCallback(() => {
    const el = track.current;
    if (!el) return;
    const idx = Math.round(el.scrollLeft / el.clientWidth);
    if (idx !== iRef.current && idx >= 0 && idx < nRef.current) setI(idx);
  }, []);

  useEffect(() => {
    if (paused || N <= 1) return;
    if (typeof window !== "undefined" && window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;
    const t = setInterval(() => go(iRef.current + 1), INTERVAL);
    return () => clearInterval(t);
  }, [paused, go, N]);

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
        {slides ? (
          slides.map((s, idx) => <CmsSlide key={s.id ?? idx} s={s} active={i === idx} />)
        ) : (
          <>
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
          </>
        )}
      </div>

      <button className="hero-nav prev" aria-label="Previous slide" onClick={() => go(i - 1)}>‹</button>
      <button className="hero-nav next" aria-label="Next slide" onClick={() => go(i + 1)}>›</button>
      <div className="hero-dots" role="tablist" aria-label="Slides">
        {Array.from({ length: N }).map((_, n) => (
          <button key={n} role="tab" aria-selected={i === n} aria-label={`Slide ${n + 1}`}
            className={i === n ? "on" : ""} onClick={() => go(n)} />
        ))}
      </div>
    </section>
  );
}
