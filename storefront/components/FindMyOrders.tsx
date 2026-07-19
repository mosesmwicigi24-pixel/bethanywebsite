"use client";

import { useCallback, useEffect, useRef, useState } from "react";
import {
  requestCode, verifyCode, refreshMyOrders, syncOrders,
  getSession, saveSession, clearSession, lookupLive,
} from "@/lib/lookup";
import type { OrderRecord } from "@/lib/orders";

type Step = "contact" | "code" | "signedin";

/**
 * Passwordless "Find my orders". A returning guest enters the phone/email on
 * their order, gets a one-time code (WhatsApp / email), and their full history
 * syncs onto this device. The verified session is remembered so we can refresh
 * silently next time. Never blocks checkout — it lives only on /orders.
 */
export default function FindMyOrders({ onSynced }: { onSynced: (r: OrderRecord[]) => void }) {
  const [step, setStep] = useState<Step>("contact");
  const [contact, setContact] = useState("");
  const [code, setCode] = useState("");
  const [dest, setDest] = useState("");
  const [channels, setChannels] = useState<string[]>([]);
  const [hint, setHint] = useState("");
  const [busy, setBusy] = useState(false);
  const [err, setErr] = useState("");
  const booted = useRef(false);

  const onSyncedRef = useRef(onSynced);
  onSyncedRef.current = onSynced;

  useEffect(() => {
    if (booted.current) return;
    booted.current = true;
    const s = getSession();
    if (!s) return;
    setHint(s.hint);
    setStep("signedin");
    refreshMyOrders(s.token).then((recs) => {
      if (recs) onSyncedRef.current(syncOrders(recs));
      else { clearSession(); setStep("contact"); }
    });
  }, []);

  const send = useCallback(async () => {
    setErr(""); setBusy(true);
    const r = await requestCode(contact.trim());
    setBusy(false);
    if (r.status === 429) {
      setDest(r.destination ?? ""); setStep("code");
      setErr("A code was just sent — check your messages before retrying.");
      return;
    }
    if (!r.ok) { setErr(r.message ?? "Enter a valid phone number or email."); return; }
    // Nothing could be delivered (e.g. a phone with no reachable channel yet):
    // guide them to the other contact instead of a dead code screen.
    if ((r.channels ?? []).length === 0) {
      setErr(contact.includes("@")
        ? "We couldn't send a code to that email. Try the phone number on your order."
        : "We couldn't reach that number yet. Try the email address on your order instead.");
      return;
    }
    setDest(r.destination ?? ""); setChannels(r.channels ?? []); setStep("code");
  }, [contact]);

  const verify = useCallback(async () => {
    setErr(""); setBusy(true);
    const r = await verifyCode(contact.trim(), code.trim());
    setBusy(false);
    if (!r.ok || !r.token) { setErr(r.message ?? "That code didn't work."); return; }
    onSyncedRef.current(syncOrders(r.orders ?? []));
    saveSession({ token: r.token, hint: dest, contact: contact.trim() });
    setHint(dest); setCode(""); setStep("signedin");
  }, [contact, code, dest]);

  const signOut = useCallback(() => {
    clearSession();
    setStep("contact"); setContact(""); setCode(""); setDest(""); setErr("");
  }, []);

  if (!lookupLive()) return null;

  if (step === "signedin") {
    return (
      <div className="lookup-bar">
        <span>✓ Showing orders for <b>{hint}</b></span>
        <button className="linkbtn" onClick={signOut}>Not you? Sign out</button>
      </div>
    );
  }

  const channelWord = channels.includes("whatsapp") ? "WhatsApp"
    : channels.includes("email") ? "email" : "messages";

  return (
    <div className="lookup-card">
      <div className="lookup-ico" aria-hidden>🔒</div>
      <h3>See your orders on any device</h3>
      <p>Ordered before? Enter the phone or email on your order — we’ll send a one-time
        code. No password, no account needed.</p>

      {step === "contact" && (
        <div className="lookup-form">
          <input
            value={contact}
            onChange={(e) => setContact(e.target.value)}
            onKeyDown={(e) => { if (e.key === "Enter" && contact.trim()) send(); }}
            placeholder="Phone (07…) or email"
            inputMode="email" autoComplete="off" spellCheck={false}
          />
          <button className="pill pill-gold" disabled={busy || !contact.trim()} onClick={send}>
            {busy ? "Sending…" : "Send code"}
          </button>
        </div>
      )}

      {step === "code" && (
        <>
          <p className="lookup-sent">Enter the 6-digit code sent to your {channelWord} <b>{dest}</b>.</p>
          <div className="lookup-form">
            <input
              value={code}
              onChange={(e) => setCode(e.target.value.replace(/\D/g, "").slice(0, 6))}
              onKeyDown={(e) => { if (e.key === "Enter" && code.length >= 4) verify(); }}
              placeholder="••••••" inputMode="numeric" className="lookup-code" autoFocus
            />
            <button className="pill pill-gold" disabled={busy || code.length < 4} onClick={verify}>
              {busy ? "Checking…" : "View my orders"}
            </button>
          </div>
          <div className="lookup-alt">
            <button className="linkbtn" onClick={send} disabled={busy}>Resend code</button>
            <button className="linkbtn" onClick={() => { setStep("contact"); setCode(""); setErr(""); }}>
              Use a different contact
            </button>
          </div>
        </>
      )}

      {err && <p className="lookup-err">{err}</p>}
    </div>
  );
}
