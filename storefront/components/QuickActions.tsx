"use client";

import { useState } from "react";

/** A single, low-noise wishlist control on the product tile.
    The tile itself taps through to the PDP, where add-to-cart lives. */
export default function QuickActions({ slug }: { slug: string }) {
  const [wished, setWished] = useState(false);

  const wish = (e: React.MouseEvent) => {
    e.preventDefault();
    e.stopPropagation();
    setWished((w) => !w);
  };

  return (
    <span className="wish" data-slug={slug}>
      <button
        aria-label={wished ? "Saved to wishlist" : "Save to wishlist"}
        aria-pressed={wished}
        onClick={wish}
      >
        {wished ? "♥" : "♡"}
      </button>
    </span>
  );
}
