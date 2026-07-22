/* ============================================================
   Seasonal skin — read the hub's active liturgical season.

   The storefront's navy + gold brand theme is NEVER recoloured. A season is
   expressed only through additive touches (a slim seasonal strip: motif +
   name + scripture, and the Blessed Friday campaign when one is running). The
   accent from the hub is used solely to tint the small season motif glyph.

   Source of truth: GET {HUB}/site/theme (see bethany-house SiteController).
   Server-only fetch with ISR; out of season → all nulls → default look.
   ============================================================ */

const HUB = process.env.NEXT_PUBLIC_HUB_API; // https://hub.bethanyhouse.co.ke/api/v1
const REVALIDATE = 300; // seconds — season flips within one window of its date

export interface SeasonTheme {
  key: string;
  name: string;
  tagline?: string | null;
  scripture?: string | null;
  theme?: {
    accent?: string; accentInk?: string; accentSoft?: string;
    onAccent?: string; liturgical?: string; motif?: string;
  } | null;
  ends_at?: string | null;
}
export interface Campaign {
  name: string;
  discount_type: string;      // percentage | fixed
  discount_value: number;
  ends_at?: string | null;
}
export interface SeasonBanner {
  title: string; subtitle?: string | null; image_url: string;
  link_url?: string | null; link_text?: string | null;
}
export interface SiteTheme {
  season: SeasonTheme | null;
  campaign: Campaign | null;
  banner: SeasonBanner | null;
}

const EMPTY: SiteTheme = { season: null, campaign: null, banner: null };

/** The currently-active season + campaign, from the hub. Never throws. */
export async function getSiteTheme(): Promise<SiteTheme> {
  if (!HUB) return EMPTY;
  try {
    const r = await fetch(`${HUB}/site/theme`, {
      next: { revalidate: REVALIDATE },
      headers: { Accept: "application/json" },
    });
    if (!r.ok) return EMPTY;
    const j = (await r.json()) as SiteTheme;
    return { season: j.season ?? null, campaign: j.campaign ?? null, banner: j.banner ?? null };
  } catch {
    return EMPTY;
  }
}

/** Motif key → glyph for the small seasonal icon (the only seasonal colour cue). */
export const MOTIF_GLYPH: Record<string, string> = {
  lily: "🕊️", flame: "🔥", wheat: "🌾", star: "✦", cross: "✝", default: "✦",
};

/**
 * Local preview palettes — mirror the hub's seed so the seasonal strip can be
 * QA'd out of season via `?season=<key>` (e.g. ?season=harvest). Preview shows
 * the seasonal LOOK only (no campaign/discount). The hub stays the source of
 * truth for the real, date-driven season.
 */
export const SEASON_PREVIEWS: Record<string, SeasonTheme> = {
  "lent-easter": {
    key: "lent-easter", name: "Lent → Easter",
    scripture: "“He is not here; he has risen!” — Luke 24:6",
    theme: { accent: "#6f9e79", motif: "lily" }, ends_at: null,
  },
  pentecost: {
    key: "pentecost", name: "Pentecost",
    scripture: "“They saw what seemed to be tongues of fire.” — Acts 2:3",
    theme: { accent: "#c1352b", motif: "flame" }, ends_at: null,
  },
  harvest: {
    key: "harvest", name: "Harvest Thanksgiving",
    scripture: "“Honour the Lord with the firstfruits of all your crops.” — Proverbs 3:9",
    theme: { accent: "#b5791f", motif: "wheat" }, ends_at: null,
  },
  "advent-christmas": {
    key: "advent-christmas", name: "Advent → Christmas",
    scripture: "“For unto us a child is born.” — Isaiah 9:6",
    theme: { accent: "#5b3a8e", motif: "star" }, ends_at: null,
  },
};
