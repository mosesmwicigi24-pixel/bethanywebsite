"use client";

import Link from "next/link";
import { useSearchParams } from "next/navigation";
import { MOTIF_GLYPH, SEASON_PREVIEWS, type SiteTheme } from "@/lib/theme";

/** Whole days until an ISO date, or null if past/absent. */
function daysLeft(iso?: string | null): number | null {
  if (!iso) return null;
  const ms = new Date(iso).getTime() - Date.now();
  return ms > 0 ? Math.ceil(ms / 86_400_000) : null;
}

/**
 * A slim seasonal strip shown ONLY when a liturgical season is active — a
 * subtle, additive touch that never recolours the navy/gold brand. The season
 * reads through the motif + name + scripture; the Blessed Friday campaign (from
 * the hub promotion) appears on the right when one is running.
 *
 * `?season=<key>` previews the seasonal look out of season (look only, no
 * discount) so it can be QA'd before its date.
 */
export default function SeasonRibbon({ site }: { site: SiteTheme }) {
  const previewKey = useSearchParams().get("season");
  const preview = previewKey ? SEASON_PREVIEWS[previewKey] : undefined;

  const season = preview ?? site.season;
  if (!season) return null;

  // In preview mode we show the look only — never a campaign/discount.
  const campaign = preview ? null : site.campaign;

  const motif = MOTIF_GLYPH[season.theme?.motif ?? "default"] ?? MOTIF_GLYPH.default;
  const accent = season.theme?.accent ?? undefined; // tints the motif glyph only
  const dLeft = daysLeft(campaign?.ends_at ?? season.ends_at);

  return (
    <div className="season-ribbon" role="region" aria-label={`${season.name} season`}>
      <div className="wrap">
        <span className="sr-left">
          <span className="sr-motif" style={accent ? { color: accent } : undefined} aria-hidden>
            {motif}
          </span>
          <b>{season.name}</b>
          {season.scripture && <em>{season.scripture}</em>}
        </span>

        {campaign ? (
          <Link href="/shop" className="sr-camp">
            {campaign.name || "Blessed Friday"} · up to {Math.round(campaign.discount_value)}% off
            {dLeft ? ` · ends in ${dLeft}d` : ""} ›
          </Link>
        ) : (
          <Link href="/shop" className="sr-camp">Shop the season ›</Link>
        )}
      </div>
    </div>
  );
}
