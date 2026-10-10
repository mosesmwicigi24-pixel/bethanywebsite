"use client";

import { useCart } from "@/lib/cart";
import { CartIcon } from "./icons";

/** Live cart button in the nav — count badge from real cart state. */
export default function NavCart() {
  const { count, setOpen } = useCart();
  return (
    <button className="nav-ico" aria-label={`Cart, ${count} items`} onClick={() => setOpen(true)}>
      <CartIcon />
      <span className="nav-lbl">Cart</span>
      {count > 0 && <span className="cart-dot">{count}</span>}
    </button>
  );
}
