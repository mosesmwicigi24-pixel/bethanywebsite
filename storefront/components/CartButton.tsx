"use client";

import { useRef } from "react";

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
      <svg viewBox="0 0 24 24"><path d="M6 7h12l-1 13H7L6 7zm3 0a3 3 0 0 1 6 0" /></svg>
    </button>
  );
}
