"use client";

import { useEffect } from "react";
import { contactChannel, track } from "@/lib/analytics";

/* Most Bethany House sales close on WhatsApp or a phone call, not in the
   checkout — so a tap on any WhatsApp, call or email link is the site's main
   conversion. One document-level listener (capture phase, so it runs even
   when the tap navigates away) reports every such link on every page as a
   `contact_click` event with the channel and the page it happened on.
   Mark `contact_click` as a key event in Google Analytics to count them. */
export default function ContactClicks() {
  useEffect(() => {
    const onClick = (e: MouseEvent) => {
      const a = (e.target as Element | null)?.closest?.("a[href]") as HTMLAnchorElement | null;
      if (!a) return;
      const channel = contactChannel(a.getAttribute("href") || "");
      if (!channel) return;
      track("contact_click", {
        channel,
        page_path: window.location.pathname,
        link_text: (a.textContent || "").trim().slice(0, 60),
      });
    };
    document.addEventListener("click", onClick, true);
    return () => document.removeEventListener("click", onClick, true);
  }, []);
  return null;
}
