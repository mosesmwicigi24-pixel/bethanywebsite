"use client";

import { useCallback, useEffect, useMemo, useRef, useState } from "react";
import Link from "next/link";
import { usePathname } from "next/navigation";
import Img from "./Img";
import { Price } from "./Money";
import { useCatalog } from "@/lib/catalogClient";
import { useCart } from "@/lib/cart";
import type { NeemaReply } from "@/lib/neema";

/* Neema — the customer-facing chat widget (advisory §3.1).
   Renders Neema's structured replies as real UI: product cards, one-tap
   questions and actions — not plain chat text. Talks only to /api/neema;
   never to a model or the Hub directly. Reuses the site's Img + Price (so
   currency + imagery match everywhere) and the shared client catalog. */

interface Msg {
  role: "user" | "assistant";
  content: string;
  reply?: NeemaReply;
  synthetic?: boolean; // welcome/error bubbles — not sent back as history
}

/** A minimal reply for synthetic bubbles (confirmations, errors). */
const blankReply = (actions: NeemaReply["actions"] = []): NeemaReply => ({
  intent: "other", message: "", confidence: 0, products: [], questions: [], actions,
  handoff: { required: false }, sources: [], analytics: { readiness: "low", stage: "support" }, grounded: false,
});

const WELCOME: Msg = {
  role: "assistant",
  synthetic: true,
  content:
    "Hello, I'm Neema. I can help you choose communion elements, clergy apparel or gifts — and get them to your church anywhere in the world. What are you looking for?",
  reply: {
    intent: "greeting", message: "", confidence: 1, products: [],
    questions: [
      { id: "communion", label: "Communion elements" },
      { id: "clergy", label: "Clergy apparel & vestments" },
      { id: "gifts", label: "Gifts & accessories" },
      { id: "quote", label: "A quote for our parish" },
    ],
    actions: [], handoff: { required: false }, sources: [],
    analytics: { readiness: "low", stage: "consideration" }, grounded: false,
  },
};

// Stable idempotency key (crypto.randomUUID with a graceful fallback for
// older/insecure contexts — the live site is https, so the try path is used).
const newId = (): string => {
  try { return crypto.randomUUID(); } catch { return Math.random().toString(36).slice(2); }
};

