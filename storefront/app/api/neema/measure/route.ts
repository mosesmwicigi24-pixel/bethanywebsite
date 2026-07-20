import type { MeasureResult, MeasurementEstimate } from "@/lib/neema";

/* Neema measurement copilot (advisory §5.1). A customer uploads a photo;
   Grok vision estimates garment measurements keyed to the product's own
   template, which prefill the measurement form for the customer to CONFIRM
   and edit before ordering — estimates, never commitments.

   Server-side only. When vision isn't configured (no NEEMA_API_KEY) or the
   photo is unusable, returns available:false with self-measure guidance, so
   the made-to-order flow still works. Vision model is configurable and
   defaults to the chat model (grok-4 is multimodal):
     NEEMA_VISION_MODEL (falls back to NEEMA_MODEL, then "grok-4"). */

const AI_KEY = process.env.NEEMA_API_KEY;
const AI_URL = process.env.NEEMA_API_URL || "https://api.x.ai/v1";
const VISION_MODEL = process.env.NEEMA_VISION_MODEL || process.env.NEEMA_MODEL || "grok-4";

const MAX_IMAGE_CHARS = 3_000_000; // ~2.2MB image as a base64 data URL (client downscales first)
const RATE_LIMIT = 6; // vision calls
const RATE_WINDOW_MS = 60_000;

const SELF_MEASURE_GUIDE =
  "Wear a fitted shirt and stand straight. With a soft tape, in inches: Neck — around the base; Shoulders — seam to seam across the back; Sleeve — shoulder point to wrist; Chest — the fullest part, under the arms; Full length — shoulder to the hem you want. Enter each below and we'll sew to your numbers.";

const buckets = new Map<string, { count: number; resetAt: number }>();
function rateLimited(id: string): boolean {
  const now = Date.now();
  const b = buckets.get(id);
  if (!b || now > b.resetAt) {
    buckets.set(id, { count: 1, resetAt: now + RATE_WINDOW_MS });
    return false;
  }
  b.count += 1;
  return b.count > RATE_LIMIT;
}

interface MeasureBody {
  image?: string; // data:image/...;base64,...
  garment?: string;
  template?: string[]; // measurement field names to fill
  sessionId?: string;
}

function fallback(guidance = SELF_MEASURE_GUIDE, notes?: string): MeasureResult {
  return { available: false, estimates: [], guidance, notes };
}

async function estimateFromPhoto(image: string, garment: string, template: string[]): Promise<MeasureResult> {
  const names = template.length ? template : ["Neck", "Shoulders", "Sleeves", "Chest", "Full Length"];
  const system =
    "You are a master tailor's assistant for clergy garments (cassocks, gowns, chasubles, shirts). " +
    "From a photo of a person, estimate their body measurements in INCHES to sew a made-to-order garment. " +
    "Be realistic and cautious — these are estimates the customer will confirm. If the photo does not clearly " +
    "show a standing person (or is unusable), return an empty estimates array and explain briefly in notes.";
  const instruction =
    `Estimate these measurements in inches for a ${garment || "clergy garment"}: ${names.join(", ")}. ` +
    `Return ONLY JSON: {"estimates":[{"name":"<exactly one of the listed names>","value":"<number in inches>","confidence":"low|medium|high"}],"notes":"<short caveat, or why the photo can't be used>"}. ` +
    `Use the exact names given; omit any you cannot infer. Do not invent precision — round to the nearest half inch.`;

  const r = await fetch(`${AI_URL}/chat/completions`, {
    method: "POST",
    headers: { "Content-Type": "application/json", Authorization: `Bearer ${AI_KEY}` },
    body: JSON.stringify({
      model: VISION_MODEL,
      temperature: 0.2,
      response_format: { type: "json_object" },
      messages: [
        { role: "system", content: system },
        {
          role: "user",
          content: [
            { type: "text", text: instruction },
            { type: "image_url", image_url: { url: image } },
          ],
        },
      ],
    }),
  });
  if (!r.ok) throw new Error(`vision ${r.status}`);
  const data = await r.json();
  const raw = data.choices?.[0]?.message?.content ?? "";
  let parsed: { estimates?: unknown; notes?: unknown };
  try {
    parsed = JSON.parse(String(raw).trim().replace(/^```(?:json)?/i, "").replace(/```$/, "").trim());
  } catch {
    return fallback(SELF_MEASURE_GUIDE, "I couldn't read that photo — please enter your measurements below.");
  }

  const allow = new Set(names.map((n) => n.toLowerCase()));
  const estimates: MeasurementEstimate[] = (Array.isArray(parsed.estimates) ? parsed.estimates : [])
    .map((e) => e as { name?: unknown; value?: unknown; confidence?: unknown })
    .filter((e) => typeof e?.name === "string" && (typeof e.value === "string" || typeof e.value === "number"))
    .map((e) => ({
      name: String(e.name),
      value: String(e.value).replace(/[^\d.]/g, ""), // keep the number; the form shows inches
      confidence: ["low", "medium", "high"].includes(e.confidence as string) ? (e.confidence as MeasurementEstimate["confidence"]) : undefined,
    }))
    .filter((e) => e.value && (!allow.size || allow.has(e.name.toLowerCase())));

  if (!estimates.length) return fallback(SELF_MEASURE_GUIDE, typeof parsed.notes === "string" ? parsed.notes : "I couldn't estimate from that photo — please enter your measurements below.");
  return { available: true, estimates, notes: typeof parsed.notes === "string" ? parsed.notes.slice(0, 240) : undefined };
}

export async function POST(request: Request): Promise<Response> {
  const started = Date.now();
  let body: MeasureBody;
  try {
    body = (await request.json()) as MeasureBody;
  } catch {
    return Response.json({ error: "Invalid request" }, { status: 400 });
  }

  const sessionId = String(body.sessionId ?? "anon").slice(0, 80);
  if (rateLimited(sessionId)) return Response.json({ error: "One moment before the next photo, please." }, { status: 429 });

  const image = typeof body.image === "string" ? body.image : "";
  if (!image.startsWith("data:image/")) return Response.json({ error: "Please attach a photo." }, { status: 422 });
  if (image.length > MAX_IMAGE_CHARS) return Response.json({ error: "That image is too large — try a smaller photo." }, { status: 413 });

  const template = Array.isArray(body.template) ? body.template.filter((t) => typeof t === "string").slice(0, 20) : [];

  let result: MeasureResult;
  let mode = AI_KEY ? "vision" : "fallback";
  try {
    result = AI_KEY ? await estimateFromPhoto(image, String(body.garment ?? ""), template) : fallback();
  } catch (err) {
    mode = "fallback";
    console.error("[neema:measure] vision failed:", err instanceof Error ? err.message : err);
    result = fallback(SELF_MEASURE_GUIDE, "I couldn't read that photo just now — please enter your measurements below.");
  }

  console.log(JSON.stringify({ t: "neema_measure", mode, sessionId, available: result.available, estimates: result.estimates.length, ms: Date.now() - started }));
  return Response.json(result);
}
