"use client";

import Link from "next/link";
import { useEffect, useRef, useState } from "react";
import { useCart } from "@/lib/cart";
import { openNeema } from "@/lib/ui-events";
import { CartIcon } from "./icons";

export type CtaMode =
  | "add"      // one tap puts it in the cart
  | "options"  // has variants — choose on the product page
  | "size"     // garment — pick a size or give measurements on the product page
  | "sold"     // out of stock
  | "ask";     // no price set — ask Neema

/**
 * The card's one call to action — the same size and place on every card, so
 * a row of products reads as a row of buttons. It always does the next honest
 * thing: add the product, or open the page where the choice is made, or (no
 * price yet) start the question with Neema.
 */
export default function CardCta({ slug, name, href, mode }: { slug: string; name: string; href: string; mode: CtaMode }) {
  const { add } = useCart();
  const [added, setAdded] = useState(false);
  const timer = useRef<number | undefined>(undefined);
  useEffect(() => () => window.clearTimeout(timer.current), []);

  if (mode === "add") {
    const onAdd = () => {
      add(slug);
      setAdded(true);
      window.clearTimeout(timer.current);
      timer.current = window.setTimeout(() => setAdded(false), 2200);
    };
    return (
      <button type="button" className={`cta${added ? " done" : ""}`} onClick={onAdd}
        aria-label={`Add ${name} to cart`}>
        {added
          ? <><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12.5 4.5 4.5L19 7.5" /></svg>Added</>
          : <><CartIcon />Add to cart</>}
      </button>
    );
  }
  if (mode === "ask") {
    return (
      <button type="button" className="cta alt" onClick={() => openNeema(`Hello, what is the price of ${name}?`)}>
        Ask for price
      </button>
    );
  }
  if (mode === "sold") {
    return <Link className="cta off" href={href}>Sold out</Link>;
  }
  return (
    <Link className="cta alt" href={href} aria-label={`${mode === "size" ? "Choose size" : "Choose options"} for ${name}`}>
      {mode === "size" ? "Choose size" : "Choose options"}
    </Link>
  );
}
