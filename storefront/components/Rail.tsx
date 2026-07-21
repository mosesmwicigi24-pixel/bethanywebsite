"use client";

import { ReactNode, useRef } from "react";

/** Horizontal scroll-snap carousel with arrow nav (oraimo/Apple rail). */
export default function Rail({
  children,
  dark = false,
  navInWrap = false,
}: {
  children: ReactNode;
  dark?: boolean;
  navInWrap?: boolean;
}) {
  const ref = useRef<HTMLDivElement>(null);
  // Vertical wheel/touch over the rail scrolls the page natively — the rail is
  // an x-only scroller (overflow-y:hidden in .rail), so the browser chains the
  // vertical gesture to the document. No JS wheel handling (it forced main-thread
  // scrolling and killed momentum). `ref` drives the arrow-nav scrollBy below.
  const go = (dir: number) =>
    ref.current?.scrollBy({ left: 340 * dir, behavior: "smooth" });

  const nav = (
    <div className="rail-nav" style={navInWrap ? { padding: 0 } : undefined}>
      <button aria-label="Previous" onClick={() => go(-1)}>‹</button>
      <button aria-label="Next" onClick={() => go(1)}>›</button>
    </div>
  );

  if (navInWrap) {
    return (
      <>
        <div className="rail" ref={ref}>{children}</div>
        {nav}
      </>
    );
  }
  return (
    <>
      <div className="wrap"><div className="rail" ref={ref}>{children}</div></div>
      {nav}
    </>
  );
}
