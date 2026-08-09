"use client";

import { useState } from "react";

const PLACEHOLDER = "/brand/placeholder.svg";

/** Product image that falls back to the branded placeholder if the source
    (a hub-hosted URL that may 404, or a missing asset) fails to load —
    so the storefront never shows a broken image. */
export default function Img({ src, alt = "", className }: { src: string; alt?: string; className?: string }) {
  const [failed, setFailed] = useState(false);
  return (
    <img
      src={failed ? PLACEHOLDER : src}
      alt={alt}
      className={className}
      loading="lazy"
      onError={() => { if (!failed) setFailed(true); }}
    />
  );
}
