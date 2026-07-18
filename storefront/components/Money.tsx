"use client";

import { useCurrency } from "@/lib/currency";
import { formatMoney, oldPriceFor, priceFor, type Product } from "@/lib/products";

/** Renders an amount in the active display currency. */
export function Money({ kes, usd }: { kes: number; usd: number }) {
  const { currency } = useCurrency();
  return <>{formatMoney(currency === "KES" ? kes : usd, currency)}</>;
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

/** KES | USD segmented toggle for the nav. */
export function CurrencyToggle() {
  const { currency, setCurrency } = useCurrency();
  return (
    <div className="cur-toggle" role="group" aria-label="Display currency">
      {(["KES", "USD"] as const).map((c) => (
        <button key={c} className={currency === c ? "on" : ""} aria-pressed={currency === c}
          onClick={() => setCurrency(c)}>{c}</button>
      ))}
    </div>
  );
}
