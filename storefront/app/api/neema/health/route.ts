import { providerStatus } from "@/lib/llm";

/* Neema health check — GET /api/neema/health.

   Reports which model providers are wired (a boolean per provider) and the
   effective fallback chain order with model names, so an env change can be
   confirmed at a glance:

     { ok, ready, mode, providers:{groq,anthropic,openai,gemini},
       chatChain:[…], visionChain:[…] }

   NEVER returns API keys or base URLs — only provider names, model names and
   presence flags. Forced dynamic so it reflects the live runtime env rather
   than a build-time snapshot. Server-side only. */

export const dynamic = "force-dynamic";

export function GET(): Response {
  const status = providerStatus();
  return Response.json({
    ok: true,
    ready: status.ready,
    mode: status.ready ? "live" : "deterministic-fallback",
    note: status.ready
      ? "Chat calls the first configured provider; the rest are fallbacks. Vision uses its own chain."
      : "No provider keys set — the catalog-grounded fallback answers, so the widget still works.",
    providers: status.configured,
    chatChain: status.chat,
    visionChain: status.vision,
  });
}
