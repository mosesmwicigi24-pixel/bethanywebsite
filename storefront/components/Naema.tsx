"use client";

import { useCallback, useEffect, useMemo, useRef, useState } from "react";
import Link from "next/link";
import { usePathname } from "next/navigation";
import Img from "./Img";
import { Price } from "./Money";
import { useCatalog } from "@/lib/catalogClient";
import type { NaemaReply } from "@/lib/naema";

/* Naema — the customer-facing chat widget (advisory §3.1).
   Renders Naema's structured replies as real UI: product cards, one-tap
   questions and actions — not plain chat text. Talks only to /api/naema;
   never to Grok or the Hub directly. Reuses the site's Img + Price (so
   currency + imagery match everywhere) and the shared client catalog. */

interface Msg {
  role: "user" | "assistant";
  content: string;
  reply?: NaemaReply;
  synthetic?: boolean; // welcome/error bubbles — not sent back as history
}

const WELCOME: Msg = {
  role: "assistant",
  synthetic: true,
  content:
    "Hello, I'm Naema. I can help you choose communion elements, clergy apparel or gifts — and get them to your church anywhere in the world. What are you looking for?",
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

export default function Naema() {
  const [open, setOpen] = useState(false);
  const [messages, setMessages] = useState<Msg[]>([WELCOME]);
  const [input, setInput] = useState("");
  const [loading, setLoading] = useState(false);
  const sid = useRef<string>("");
  const scroller = useRef<HTMLDivElement>(null);
  const inputRef = useRef<HTMLInputElement>(null);

  const pathname = usePathname();
  const { bySlug } = useCatalog();
  // Intent-aware context for the gateway — stable across renders so `send`'s
  // memo holds (a product page opens Naema already knowing the product).
  const pageContext = useMemo(() => {
    const slug = pathname?.startsWith("/product/") ? decodeURIComponent(pathname.split("/")[2] ?? "") : undefined;
    return { path: pathname ?? undefined, productSlug: slug, category: slug ? bySlug(slug)?.category : undefined };
  }, [pathname, bySlug]);

  // Stable per-visitor session id (also lets the gateway rate-limit + thread memory later).
  useEffect(() => {
    try {
      sid.current = localStorage.getItem("bh-naema-sid") || crypto.randomUUID();
      localStorage.setItem("bh-naema-sid", sid.current);
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
        const res = await fetch("/api/naema", {
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
        const reply = (await res.json()) as NaemaReply;
        setMessages((m) => [...m, { role: "assistant", content: reply.message, reply }]);
      } catch {
        setMessages((m) => [
          ...m,
          {
            role: "assistant", synthetic: true,
            content: "I'm having trouble reaching our system right now — our team can help you directly on WhatsApp.",
            reply: {
              intent: "other", message: "", confidence: 0, products: [], questions: [],
              actions: [{ type: "whatsapp", label: "Chat on WhatsApp", value: "https://wa.me/254727891989" }],
              handoff: { required: true }, sources: [], analytics: { readiness: "low", stage: "support" }, grounded: false,
            },
          },
        ]);
      } finally {
        setLoading(false);
      }
    },
    [messages, loading, pageContext],
  );

  return (
    <>
      <button
        className="chat-fab"
        aria-label={open ? "Close Naema chat" : "Ask Naema"}
        aria-expanded={open}
        onClick={() => setOpen((v) => !v)}
      >
        <img src="/brand/mark-gold.png" alt="" />
      </button>

      {open && (
        <div className="naema" role="dialog" aria-label="Chat with Naema" onKeyDown={(e) => e.key === "Escape" && setOpen(false)}>
          <header className="naema-head">
            <div className="naema-id">
              <span className="naema-avatar"><img src="/brand/mark-gold.png" alt="" /></span>
              <div>
                <b>Naema</b>
                <span className="naema-sub">Bethany House assistant</span>
              </div>
            </div>
            <button className="naema-x" aria-label="Close" onClick={() => setOpen(false)}>×</button>
          </header>

          <div className="naema-log" ref={scroller}>
            {messages.map((m, i) => (
              <div key={i} className={`naema-turn ${m.role}`}>
                {m.content && <div className="naema-bubble">{m.content}</div>}

                {m.reply?.products?.map((ref) => {
                  const p = bySlug(ref.slug);
                  if (!p) return null;
                  return (
                    <Link className="naema-prod" key={ref.slug} href={`/product/${p.slug}`} onClick={() => setOpen(false)}>
                      <span className="naema-prod-img"><Img src={p.img} alt={p.name} /></span>
                      <span className="naema-prod-body">
                        <b>{p.name}</b>
                        <span className="naema-prod-price"><Price p={p} /></span>
                      </span>
                      <span className="naema-prod-go" aria-hidden="true">›</span>
                    </Link>
                  );
                })}

                {!!m.reply?.questions?.length && (
                  <div className="naema-chips">
                    {m.reply.questions.map((q) => (
                      <button className="naema-chip" key={q.id} onClick={() => send(q.label)}>{q.label}</button>
                    ))}
                  </div>
                )}

                {!!m.reply?.actions?.length && (
                  <div className="naema-actions">
                    {m.reply.actions.map((a, j) => {
                      const label = a.label;
                      if (a.type === "whatsapp" || a.type === "request_quote") {
                        return <a className="naema-action" key={j} href={a.value || "https://wa.me/254727891989"} target="_blank" rel="noopener noreferrer">{label}</a>;
                      }
                      if (a.type === "view_product" && a.value) {
                        return <Link className="naema-action" key={j} href={`/product/${a.value}`} onClick={() => setOpen(false)}>{label}</Link>;
                      }
                      const href = a.type === "find_orders" ? "/orders" : a.value || "/shop";
                      return <Link className="naema-action" key={j} href={href} onClick={() => setOpen(false)}>{label}</Link>;
                    })}
                  </div>
                )}
              </div>
            ))}

            {loading && (
              <div className="naema-turn assistant">
                <div className="naema-bubble naema-typing"><i></i><i></i><i></i></div>
              </div>
            )}
          </div>

          <form
            className="naema-input"
            onSubmit={(e) => { e.preventDefault(); send(input); }}
          >
            <input
              ref={inputRef}
              value={input}
              onChange={(e) => setInput(e.target.value)}
              placeholder="Ask Naema anything…"
              aria-label="Message Naema"
              maxLength={2000}
            />
            <button type="submit" aria-label="Send" disabled={loading || !input.trim()}>
              <svg viewBox="0 0 24 24" width="18" height="18"><path d="M4 12 20 4l-5 16-4-6-7-2z" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinejoin="round" /></svg>
            </button>
          </form>

          <div className="naema-foot">Naema can make mistakes — confirm prices &amp; sizes before ordering.</div>
        </div>
      )}
    </>
  );
}
