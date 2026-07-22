import { NextRequest } from "next/server";

/* Geo-IP → currency, and the analytics beacon, in one same-origin call.
   Runs per-request on the storefront server (so it sees the real client IP via
   x-forwarded-for). Resolves the country with a free, no-key geo-IP service
   (cached in-memory), parses the device from the UA, fires a minimal visit
   record to the hub, and returns { country, currency } for the CurrencyProvider.
   Never throws — geo/analytics must not break the site. */

export const dynamic = "force-dynamic";

const HUB = process.env.NEXT_PUBLIC_HUB_API;

type Cur = "KES" | "USD" | "ZMW";
const currencyForCountry = (cc: string | null): Cur =>
  cc === "KE" ? "KES" : cc === "ZM" ? "ZMW" : "USD";

function clientIp(req: NextRequest): string {
  const xff = req.headers.get("x-forwarded-for") || "";
  const first = xff.split(",")[0]?.trim();
  return first || req.headers.get("x-real-ip") || "";
}

function isPrivate(ip: string): boolean {
  return (
    !ip ||
    ip === "127.0.0.1" ||
    ip.startsWith("::") ||
    ip.startsWith("10.") ||
    ip.startsWith("192.168.") ||
    /^172\.(1[6-9]|2\d|3[01])\./.test(ip)
  );
}

async function geoLookup(ip: string): Promise<string | null> {
  if (isPrivate(ip)) return null;
  // Primary: ipwho.is (free, no key, commercial-friendly, HTTPS).
  try {
    const r = await fetch(`https://ipwho.is/${ip}?fields=success,country_code`, {
      signal: AbortSignal.timeout(2500),
    });
    const j = await r.json();
    if (j?.success && typeof j.country_code === "string") return j.country_code;
  } catch {
    /* fall through */
  }
  // Fallback: country.is (free, no key).
  try {
    const r = await fetch(`https://api.country.is/${ip}`, { signal: AbortSignal.timeout(2500) });
    const j = await r.json();
    if (typeof j?.country === "string") return j.country;
  } catch {
    /* give up → default currency */
  }
  return null;
}

function parseDevice(ua: string) {
  const u = ua.toLowerCase();
  const isTablet = /ipad|tablet|playbook|silk/.test(u);
  const isPhone = /mobile|iphone|ipod|android.*mobile|windows phone/.test(u) && !isTablet;
  const device_type = isTablet ? "tablet" : isPhone ? "mobile" : "desktop";
  const os = /iphone|ipad|ipod| os \d|ios/.test(u)
    ? "iOS"
    : /android/.test(u)
      ? "Android"
      : /mac os x|macintosh/.test(u)
        ? "macOS"
        : /windows/.test(u)
          ? "Windows"
          : /linux|cros/.test(u)
            ? "Linux"
            : "Other";
  const browser = /edg\//.test(u)
    ? "Edge"
    : /chrome|crios/.test(u)
      ? "Chrome"
      : /firefox|fxios/.test(u)
        ? "Firefox"
        : /safari/.test(u)
          ? "Safari"
          : "Other";
  return { device_type, os, browser, is_mobile: isPhone || isTablet };
}

// In-memory country cache by IP (per server instance) — keeps the free geo API
// calls low without storing anything about the visitor.
const cache = new Map<string, { country: string | null; at: number }>();
const TTL = 60 * 60 * 1000;

export async function GET(req: NextRequest) {
  const ip = clientIp(req);
  const ua = req.headers.get("user-agent") || "";
  const path = new URL(req.url).searchParams.get("path") || "";

  let country: string | null = null;
  const hit = cache.get(ip);
  if (hit && Date.now() - hit.at < TTL) {
    country = hit.country;
  } else {
    country = await geoLookup(ip);
    if (ip) cache.set(ip, { country, at: Date.now() });
  }

  // Fire-and-forget visit beacon (country + device only, no IP). Never blocks.
  if (HUB) {
    fetch(`${HUB}/site/track`, {
      method: "POST",
      headers: { "Content-Type": "application/json", Accept: "application/json" },
      body: JSON.stringify({ country, path, ...parseDevice(ua) }),
    }).catch(() => {});
  }

  return Response.json({ country, currency: currencyForCountry(country) });
}
