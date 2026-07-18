"use client";

import { useState } from "react";
import { CartIcon } from "./icons";

/** Hover quick-actions on product cards: wishlist + instant add-to-cart. */
export default function QuickActions() {
  const [wished, setWished] = useState(false);

  const addToCart = (e: React.MouseEvent) => {
    e.preventDefault();
    e.stopPropagation();
    const dot = document.querySelector(".cart-dot");
    if (dot) dot.textContent = String(Number(dot.textContent) + 1);
  };

  const wish = (e: React.MouseEvent) => {
    e.preventDefault();
    e.stopPropagation();
    setWished((w) => !w);
  };

  return (
    <span className="quick">
      <button aria-label="Add to wishlist" aria-pressed={wished} onClick={wish}
        style={wished ? { background: "var(--gold)", color: "var(--navy-950)" } : undefined}>
        {wished ? "♥" : "♡"}
      </button>
      <button aria-label="Quick add to cart" onClick={addToCart}>
        <CartIcon />
      </button>
    </span>
  );
}
