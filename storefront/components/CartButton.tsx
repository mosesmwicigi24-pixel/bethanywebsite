"use client";

import { useRef } from "react";
import { useCart } from "@/lib/cart";
import { CartIcon } from "./icons";

/** Mini-card add-to-cart button — adds to the real cart with a gold flash. */
export default function CartButton({ slug }: { slug: string }) {
  const ref = useRef<HTMLButtonElement>(null);
  const { add } = useCart();

  const onAdd = () => {
    add(slug);
    const b = ref.current;
    if (b) {
      b.style.background = "var(--gold)";
      setTimeout(() => { b.style.background = ""; }, 500);
    }
  };

  return (
    <button ref={ref} className="cartbtn" aria-label="Add to cart" onClick={onAdd}>
      <CartIcon />
    </button>
  );
}
