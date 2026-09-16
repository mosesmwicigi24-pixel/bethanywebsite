import type { MetadataRoute } from "next";
import { SITE } from "@/lib/site";

/* Let crawlers index the catalogue; keep transactional and receipt pages
   out of the index (carts and tokenised order receipts have no search
   value and shouldn't be surfaced). Points crawlers at the sitemap.

   AI search crawlers are named EXPLICITLY and given the same access.
   A store wants to be the answer when someone asks ChatGPT, Perplexity,
   Claude or Gemini where to buy communion bread in Nairobi — naming the
   bots makes the welcome unambiguous and survives any future tightening
   of the wildcard rule. /llms.txt gives the same assistants a curated
   map of the store. */
const AI_CRAWLERS = [
  "GPTBot", // OpenAI — training + ChatGPT browsing corpus
  "OAI-SearchBot", // OpenAI — ChatGPT search index
  "ChatGPT-User", // OpenAI — live page fetches for a user
  "ClaudeBot", // Anthropic — crawl corpus
  "Claude-SearchBot", // Anthropic — search index
  "Claude-User", // Anthropic — live fetches for a user
  "PerplexityBot", // Perplexity — search index
  "Perplexity-User", // Perplexity — live fetches for a user
  "Google-Extended", // Google — Gemini app + AI grounding
  "Applebot", // Apple — Siri/Spotlight (also honours Applebot-Extended)
  "Amazonbot", // Amazon — Alexa answers
  "meta-externalagent", // Meta AI
];

export default function robots(): MetadataRoute.Robots {
  const access = { allow: "/", disallow: ["/cart", "/checkout", "/order/"] };
  return {
    rules: [
      { userAgent: "*", ...access },
      { userAgent: AI_CRAWLERS, ...access },
    ],
    sitemap: `${SITE.url}/sitemap.xml`,
    host: SITE.url,
  };
}
