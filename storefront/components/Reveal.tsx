"use client";

import { ReactNode, useEffect, useRef } from "react";

/** Scroll-reveal wrapper — fades content up as it enters the viewport.
    Respects prefers-reduced-motion (CSS kills the transition).

    Reveals on visible PIXELS, not on a share of the section: a ratio
    threshold (the old 0.12) never fires for a section taller than ~8×
    the viewport — a 46-card category grid is ~6,000px, so 12% of it was
    never on screen at once and the whole department rendered invisible.
    Now a section shows as soon as ~160px of it is in view (or 12% for
    short ones), and anything already on screen at mount shows at once. */
export default function Reveal({ children, as: Tag = "div", className = "" }: {
  children: ReactNode;
  as?: "div" | "section";
  className?: string;
}) {
  const ref = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const el = ref.current;
    if (!el) return;

    const show = () => el.classList.add("in");

    // Already in view on mount (above-the-fold sections, or a page opened
    // mid-scroll): don't wait for an observer tick.
    const r = el.getBoundingClientRect();
    if (r.top < window.innerHeight - 40 && r.bottom > 0) { show(); return; }

    if (!("IntersectionObserver" in window)) { show(); return; }
    const io = new IntersectionObserver(
      (entries) => entries.forEach((e) => {
        if (!e.isIntersecting) return;
        const visiblePx = e.intersectionRect.height;
        if (e.intersectionRatio >= 0.12 || visiblePx >= 160) {
          show();
          io.unobserve(e.target);
        }
      }),
      { threshold: [0, 0.03, 0.06, 0.12], rootMargin: "0px 0px -40px 0px" },
    );
    io.observe(el);
    return () => io.disconnect();
  }, []);

  return <Tag ref={ref as never} className={`reveal ${className}`}>{children}</Tag>;
}
