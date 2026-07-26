import { NextResponse } from "next/server";
import { submitReview } from "@/lib/hub";

/* Save a customer's product rating/review.

   Client-facing endpoint for the PDP's "Review this product" star input.
   Kept under /api/neema/* because the host nginx routes other /api/* paths
   to the legacy POS app — only /api/neema* reaches this storefront.

   The X-Storefront-Key never leaves the server: the browser POSTs here, and
   lib/hub.ts adds the key when forwarding to the Hub. Best-effort — if the
   Hub is unset or the endpoint isn't live yet, we still acknowledge so the
   customer gets a thank-you (reviews are moderated before they appear). */

export const runtime = "nodejs";

export async function POST(req: Request) {
  let body: Record<string, unknown> = {};
  try {
    body = (await req.json()) as Record<string, unknown>;
  } catch {
    /* empty / malformed body — validated below */
  }

  const slug = typeof body.slug === "string" ? body.slug.trim() : "";
  const rating = Math.round(Number(body.rating));
  if (!slug || !Number.isFinite(rating) || rating < 1 || rating > 5) {
    return NextResponse.json(
      { ok: false, error: "A slug and a rating from 1 to 5 are required." },
      { status: 400 },
    );
  }

  const str = (v: unknown, max: number) =>
    typeof v === "string" && v.trim() ? v.trim().slice(0, max) : undefined;

  const res = await submitReview(slug, {
    rating,
    title: str(body.title, 120),
    body: str(body.body, 2000),
    author: str(body.author, 80),
    email: str(body.email, 160),
  });

  // Acknowledge either way (optimistic, moderated). `saved` tells the truth
  // about whether it reached the Hub, without surfacing a hard error for a
  // best-effort review write.
  return NextResponse.json({ ok: true, saved: res.ok });
}
