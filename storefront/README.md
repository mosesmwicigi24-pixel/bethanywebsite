This is a [Next.js](https://nextjs.org) project bootstrapped with [`create-next-app`](https://nextjs.org/docs/app/api-reference/cli/create-next-app).

## Neema AI gateway

The customer-facing AI assistant (the gold chat launcher, bottom-right) is wired
through a single server route — the browser never talks to a model or the Hub directly.

- **Entry** — `components/Neema.tsx` (the launcher + chat panel), mounted site-wide via
  `ChatFab` in `components/chrome.tsx`.
- **Gateway** — `app/api/neema/route.ts`. Enforces guardrails (size caps, per-session
  rate limit), runs Neema through a provider fallback chain with function-calling tools
  when configured, and otherwise falls back to a deterministic, catalog-grounded
  orchestrator so the chat always works. Returns a validated `NeemaReply` the panel
  renders as product cards, quick replies and actions.
- **Providers** — `lib/llm.ts` is a small multi-provider layer. Chat runs
  **Groq (primary, `llama-3.3-70b-versatile`) → Anthropic → OpenAI → Gemini**; only
  providers whose key is set are tried, and if one errors the next takes over. Groq,
  OpenAI and Gemini use their OpenAI-compatible endpoints; Anthropic uses its native
  Messages API.
- **Tools** — `search_products`, `get_order_status`, `create_lead` and
  `estimate_shipping` reuse the existing `lib/catalog.ts` and `lib/hub.ts` functions;
  `lib/neema.ts` holds the shared contract and catalog-grounding helpers.
- **Vision** — the measurement copilot (`app/api/neema/measure/route.ts`) runs a separate
  chain, **Gemini → OpenAI → Anthropic**. Groq's Llama-3.3 is text-only, so it's skipped
  for images.

Configure providers via server-only env (see `.env.example`): `GROQ_API_KEY` /
`GROQ_MODEL`, `ANTHROPIC_API_KEY` / `ANTHROPIC_MODEL`, `OPENAI_API_KEY` / `OPENAI_MODEL`,
`GEMINI_API_KEY` / `GEMINI_MODEL` (plus optional `*_API_URL` and `GEMINI_VISION_MODEL` /
`OPENAI_VISION_MODEL` / `ANTHROPIC_VISION_MODEL` overrides).
With no key set, the grounded fallback runs — useful for local dev and demos.

`GET /api/neema/health` reports which providers are wired (a boolean each, plus the
effective chat/vision chain order and model names — never the keys), so you can confirm
an env change took effect after a deploy/restart. Set `NEEMA_HEALTH_TOKEN` to require
`?token=<value>` on the endpoint (401 otherwise); leave it unset to keep the check open.

See `docs/AI_INTEGRATION_ADVISORY.md` for the wider plan.

## Getting Started

First, run the development server:

```bash
npm run dev
# or
yarn dev
# or
pnpm dev
# or
bun dev
```

Open [http://localhost:3000](http://localhost:3000) with your browser to see the result.

You can start editing the page by modifying `app/page.tsx`. The page auto-updates as you edit the file.

This project uses [`next/font`](https://nextjs.org/docs/app/building-your-application/optimizing/fonts) to automatically optimize and load [Geist](https://vercel.com/font), a new font family for Vercel.

## Learn More

To learn more about Next.js, take a look at the following resources:

- [Next.js Documentation](https://nextjs.org/docs) - learn about Next.js features and API.
- [Learn Next.js](https://nextjs.org/learn) - an interactive Next.js tutorial.

You can check out [the Next.js GitHub repository](https://github.com/vercel/next.js) - your feedback and contributions are welcome!

## Deploy on Vercel

The easiest way to deploy your Next.js app is to use the [Vercel Platform](https://vercel.com/new?utm_medium=default-template&filter=next.js&utm_source=create-next-app&utm_campaign=create-next-app-readme) from the creators of Next.js.

Check out our [Next.js deployment documentation](https://nextjs.org/docs/app/building-your-application/deploying) for more details.
