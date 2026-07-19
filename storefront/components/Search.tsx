"use client";

import { useCallback, useEffect, useMemo, useRef, useState } from "react";
import { useRouter } from "next/navigation";
import { useCatalog } from "@/lib/catalogClient";
import { Price } from "./Money";

const POPULAR = ["Chalice", "Preaching gown", "Altar wine", "Stole", "Communion hosts", "Tallit"];

/** Command-palette style search: instant results, keyboard nav (↑ ↓ ⏎ Esc), ⌘K. */
export default function Search() {
  const [open, setOpen] = useState(false);
  const [q, setQ] = useState("");
  const [sel, setSel] = useState(0);
  const inputRef = useRef<HTMLInputElement>(null);
  const router = useRouter();
  const { all } = useCatalog();
  const products = all.filter((p) => !p.variantId); // parents + simples, not variants

  const hits = useMemo(() => {
    const t = q.trim().toLowerCase();
    if (!t) return [];
    return products
      .filter((p) => `${p.name} ${p.category}`.toLowerCase().includes(t))
      .slice(0, 7);
  }, [q]);

  const close = useCallback(() => { setOpen(false); setQ(""); setSel(0); }, []);

  const go = useCallback((slug: string) => {
    close();
    router.push(`/product/${slug}`);
  }, [close, router]);

  useEffect(() => {
    const onKey = (e: KeyboardEvent) => {
      if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === "k") {
        e.preventDefault();
        setOpen((o) => !o);
      } else if (e.key === "Escape") close();
    };
    window.addEventListener("keydown", onKey);
    return () => window.removeEventListener("keydown", onKey);
  }, [close]);

  useEffect(() => {
    if (open) setTimeout(() => inputRef.current?.focus(), 60);
    document.body.style.overflow = open ? "hidden" : "";
    return () => { document.body.style.overflow = ""; };
  }, [open]);

  const onInputKey = (e: React.KeyboardEvent) => {
    if (e.key === "ArrowDown") { e.preventDefault(); setSel((s) => Math.min(s + 1, hits.length - 1)); }
    else if (e.key === "ArrowUp") { e.preventDefault(); setSel((s) => Math.max(s - 1, 0)); }
    else if (e.key === "Enter" && hits[sel]) go(hits[sel].slug);
  };

  return (
    <>
      <button aria-label="Search (⌘K)" onClick={() => setOpen(true)} style={{ display: "flex" }}>
        <svg viewBox="0 0 24 24" style={{ width: 20, height: 20, stroke: "#e8edf5", fill: "none", strokeWidth: 1.7 }}>
          <circle cx="11" cy="11" r="7" /><path d="m20 20-3.5-3.5" />
        </svg>
      </button>

      <div className={`search-veil ${open ? "open" : ""}`} onClick={close} role="dialog" aria-modal="true" aria-label="Search products">
        <div className="search-panel" onClick={(e) => e.stopPropagation()}>
          <div className="search-bar">
            <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7" /><path d="m20 20-3.5-3.5" /></svg>
            <input
              ref={inputRef}
              value={q}
              placeholder="Search chalices, gowns, stoles, gifts…"
              onChange={(e) => { setQ(e.target.value); setSel(0); }}
              onKeyDown={onInputKey}
              aria-label="Search products"
            />
            <kbd>ESC</kbd>
          </div>
          <div className="search-body">
            {!q && (
              <>
                <div className="search-h">Popular searches</div>
                <div className="search-chips">
                  {POPULAR.map((s) => <button key={s} onClick={() => setQ(s.split(" ")[0])}>{s}</button>)}
                </div>
                <div className="search-h">Best sellers</div>
                {products.filter((p) => p.badge === "best" || (p.reviews > 400)).slice(0, 4).map((p) => (
                  <button className="search-hit" key={p.slug} onClick={() => go(p.slug)}>
                    <span className="im"><img src={p.img} alt="" /></span>
                    <span style={{ minWidth: 0 }}><b>{p.name}</b><span>{p.category}</span></span>
                    <span className="pr"><Price p={p} /></span>
                  </button>
                ))}
              </>
            )}
            {q && hits.length === 0 && (
              <div className="search-empty">No matches for “{q}” — try “chalice”, “gown” or “stole”.</div>
            )}
            {hits.length > 0 && (
              <>
                <div className="search-h">Products</div>
                {hits.map((p, i) => (
                  <button
                    className={`search-hit ${i === sel ? "sel" : ""}`}
                    key={p.slug}
                    onMouseEnter={() => setSel(i)}
                    onClick={() => go(p.slug)}
                  >
                    <span className="im"><img src={p.img} alt="" /></span>
                    <span style={{ minWidth: 0 }}><b>{p.name}</b><span>{p.category}</span></span>
                    <span className="pr"><Price p={p} /></span>
                  </button>
                ))}
              </>
            )}
          </div>
        </div>
      </div>
    </>
  );
}
