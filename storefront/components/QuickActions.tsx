"use client";

import { useState } from "react";
import { useCart } from "@/lib/cart";
import { CartIcon } from "./icons";

/**
 * Quick actions on a product card's photo: wishlist, and one-tap add to cart.
 * `canAdd` is false for anything one tap can't honestly put in the cart — a
 * garment that needs a size or measurements, a product with options, a
 * sold-out item — and the cart button is simply not offered there.
 */
export default function QuickActions({ slug, canAdd = true }: { slug: string; canAdd?: boolean }) {
  const [wished, setWished] = useState(false);
  const { add } = useCart();

  const addToCart = (e: React.MouseEvent) => {
    e.preventDefault();
    e.stopPropagation();
    add(slug);
  };

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
      {canAdd && (
        <button type="button" aria-label="Quick add to cart" onClick={addToCart}>
          <CartIcon />
        </button>
      )}
    </span>
  );
}
