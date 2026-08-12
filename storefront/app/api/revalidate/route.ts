import { NextResponse } from "next/server";
import { revalidateTag } from "next/cache";
import { createHmac, timingSafeEqual } from "node:crypto";
import { CATALOG_TAG, clearCatalogMemo } from "@/lib/catalog";

/* On-demand revalidation for the hub.

   The storefront reads its catalogue from the hub over ISR with a 300s window,
   so a product edit — a new photo above all — could sit invisible for five
   minutes. The hub now posts here the moment the catalogue changes and the
   change is live on the next request.

   Contract (mirrors the hub's Neema emitter, which is the pattern this rides):
     POST /api/revalidate
     X-Storefront-Signature: sha256=<hmac of the raw body, STOREFRONT_REVALIDATE_SECRET>
     body: {"tag":"catalog"}          // tag optional, defaults to catalog

   INERT until STOREFRONT_REVALIDATE_SECRET is set: without a secret we refuse
   every call rather than exposing an unauthenticated cache-buster, which is
   free to abuse as a way to hammer the hub.
*/

export const dynamic = "force-dynamic";

/** Constant-time compare that cannot throw on a length mismatch. */
function signatureMatches(expected: string, received: string): boolean {
  const a = Buffer.from(expected);
  const b = Buffer.from(received);
  return a.length === b.length && timingSafeEqual(a, b);
}

export async function POST(req: Request) {
  const secret = process.env.STOREFRONT_REVALIDATE_SECRET;
  if (!secret) {
    return NextResponse.json({ error: "revalidation not configured" }, { status: 503 });
  }

  // Read the RAW body — the signature is over the exact bytes the hub sent, so
  // it must not be round-tripped through JSON.parse first.
  const raw = await req.text();
  const header = req.headers.get("x-storefront-signature") ?? "";
  const expected = "sha256=" + createHmac("sha256", secret).update(raw).digest("hex");

  if (!signatureMatches(expected, header)) {
    return NextResponse.json({ error: "bad signature" }, { status: 401 });
  }

  let tag = CATALOG_TAG;
  try {
    const parsed = raw ? (JSON.parse(raw) as { tag?: unknown }) : {};
    if (typeof parsed.tag === "string" && parsed.tag.trim()) tag = parsed.tag.trim();
  } catch {
    // A signed body we cannot parse still authenticated, so treat it as the
    // default catalogue ping rather than failing the hub's write.
  }

  // "max" is Next 16's replacement for the old single-argument full purge —
  // every entry carrying the tag is expired regardless of how fresh it is.
  revalidateTag(tag, "max");
  clearCatalogMemo();

  return NextResponse.json({ revalidated: true, tag });
}
