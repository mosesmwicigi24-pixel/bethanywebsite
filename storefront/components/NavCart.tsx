"use client";

import { useCart } from "@/lib/cart";
import { CartIcon } from "./icons";

/** Live cart button in the nav — count badge from real cart state. */
export default function NavCart() {
  const { count, setOpen } = useCart();
  return (
    <button aria-label={`Cart, ${count} items`} onClick={() => setOpen(true)} style={{ display: "flex", position: "relative" }}>
      <CartIcon />
      {count > 0 && <span className="cart-dot">{count}</span>}
    </button>
  );
}
