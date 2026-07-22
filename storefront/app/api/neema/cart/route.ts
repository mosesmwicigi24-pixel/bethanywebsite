import {
  upsertInterestCart,
  markInterestOutcome,
  type InterestItem,
  type InterestStatus,
  type InterestOutcome,
} from "@/lib/hub";

/* ============================================================
   /api/neema/cart — the storefront's write path into the Hub interest ledger.

   Lives under /api/neema/* on purpose: the host nginx routes /api/* to the
   legacy POS app, and only the ^~ /api/neema prefix is proxied to the
   storefront. So the cart-sync endpoint MUST sit here to be reachable in
   production (same reason the geo route moved off /api/*).

   The browser POSTs its cart here (keyed by a cross-channel token); this
   route mirrors it to the Hub server-side, where the X-Storefront-Key and
   write credentials live — the browser never touches the Hub directly.

     • active cart / checkout_started  → upsert the ledger row
     • online_order / whatsapp_order / abandoned → transition its outcome

   Best-effort by design: if the Hub interest endpoints aren't live yet (or
   NEXT_PUBLIC_HUB_API is unset), the Hub calls no-op and we still return
   200 — the cart keeps working locally. Nothing is ever dropped.
   ============================================================ */

const MAX_ITEMS = 40;
const OUTCOMES: InterestOutcome[] = ["online_order", "whatsapp_order", "abandoned"];

type Body = {
  token?: unknown;
  status?: unknown;
  sessionId?: unknown;
  items?: unknown;
  subtotal?: unknown;
  currency?: unknown;
  customer?: unknown;
  orderRef?: unknown;
  sourcePath?: unknown;
};

function cleanCustomer(v: unknown): { name?: string; phone?: string; church?: string } | undefined {
  if (!v || typeof v !== "object") return undefined;
  const c = v as Record<string, unknown>;
  const out: { name?: string; phone?: string; church?: string } = {};
  if (typeof c.name === "string" && c.name.trim()) out.name = c.name.trim().slice(0, 120);
  if (typeof c.phone === "string" && c.phone.trim()) out.phone = c.phone.trim().slice(0, 32);
  if (typeof c.church === "string" && c.church.trim()) out.church = c.church.trim().slice(0, 160);
  return out.name || out.phone || out.church ? out : undefined;
}

function cleanItem(v: unknown): InterestItem | null {
  if (!v || typeof v !== "object") return null;
  const i = v as Record<string, unknown>;
  const slug = typeof i.slug === "string" ? i.slug.slice(0, 120) : "";
  if (!slug) return null;
  const quantity = Number(i.quantity);
  const item: InterestItem = { slug, quantity: Number.isFinite(quantity) && quantity > 0 ? Math.min(9999, Math.floor(quantity)) : 1 };
  if (i.measurements && typeof i.measurements === "object") item.measurements = i.measurements as Record<string, string>;
  if (typeof i.size === "string" && i.size.trim()) item.size = i.size.trim().slice(0, 40);
  return item;
}

export async function POST(request: Request): Promise<Response> {
  let body: Body;
  try {
    body = (await request.json()) as Body;
  } catch {
    return Response.json({ error: "Invalid request" }, { status: 400 });
  }

  const token = typeof body.token === "string" ? body.token.slice(0, 40) : "";
  if (!token) return Response.json({ error: "Missing token" }, { status: 400 });

  const status = typeof body.status === "string" ? body.status : "";

  // Terminal outcome → transition the ledger row.
  if (OUTCOMES.includes(status as InterestOutcome)) {
    const ok = await markInterestOutcome(token, status as InterestOutcome, {
      orderRef: typeof body.orderRef === "string" ? body.orderRef.slice(0, 80) : undefined,
      customer: cleanCustomer(body.customer),
    });
    return Response.json({ ok });
  }

  // Otherwise upsert the active cart.
  const items = Array.isArray(body.items)
    ? (body.items.slice(0, MAX_ITEMS).map(cleanItem).filter(Boolean) as InterestItem[])
    : [];
  if (!items.length) return Response.json({ ok: false, note: "empty cart" });

  const res = await upsertInterestCart({
    token,
    channel: "web",
    sessionId: typeof body.sessionId === "string" ? body.sessionId.slice(0, 80) : undefined,
    status: status === "checkout_started" ? ("checkout_started" as InterestStatus) : ("active_cart" as InterestStatus),
    items,
    subtotal: typeof body.subtotal === "number" && Number.isFinite(body.subtotal) ? body.subtotal : undefined,
    currency: typeof body.currency === "string" ? body.currency.slice(0, 8) : undefined,
    customer: cleanCustomer(body.customer),
    sourcePath: typeof body.sourcePath === "string" ? body.sourcePath.slice(0, 200) : undefined,
  });
  return Response.json({ ok: Boolean(res), token });
}
