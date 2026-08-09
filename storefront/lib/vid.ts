import { cookies } from "next/headers";

/* ============================================================
   First-party visitor id (bh_vid) — a durable, server-set, httpOnly anchor
   for ANONYMOUS identity, before any phone.

   Why it exists (interest ledger v2):
     • The cart token (BH-XXXX) identifies one cart and ROTATES per order.
       bh_vid does NOT rotate — it's the same visitor across many carts, so
       the Hub can group "this person's carts over time" without a phone.
     • It's set server-side and httpOnly (client JS can't read it), so it's
       harder to lose than localStorage and the server can always stamp it
       onto the interest row (and onto the chat), linking cart ↔ chat.

   Read-or-mint on any /api/neema* request; the browser then carries the
   cookie on every same-origin call. Safe in a Route Handler, where
   Set-Cookie is allowed.
   ============================================================ */

const VID_COOKIE = "bh_vid";
const ONE_YEAR = 60 * 60 * 24 * 365;

function mintVid(): string {
  try {
    return `v-${crypto.randomUUID()}`;
  } catch {
    return `v-${Date.now().toString(36)}${Math.random().toString(36).slice(2, 10)}`;
  }
}

/** This visitor's stable id, minting + persisting the cookie on first sight.
    Never throws — degrades to a fresh per-request id if cookies() is somehow
    unavailable (the ledger still gets a value, just not persisted). */
export async function getOrCreateVid(): Promise<string> {
  try {
    const jar = await cookies();
    const existing = jar.get(VID_COOKIE)?.value;
    if (existing) return existing;
    const vid = mintVid();
    jar.set(VID_COOKIE, vid, {
      maxAge: ONE_YEAR,
      path: "/",
      httpOnly: true,
      sameSite: "lax",
      secure: process.env.NODE_ENV === "production",
    });
    return vid;
  } catch {
    return mintVid();
  }
}
