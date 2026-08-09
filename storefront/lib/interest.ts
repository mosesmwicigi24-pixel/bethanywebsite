/* ============================================================
   Interest ledger — client side.

   A stable, short cross-channel token (BH-XXXX) minted per browser and
   kept in localStorage. It's the handle that links this browser's cart to
   the Hub interest ledger, and to a WhatsApp/Messenger conversation when
   the customer taps through (the handoff message carries it).

   Client-safe: pure functions + localStorage, no server imports. All
   guarded so a private-mode / storage-blocked browser still works.
   ============================================================ */

const TOKEN_KEY = "bh-cart-token";
// Crockford base32 (no I/L/O/U — unambiguous when a human reads it off WhatsApp).
const ALPHABET = "0123456789ABCDEFGHJKMNPQRSTVWXYZ";

function mint(): string {
  let s = "";
  try {
    const a = new Uint8Array(8);
    crypto.getRandomValues(a);
    s = [...a].map((x) => ALPHABET[x & 31]).join("");
  } catch {
    // last-ditch fallback — timestamp-derived, still 8 chars
    let n = Date.now();
    for (let i = 0; i < 8; i++) { s += ALPHABET[n & 31]; n = Math.floor(n / 32) + i * 7; }
  }
  return `BH-${s}`;
}

/** Get this browser's cart token, minting + persisting one on first use. */
export function cartToken(): string {
  try {
    let t = localStorage.getItem(TOKEN_KEY);
    if (!t) { t = mint(); localStorage.setItem(TOKEN_KEY, t); }
    return t;
  } catch {
    return mint();
  }
}

/** Start a fresh interest record — call after an order resolves, so the next
    cart is tracked as new interest rather than appended to a closed one. */
export function rotateCartToken(): string {
  const t = mint();
  try { localStorage.setItem(TOKEN_KEY, t); } catch { /* storage blocked — token stays in memory only */ }
  return t;
}

/** The Neema chat session id (set by the widget at `bh-neema-sid`) — sent with
    the cart so the Hub can link this cart to the customer's chat conversation. */
export function neemaSession(): string | undefined {
  try {
    return localStorage.getItem("bh-neema-sid") || undefined;
  } catch {
    return undefined;
  }
}

/** Fire-and-forget POST to the storefront's /api/neema/cart, which upserts to
    the Hub ledger server-side. (Under /api/neema/* because the host nginx routes
    other /api/* paths to the legacy app.) Never throws; a failure just means the
    cart isn't mirrored this beat (it still works locally). */
export function postInterest(body: Record<string, unknown>): void {
  try {
    void fetch("/api/neema/cart", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(body),
      keepalive: true, // survives a page navigation (e.g. tapping through to checkout)
    }).catch(() => {});
  } catch {
    /* fetch unavailable — ignore */
  }
}
