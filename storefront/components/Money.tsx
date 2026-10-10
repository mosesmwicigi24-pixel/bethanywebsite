"use client";

import { useEffect, useRef, useState } from "react";
import { useCurrency } from "@/lib/currency";
import { convert, formatMoney, oldPriceFor, priceFor, type Product } from "@/lib/products";

/** Renders a base-KES amount in the active display currency (USD = KES/100,
    Kwacha = KES/5). The `usd` prop is accepted for call-site compatibility but
    ignored — the value is derived from `kes`. */
export function Money({ kes }: { kes: number; usd?: number }) {
  const { currency } = useCurrency();
  return <>{formatMoney(convert(kes, currency), currency)}</>;
}

/** Product price in the active currency. */
export function Price({ p }: { p: Product }) {
  const { currency } = useCurrency();
  return <>{formatMoney(priceFor(p, currency), currency)}</>;
}

/** Struck-through old price — renders nothing if the product has none. */
export function OldPrice({ p }: { p: Product }) {
  const { currency } = useCurrency();
  const v = oldPriceFor(p, currency);
  if (!v) return null;
  return <s>{formatMoney(v, currency)}</s>;
}

/** KES | USD | ZMW segmented toggle for the nav. */
export function CurrencyToggle() {
  const { currency, setCurrency } = useCurrency();
  return (
    <div className="cur-toggle" role="group" aria-label="Display currency">
      {(["KES", "USD", "ZMW"] as const).map((c) => (
        <button key={c} className={currency === c ? "on" : ""} aria-pressed={currency === c}
          onClick={() => setCurrency(c)}>{c}</button>
      ))}
    </div>
  );
}

const FLAG: Record<"KES" | "USD" | "ZMW", string> = { KES: "🇰🇪", USD: "🇺🇸", ZMW: "🇿🇲" };

/** Compact phone-nav currency chip (globe · flag · code) with a small menu —
    the CurrencyToggle's segmented control doesn't fit a phone header. */
export function CurrencyChip() {
  const { currency, setCurrency } = useCurrency();
  const [open, setOpen] = useState(false);
  const box = useRef<HTMLDivElement>(null);
  useEffect(() => {
    if (!open) return;
    const off = (e: PointerEvent) => { if (!box.current?.contains(e.target as Node)) setOpen(false); };
    document.addEventListener("pointerdown", off);
    return () => document.removeEventListener("pointerdown", off);
  }, [open]);
  return (
    <div className="cur-chip" ref={box}>
      <button type="button" aria-haspopup="listbox" aria-expanded={open} aria-label={`Currency: ${currency}`}
        onClick={() => setOpen((o) => !o)}>
        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path d="M3 12h18M12 3c2.6 2.6 3.8 5.6 3.8 9s-1.2 6.4-3.8 9c-2.6-2.6-3.8-5.6-3.8-9S9.4 5.6 12 3z" /></svg>
        <span className="flag" aria-hidden="true">{FLAG[currency]}</span>
        {currency}
      </button>
      {open && (
        <ul role="listbox" aria-label="Display currency">
          {(["KES", "USD", "ZMW"] as const).map((c) => (
            <li key={c}>
              <button type="button" role="option" aria-selected={c === currency}
                onClick={() => { setCurrency(c); setOpen(false); }}>
                <span aria-hidden="true">{FLAG[c]}</span>{c}
              </button>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
