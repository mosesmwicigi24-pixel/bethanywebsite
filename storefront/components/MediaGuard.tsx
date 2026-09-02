"use client";

import { useEffect, useState } from "react";

/**
 * Media protection deterrent — the jojosfashion.com "Protected content"
 * pattern, scoped to what actually needs it.
 *
 * What it does: a right-click or drag on a product photo/clip, a
 * PrintScreen press, the Mac screenshot shortcuts, or Ctrl/Cmd+S / +U
 * flash a branded, blurred "Protected content" veil for ~2.5 s (and
 * PrintScreen drops our name into the clipboard). What it deliberately
 * does NOT do: block text selection or Ctrl+C (shoppers copy names and
 * prices into WhatsApp), block F12/DevTools (unreliable and hostile),
 * or run on form fields. It never fires inside inputs.
 *
 * Be honest about what this is: a "please don't" sign. The real
 * protection is the watermark the Hub burns into every product photo at
 * upload — that survives any screenshot; this veil does not.
 */
const SHIELD_KEYS = new Set(["s", "u"]);
const VEIL_MS = 2400;

export default function MediaGuard() {
  const [shown, setShown] = useState(false);

  useEffect(() => {
    let timer: number | undefined;
    const flash = () => {
      setShown(true);
      window.clearTimeout(timer);
      timer = window.setTimeout(() => setShown(false), VEIL_MS);
    };
    const isMedia = (t: EventTarget | null) =>
      t instanceof Element && Boolean(t.closest("img, video, picture")) && !t.closest("[data-guard-exempt]");
    const isEditing = (t: EventTarget | null) =>
      t instanceof Element && Boolean(t.closest("input, textarea, select, [contenteditable=''], [contenteditable='true']"));

    const onContextMenu = (e: MouseEvent) => {
      if (isMedia(e.target)) { e.preventDefault(); flash(); }
    };
    const onDragStart = (e: DragEvent) => {
      if (isMedia(e.target)) e.preventDefault();
    };
    const onKeyDown = (e: KeyboardEvent) => {
      if (isEditing(e.target)) return;
      const k = e.key.toLowerCase();
      const mod = e.metaKey || e.ctrlKey;
      const printScreen = k === "printscreen";
      const macShot = e.metaKey && e.shiftKey && ["3", "4", "5"].includes(k);
      const savePage = mod && !e.altKey && SHIELD_KEYS.has(k);
      if (!printScreen && !macShot && !savePage) return;
      if (savePage) e.preventDefault();
      flash();
      if (printScreen && navigator.clipboard?.writeText) {
        navigator.clipboard.writeText("Bethany House — bethanyhouse.co.ke").catch(() => { /* clipboard blocked */ });
      }
    };

    document.addEventListener("contextmenu", onContextMenu);
    document.addEventListener("dragstart", onDragStart);
    document.addEventListener("keydown", onKeyDown);
    return () => {
      window.clearTimeout(timer);
      document.removeEventListener("contextmenu", onContextMenu);
      document.removeEventListener("dragstart", onDragStart);
      document.removeEventListener("keydown", onKeyDown);
    };
  }, []);

  if (!shown) return null;
  return (
    <div className="guard" role="status" aria-live="polite" aria-label="Content protected">
      <div className="guard-card">
        <img src="/brand/mark-gold.png" alt="" data-guard-exempt="" />
        <b>Bethany House</b>
        <span>Protected content</span>
      </div>
    </div>
  );
}
