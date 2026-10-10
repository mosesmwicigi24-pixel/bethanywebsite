"use client";

import { useLayoutEffect, useRef } from "react";

/**
 * The product name on one line whenever it can be: the font steps down from
 * the design size until the name fits its column, never below `min`. A name
 * too long even at `min` wraps at the design size instead of shrinking into
 * something unreadable. Refits when the column resizes (rotation, fonts).
 */
export default function FitTitle({ children, max = 23, min = 17 }: { children: string; max?: number; min?: number }) {
  const ref = useRef<HTMLHeadingElement>(null);

  useLayoutEffect(() => {
    const el = ref.current;
    if (!el) return;
    const fit = () => {
      // Only phones get the one-line treatment; larger screens keep the CSS size.
      if (!window.matchMedia("(max-width:767px)").matches) {
        el.style.fontSize = "";
        el.style.whiteSpace = "";
        return;
      }
      el.style.whiteSpace = "nowrap";
      let size = max;
      el.style.fontSize = `${size}px`;
      while (el.scrollWidth > el.clientWidth && size > min) {
        size -= 0.5;
        el.style.fontSize = `${size}px`;
      }
      if (el.scrollWidth > el.clientWidth) {
        el.style.whiteSpace = "";
        el.style.fontSize = `${max}px`;
      }
    };
    fit();
    const ro = new ResizeObserver(fit);
    ro.observe(el);
    document.fonts?.ready.then(fit).catch(() => {});
    return () => ro.disconnect();
  }, [children, max, min]);

  return <h1 ref={ref} className="fit-title">{children}</h1>;
}
