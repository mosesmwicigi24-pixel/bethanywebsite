"use client";

import { useEffect, useState, useSyncExternalStore } from "react";
import { openNeema, openSearch } from "@/lib/ui-events";
import { SITE } from "@/lib/site";

/** Tablet/desktop header search field — opens the command-palette search. */
export function NavFind() {
  return (
    <button type="button" className="nav-find" onClick={openSearch} aria-label="Search products">
      <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7" /><path d="m20 20-3.5-3.5" /></svg>
      <span>Search chalices, gowns, stoles…</span>
      <kbd>⌘K</kbd>
    </button>
  );
}

/** HELP — opens Neema, the same as the phone tab bar's Help tab. */
export function NavHelp() {
  return (
    <button type="button" className="nav-ico nav-help" onClick={() => openNeema()}>
      <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 12a8 8 0 0 1-11.6 7.1L4 20l1-4.1A8 8 0 1 1 20 12z" /></svg>
      <span className="nav-lbl">Help</span>
    </button>
  );
}

const MESSAGES = [
  <>Free Nairobi CBD delivery on orders over <b>KES 10,000</b></>,
  <>Order before <b>2 PM</b> for same-day Nairobi delivery</>,
  <>Parish &amp; diocese accounts — <b>quotes in a day</b></>,
  <>Free engraving on communion ware</>,
];
const TOPBAR_KEY = "bh-topbar-closed";
const TOPBAR_EVENT = "bh:topbar";
/** The remembered close, read as an external store (server snapshot: open). */
const topbarStore = {
  sub: (cb: () => void) => { window.addEventListener(TOPBAR_EVENT, cb); return () => window.removeEventListener(TOPBAR_EVENT, cb); },
  get: () => { try { return Boolean(localStorage.getItem(TOPBAR_KEY)); } catch { return false; } },
  close: () => { try { localStorage.setItem(TOPBAR_KEY, "1"); } catch { /* storage blocked */ } window.dispatchEvent(new Event(TOPBAR_EVENT)); },
};

/**
 * The slim black bar above the header on tablets and desktops (oraimo's
 * idiom): one message at a time, cross-fading, WhatsApp on the right, and a
 * close that is remembered. Phones never see it.
 */
export function TopBar() {
  const [i, setI] = useState(0);
  const closed = useSyncExternalStore(topbarStore.sub, topbarStore.get, () => false);
  useEffect(() => {
    if (closed || window.matchMedia("(prefers-reduced-motion:reduce)").matches) return;
    const t = window.setInterval(() => setI((n) => (n + 1) % MESSAGES.length), 4500);
    return () => window.clearInterval(t);
  }, [closed]);

  if (closed) return null;
  return (
    <div className="topbar" role="region" aria-label="Store announcements">
      <div className="wrap">
        <span className="tb-gap" aria-hidden="true" />
        <p className="tb-msg" aria-live="polite">
          {MESSAGES.map((m, n) => <span key={n} className={n === i ? "on" : ""} aria-hidden={n !== i}>{m}</span>)}
        </p>
        <span className="tb-end">
          <a href={SITE.whatsapp} target="_blank" rel="noopener">WhatsApp us</a>
          <button type="button" aria-label="Close announcements"
            onClick={topbarStore.close}>×</button>
        </span>
      </div>
    </div>
  );
}

/**
 * Header scroll state for the tablet/desktop header: html[data-scrolled] once
 * the page moves (the header turns solid), html[data-scroll-dir="down"] while
 * scrolling down past the hero area (the department row tucks away).
 */
export function NavScroll() {
  useEffect(() => {
    const html = document.documentElement;
    let last = window.scrollY;
    let raf = 0;
    const on = () => {
      cancelAnimationFrame(raf);
      raf = requestAnimationFrame(() => {
        const y = window.scrollY;
        if (y > 8) html.dataset.scrolled = "1"; else delete html.dataset.scrolled;
        if (Math.abs(y - last) > 6) {
          if (y > last && y > 320) html.dataset.scrollDir = "down";
          else delete html.dataset.scrollDir;
          last = y;
        }
      });
    };
    on();
    window.addEventListener("scroll", on, { passive: true });
    return () => { window.removeEventListener("scroll", on); cancelAnimationFrame(raf); };
  }, []);
  return null;
}
