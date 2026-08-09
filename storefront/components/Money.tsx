"use client";

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
