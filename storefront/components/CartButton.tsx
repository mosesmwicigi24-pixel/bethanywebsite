"use client";

import { useRef } from "react";
import { CartIcon } from "./icons";

/** Mini-card add-to-cart button with gold flash + nav cart-count bump. */
export default function CartButton() {
  const ref = useRef<HTMLButtonElement>(null);

  const add = () => {
    const dot = document.querySelector(".cart-dot");
    if (dot) dot.textContent = String(Number(dot.textContent) + 1);
    const b = ref.current;
    if (b) {
      b.style.background = "var(--gold)";
      setTimeout(() => { b.style.background = ""; }, 500);
    }
  };

  return (
    <button ref={ref} className="cartbtn" aria-label="Add to cart" onClick={add}>
      <CartIcon />
    </button>
  );
}