// Always resolve a WhatsApp action to an absolute wa.me URL — a bare phone
// number rendered as an href would be treated as a relative path and 404.
const DEFAULT_WA = "https://wa.me/254727891989";
const waHref = (v?: string): string => {
  if (v && /^https?:\/\//i.test(v)) return v;
  const digits = (v || "").replace(/\D/g, "");
  return digits.length >= 9 ? `https://wa.me/${digits}` : DEFAULT_WA;
};

export default function Neema() {
  const [open, setOpen] = useState(false);
  const [messages, setMessages] = useState<Msg[]>([WELCOME]);
  const [input, setInput] = useState("");
  const [loading, setLoading] = useState(false);
  const [forms, setForms] = useState<Record<number, Record<string, string>>>({});
  const [doneForms, setDoneForms] = useState<Record<number, boolean>>({});
  const [sending, setSending] = useState<number | null>(null);
  const sid = useRef<string>("");
  const scroller = useRef<HTMLDivElement>(null);
  const inputRef = useRef<HTMLInputElement>(null);
  const reqIds = useRef<Record<number, string>>({}); // one idempotency key per capture form

  const pathname = usePathname();
  const { bySlug } = useCatalog();
  const cart = useCart();
  // Intent-aware context for the gateway — stable across renders so `send`'s
  // memo holds (a product page opens Neema already knowing the product).
  const pageContext = useMemo(() => {
    const slug = pathname?.startsWith("/product/") ? decodeURIComponent(pathname.split("/")[2] ?? "") : undefined;
    return { path: pathname ?? undefined, productSlug: slug, category: slug ? bySlug(slug)?.category : undefined };
  }, [pathname, bySlug]);

  // Stable per-visitor session id (also lets the gateway rate-limit + thread memory later).
  useEffect(() => {
    try {
      sid.current = localStorage.getItem("bh-neema-sid") || crypto.randomUUID();
      localStorage.setItem("bh-neema-sid", sid.current);
    } catch {
      sid.current = Math.random().toString(36).slice(2);
    }
  }, []);

  useEffect(() => {
    if (open) inputRef.current?.focus();
  }, [open]);

  useEffect(() => {
    scroller.current?.scrollTo({ top: scroller.current.scrollHeight, behavior: "smooth" });
  }, [messages, loading]);

  const send = useCallback(
    async (text: string) => {
      const content = text.trim();
      if (!content || loading) return;
      const next: Msg[] = [...messages, { role: "user", content }];
      setMessages(next);
      setInput("");
      setLoading(true);
      try {
        const res = await fetch("/api/neema", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            messages: next.filter((m) => !m.synthetic).map((m) => ({ role: m.role, content: m.content })).slice(-12),
            sessionId: sid.current || "anon",
            locale: typeof navigator !== "undefined" ? navigator.language : undefined,
            pageContext,
          }),
        });
        if (!res.ok) throw new Error(String(res.status));
        const reply = (await res.json()) as NeemaReply;
        setMessages((m) => [...m, { role: "assistant", content: reply.message, reply }]);
      } catch {
        setMessages((m) => [
          ...m,
          {
            role: "assistant", synthetic: true,
            content: "I'm having trouble reaching our system right now — our team can help you directly on WhatsApp.",
            reply: blankReply([{ type: "whatsapp", label: "Chat on WhatsApp", value: "https://wa.me/254727891989" }]),
          },
        ]);
      } finally {
        setLoading(false);
      }
    },
    [messages, loading, pageContext],
  );

  const setField = useCallback((i: number, id: string, val: string) => {
    setForms((f) => ({ ...f, [i]: { ...(f[i] || {}), [id]: val } }));
  }, []);

  const submitLead = useCallback(
    async (i: number, intent: string, productSlugs: string[]) => {
      const vals = forms[i] || {};
      if (!vals.phone?.trim() || sending !== null) return;
      // One stable idempotency key per form instance — reused on any retry so
      // the hub dedupes a double-submit to a single lead.
      const clientRequestId = reqIds.current[i] || (reqIds.current[i] = newId());
      setSending(i);
      try {
        const res = await fetch("/api/neema/lead", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ sessionId: sid.current || "anon", intent, fields: vals, products: productSlugs, pageContext, clientRequestId }),
        });
        const data = await res.json();
        setDoneForms((d) => ({ ...d, [i]: true }));
        setMessages((m) => [
          ...m,
          {
            role: "assistant", synthetic: true,
            content: data.message || "Thank you — our team will follow up shortly.",
            reply: blankReply(!data.captured && data.whatsapp ? [{ type: "whatsapp", label: "Send on WhatsApp", value: data.whatsapp }] : []),
          },
        ]);
      } catch {
        setMessages((m) => [
          ...m,
          {
            role: "assistant", synthetic: true,
            content: "Sorry, I couldn't send that just now — please reach us on WhatsApp.",
            reply: blankReply([{ type: "whatsapp", label: "Chat on WhatsApp", value: "https://wa.me/254727891989" }]),
          },
        ]);
      } finally {
        setSending(null);
      }
    },
    [forms, sending, pageContext],
  );

  return (
    <>
      <button
        className="chat-fab"
        aria-label={open ? "Close Neema chat" : "Chat with Neema"}
        aria-expanded={open}
        onClick={() => setOpen((v) => !v)}
      >
        <span className="chat-fab-mark"><img src="/brand/mark-gold.png" alt="" /></span>
        <span className="chat-fab-label">Chat with Neema</span>
      </button>

      {open && (
        <div className="neema" role="dialog" aria-label="Chat with Neema" onKeyDown={(e) => e.key === "Escape" && setOpen(false)}>
          <header className="neema-head">
            <div className="neema-id">
              <span className="neema-ava">
                <span className="neema-avatar"><img src="/brand/mark-gold.png" alt="" /></span>
                <span className="neema-dot" aria-hidden="true"></span>
              </span>
              <div>
                <b>Neema</b>
                <span className="neema-sub">Bethany House assistant</span>
              </div>
            </div>
            <button className="neema-x" aria-label="Close" onClick={() => setOpen(false)}>×</button>
          </header>

          <div className="neema-log" ref={scroller}>
            {messages.map((m, i) => (
              <div key={i} className={`neema-turn ${m.role}`}>
                {m.content && <div className="neema-bubble">{m.content}</div>}

                {m.reply?.products?.map((ref) => {
                  const p = bySlug(ref.slug);
                  if (!p) return null;
                  const readyMade = !p.producible && p.inStock !== false;
                  return (
                    <div className="neema-prod" key={ref.slug}>
                      <Link className="neema-prod-main" href={`/product/${p.slug}`} onClick={() => setOpen(false)}>
                        <span className="neema-prod-img"><Img src={p.img} alt={p.name} /></span>
                        <span className="neema-prod-body">
                          <b>{p.name}</b>
                          <span className="neema-prod-price"><Price p={p} /></span>
                        </span>
                        <span className="neema-prod-go" aria-hidden="true">›</span>
                      </Link>
                      {/* the closer: every card can be bought in one tap. Ready-made drops
                          into the cart; made-to-order routes to the page for measurements. */}
                      {readyMade ? (
                        <button className="neema-prod-add" onClick={() => { cart.add(p.slug); cart.setOpen(true); }}>Add to cart</button>
                      ) : (
                        <Link className="neema-prod-add" href={`/product/${p.slug}`} onClick={() => setOpen(false)}>
                          {p.producible ? "Order yours" : "View"}
                        </Link>
                      )}
                    </div>
                  );
                })}

                {!!m.reply?.questions?.length && (
                  <div className="neema-chips">
                    {m.reply.questions.map((q) => (
                      <button className="neema-chip" key={q.id} onClick={() => send(q.label)}>{q.label}</button>
                    ))}
                  </div>
                )}

                {!!m.reply?.actions?.length && (
                  <div className="neema-actions">
                    {m.reply.actions.map((a, j) => {
                      const label = a.label;
                      // External handoff — always an absolute wa.me URL, opened in a new tab.
                      if (a.type === "whatsapp" || a.type === "request_quote") {
                        return <a className="neema-action" key={j} href={waHref(a.value)} target="_blank" rel="noopener noreferrer">{label}</a>;
                      }
                      // The closer: one-tap add to cart. Ready-made items drop straight into
                      // the cart (drawer opens); made-to-order routes to the product page so
                      // measurements are captured first.
                      if (a.type === "add_to_cart" && a.value) {
                        const slug = a.value.replace(/^\/+/, "").replace(/^product\//, "").split(/[/?#]/)[0];
                        const p = bySlug(slug);
                        if (p && !p.producible) {
                          return <button className="neema-action neema-buy" key={j} onClick={() => { cart.add(slug); cart.setOpen(true); }}>{label}</button>;
                        }
                        return <Link className="neema-action neema-buy" key={j} href={`/product/${slug}`} onClick={() => setOpen(false)}>{label}</Link>;
                      }
                      // Internal navigation — only ever a safe in-app path (never a raw model value).
                      let href = "/shop";
                      if (a.type === "view_product" && a.value) href = `/product/${a.value.replace(/^\/+/, "").replace(/^product\//, "").split(/[/?#]/)[0]}`;
                      else if (a.type === "find_orders") href = "/orders";
                      else if (a.value && a.value.startsWith("/")) href = a.value;
                      return <Link className="neema-action" key={j} href={href} onClick={() => setOpen(false)}>{label}</Link>;
                    })}
                  </div>
                )}

                {m.reply?.capture && !doneForms[i] && (
                  <form
                    className="neema-capture"
                    onSubmit={(e) => { e.preventDefault(); submitLead(i, m.reply!.capture!.intent, (m.reply!.products ?? []).map((p) => p.slug)); }}
                  >
                    <div className="neema-capture-title">{m.reply.capture.title}</div>
                    {m.reply.capture.fields.map((fld) =>
                      fld.type === "textarea" ? (
                        <textarea key={fld.id} rows={2} placeholder={fld.placeholder || fld.label} aria-label={fld.label}
                          value={forms[i]?.[fld.id] ?? ""} onChange={(e) => setField(i, fld.id, e.target.value)} />
                      ) : fld.type === "select" ? (
                        <select key={fld.id} aria-label={fld.label} value={forms[i]?.[fld.id] ?? ""} onChange={(e) => setField(i, fld.id, e.target.value)}>
                          <option value="">{fld.label}</option>
                          {(fld.options ?? []).map((o) => <option key={o} value={o}>{o}</option>)}
                        </select>
                      ) : (
                        <input key={fld.id} type={fld.type === "tel" ? "tel" : fld.type === "email" ? "email" : "text"}
                          required={fld.required} placeholder={fld.placeholder || fld.label} aria-label={fld.label}
                          value={forms[i]?.[fld.id] ?? ""} onChange={(e) => setField(i, fld.id, e.target.value)} />
                      ),
                    )}
                    <button type="submit" disabled={sending === i}>{sending === i ? "Sending…" : m.reply.capture.submitLabel}</button>
                  </form>
                )}
                {m.reply?.capture && doneForms[i] && <div className="neema-capture-done">✓ Sent to our team</div>}
              </div>
            ))}

            {loading && (
              <div className="neema-turn assistant">
                <div className="neema-bubble neema-typing"><i></i><i></i><i></i></div>
              </div>
            )}
          </div>

          <form
            className="neema-input"
            onSubmit={(e) => { e.preventDefault(); send(input); }}
          >
            <input
              ref={inputRef}
              value={input}
              onChange={(e) => setInput(e.target.value)}
              placeholder="Ask Neema anything…"
              aria-label="Message Neema"
              maxLength={2000}
            />
            <button type="submit" aria-label="Send" disabled={loading || !input.trim()}>
              <svg viewBox="0 0 24 24" width="18" height="18"><path d="M4 12 20 4l-5 16-4-6-7-2z" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinejoin="round" /></svg>
            </button>
          </form>

          <div className="neema-foot">Neema can make mistakes — confirm prices &amp; sizes before ordering.</div>
        </div>
      )}
    </>
  );
}
