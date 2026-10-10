/* Tiny window-event bus so chrome that lives in different trees (the mobile
   tab bar, the Browse sheet, the home reel) can open the site-wide widgets —
   search and Neema — without lifting their state into a provider. */

export const OPEN_SEARCH = "bh:open-search";
export const OPEN_NEEMA = "bh:open-neema";

export const openSearch = () => window.dispatchEvent(new Event(OPEN_SEARCH));
/** Open Neema, optionally with a question ready in the box (not sent). */
export const openNeema = (draft?: string) =>
  window.dispatchEvent(new CustomEvent<string | undefined>(OPEN_NEEMA, { detail: draft }));
