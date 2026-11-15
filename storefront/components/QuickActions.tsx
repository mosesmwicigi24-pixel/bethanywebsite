"use client";

import { useState } from "react";

/** The wishlist heart on a product card's photo. (Adding to the cart is the
    card's own full-width button — see CardCta.) */
export default function QuickActions() {
  const [wished, setWished] = useState(false);

  const wish = (e: React.MouseEvent) => {
    e.preventDefault();
    e.stopPropagation();
    setWished((w) => !w);
  };

  return (
    <span className="quick">
      <button type="button" aria-label="Add to wishlist" aria-pressed={wished} onClick={wish}>
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20.5s-7.5-4.6-7.5-10.2A4.3 4.3 0 0 1 12 7.6a4.3 4.3 0 0 1 7.5 2.7c0 5.6-7.5 10.2-7.5 10.2z" /></svg>
      </button>
    </span>
  );
}
