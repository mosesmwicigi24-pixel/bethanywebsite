"use client";

import { ReactNode, useEffect, useRef } from "react";

/** Scroll-reveal wrapper — fades content up as it enters the viewport.
    Respects prefers-reduced-motion (CSS kills the transition). */
export default function Reveal({ children, as: Tag = "div", className = "" }: {
  children: ReactNode;
  as?: "div" | "section";
  className?: string;
}) {
  const ref = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const el = ref.current;
    if (!el) return;
    const io = new IntersectionObserver(
      (entries) => entries.forEach((e) => {
        if (e.isIntersecting) {
          e.target.classList.add("in");
          io.unobserve(e.target);
        }
      }),
      { threshold: 0.12, rootMargin: "0px 0px -40px 0px" },
    );
    io.observe(el);
    return () => io.disconnect();
  }, []);

  return <Tag ref={ref as never} className={`reveal ${className}`}>{children}</Tag>;
}
