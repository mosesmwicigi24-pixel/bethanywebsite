"use client";

import { useEffect, useRef, useState } from "react";

const PLACEHOLDER = "/brand/placeholder.svg";

/** Product image that falls back to the branded placeholder if the source
    (a hub-hosted URL that may 404, or a missing asset) fails to load —
    so the storefront never shows a broken image. */
export default function Img({ src, alt = "", className }: { src: string; alt?: string; className?: string }) {
  const [failed, setFailed] = useState(false);
  const ref = useRef<HTMLImageElement>(null);
  // Failed before hydration → onError never fires; check on mount.
  useEffect(() => {
    const i = ref.current;
    if (i && i.complete && i.naturalWidth === 0) setFailed(true);
  }, []);
  return (
    <img
      ref={ref}
      src={failed ? PLACEHOLDER : src}
      alt={alt}
      className={className}
      loading="lazy"
      onError={() => { if (!failed) setFailed(true); }}
    />
  );
}
