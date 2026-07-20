"use client";

import { RefObject, useEffect } from "react";

/**
 * Horizontal scroll-snap carousels (`overflow-x:auto`) are scroll containers on
 * both axes, so a vertical trackpad/wheel scroll over them gets trapped instead
 * of scrolling the page. This forwards a vertical-dominant wheel to the window
 * (so up/down scrolling works over the slider) while leaving horizontal-dominant
 * wheels to scroll the carousel natively.
 */
export function useHorizontalWheel(ref: RefObject<HTMLElement | null>) {
  useEffect(() => {
    const el = ref.current;
    if (!el) return;

    const onWheel = (e: WheelEvent) => {
      // Horizontal-dominant gesture → let the carousel scroll natively.
      if (Math.abs(e.deltaX) > Math.abs(e.deltaY)) return;
      // Vertical-dominant → the carousel would swallow it; drive the page instead.
      e.preventDefault();
      const dy =
        e.deltaMode === 1 ? e.deltaY * 16 :               // lines → px
        e.deltaMode === 2 ? e.deltaY * window.innerHeight : // pages → px
        e.deltaY;                                          // already px
      // Direct scrollTop is instant (ignores scroll-behavior:smooth) and moves
      // the real scroller. Fall back to <body> for the rare body-scroller layout.
      const scroller = (document.scrollingElement as HTMLElement | null) ?? document.documentElement;
      const y0 = scroller.scrollTop;
      scroller.scrollTop = y0 + dy;
      if (scroller.scrollTop === y0 && dy !== 0) document.body.scrollTop += dy;
    };

    el.addEventListener("wheel", onWheel, { passive: false });
    return () => el.removeEventListener("wheel", onWheel);
  }, [ref]);
}
