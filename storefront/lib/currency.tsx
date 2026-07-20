"use client";

import { ReactNode, createContext, useContext, useEffect, useState } from "react";
import type { Currency } from "./products";

/* Currency handling, ported from the hub (backend/app/Models/Order.php):
     Order::resolveCurrency(country) => 'KE' ? 'KES' : 'USD'
   The storefront applies the same rule from the customer's phone number:
   +254 / 07xx / 01xx (Kenyan numbers) => KES, everything else => USD. */

export const currencyForCountry = (countryCode: string): Currency => {
  const cc = countryCode.trim().toUpperCase();
  return cc === "KE" ? "KES" : cc === "ZM" ? "ZMW" : "USD";
};

/** Detect country from a phone number the way a Kenyan shop reads it. */
export const countryForPhone = (phone: string): "KE" | "ZM" | "INTL" | null => {
  const d = phone.replace(/[\s\-()]/g, "");
  if (!d) return null;
  if (/^(\+?254)/.test(d)) return "KE";
  if (/^(\+?260)/.test(d)) return "ZM";       // Zambia → Kwacha
  if (/^0(7|1)\d/.test(d)) return "KE";       // local format 07xx / 01xx
  if (/^\+\d{3,}/.test(d)) return "INTL";     // any other international prefix
  return null;                                 // not enough signal yet
};

export const currencyForPhone = (phone: string): Currency | null => {
  const c = countryForPhone(phone);
  return c === null ? null : c === "KE" ? "KES" : c === "ZM" ? "ZMW" : "USD";
};

interface CurrencyCtx {
  currency: Currency;
  setCurrency: (c: Currency) => void;
}

const Ctx = createContext<CurrencyCtx>({ currency: "KES", setCurrency: () => {} });
export const useCurrency = () => useContext(Ctx);

const KEY = "bh-currency";

export function CurrencyProvider({ children }: { children: ReactNode }) {
  const [currency, setCurrencyState] = useState<Currency>("KES");

  useEffect(() => {
    const saved = localStorage.getItem(KEY);
    if (saved === "USD" || saved === "KES" || saved === "ZMW") setCurrencyState(saved);
  }, []);

  const setCurrency = (c: Currency) => {
    setCurrencyState(c);
    localStorage.setItem(KEY, c);
  };

  return <Ctx.Provider value={{ currency, setCurrency }}>{children}</Ctx.Provider>;
}
