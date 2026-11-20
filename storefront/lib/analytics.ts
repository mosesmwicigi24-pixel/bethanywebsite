import { sendGAEvent } from "@next/third-parties/google";

/* Google Analytics 4 — the shop's existing property (SITE.gaId: the
   measurement ID the old site already reported to, so history continues in
   one place).

   The tag itself is loaded by <GoogleAnalytics gaId={SITE.gaId} /> in the
   root layout (after hydration, so it never delays the page). Everything
   else on the site reports through track(): a thin wrapper that can never
   throw and does nothing on the server or when the tag is blocked. */

type Params = Record<string, string | number | boolean | undefined | GaItem[]>;

export interface GaItem {
  item_id: string;
  item_name?: string;
  price?: number;
  quantity?: number;
  item_variant?: string;
}

/** Send one GA4 event. Safe anywhere: a no-op during SSR or if gtag is absent. */
export function track(name: string, params: Params = {}): void {
  if (typeof window === "undefined") return;
  try {
    sendGAEvent("event", name, params);
  } catch {
    /* analytics must never break the shop */
  }
}

/** Which contact channel a link opens, or null when it is an ordinary link. */
export function contactChannel(href: string): "whatsapp" | "phone" | "email" | null {
  const h = href.trim().toLowerCase();
  if (h.startsWith("https://wa.me") || h.startsWith("https://api.whatsapp.com") || h.startsWith("whatsapp:")) return "whatsapp";
  if (h.startsWith("tel:")) return "phone";
  if (h.startsWith("mailto:")) return "email";
  return null;
}
