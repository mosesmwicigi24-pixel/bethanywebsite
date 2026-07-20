import { createLead } from "@/lib/hub";
import { SITE } from "@/lib/site";

/* Lead submission for Neema's in-chat capture form (advisory §3, §6).
   Writes go through the hub server-side; if the hub lead endpoint isn't
   live yet, we never drop the lead — we return a WhatsApp deep link with
   the enquiry pre-filled so the customer reaches staff either way. */

const WA_NUMBER = SITE.phone.replace(/[^\d]/g, "");

interface LeadBody {
  sessionId?: string;
  intent?: string;
  fields?: Record<string, string>;
  products?: string[];
  pageContext?: { path?: string };
}

// Light per-session limiter (see the main route for the caveat).
const buckets = new Map<string, { count: number; resetAt: number }>();
function limited(id: string): boolean {
  const now = Date.now();
  const b = buckets.get(id);
  if (!b || now > b.resetAt) {
    buckets.set(id, { count: 1, resetAt: now + 60_000 });
    return false;
  }
  b.count += 1;
  return b.count > 10;
}

export async function POST(request: Request): Promise<Response> {
  let body: LeadBody;
  try {
    body = (await request.json()) as LeadBody;
  } catch {
    return Response.json({ ok: false, error: "Invalid request" }, { status: 400 });
  }

  const f = body.fields ?? {};
  const phone = String(f.phone ?? "").trim();
  if (!phone) return Response.json({ ok: false, error: "A phone or WhatsApp number is required." }, { status: 422 });

  const sessionId = String(body.sessionId ?? "anon").slice(0, 80);
  if (limited(sessionId)) return Response.json({ ok: false, error: "Please wait a moment before sending again." }, { status: 429 });

  const lead = await createLead({
    intent: body.intent || "quote",
    readiness: "high",
    name: f.name,
    phone,
    city: f.city,
    countryCode: f.country,
    quantity: f.quantity,
    message: f.note,
    products: body.products?.slice(0, 10),
    sourcePath: body.pageContext?.path,
  });

  // Pre-filled WhatsApp summary — the always-available path to staff.
  const summary = [
    "Hello Bethany House, I'd like to continue my enquiry:",
    f.name && `Name: ${f.name}`,
    `Phone: ${phone}`,
    (f.city || f.country) && `Location: ${[f.city, f.country].filter(Boolean).join(", ")}`,
    f.quantity && `Quantity: ${f.quantity}`,
    body.products?.length && `Products: ${body.products.join(", ")}`,
    f.note && `Notes: ${f.note}`,
  ]
    .filter(Boolean)
    .join("\n");
  const whatsapp = `https://wa.me/${WA_NUMBER}?text=${encodeURIComponent(summary)}`;

  console.log(JSON.stringify({ t: "neema_lead", sessionId, captured: Boolean(lead), leadId: lead?.leadId, intent: body.intent }));

  return Response.json({
    ok: true,
    captured: Boolean(lead),
    leadId: lead?.leadId,
    whatsapp,
    message: lead
      ? `Thank you — our team has your details and will follow up shortly. Reference ${lead.leadId}.`
      : "Thank you — I've prepared your enquiry. Tap below to send it to our team on WhatsApp and we'll take it from there.",
  });
}
