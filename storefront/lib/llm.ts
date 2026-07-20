/* ============================================================
   Neema LLM layer — a small multi-provider abstraction.

   Chat runs a fallback chain: Groq (primary, llama-3.3-70b-versatile)
   → Anthropic (Claude) → OpenAI → Gemini. Each provider is reached through
   its NATIVE API — Groq, OpenAI and Gemini are OpenAI-compatible
   (chat/completions); Anthropic uses its own Messages API (never an OpenAI
   shim). The gateway drives a provider-agnostic tool loop over
   `providerChat`; if a provider errors, the next one is tried, and if all
   fail the gateway falls back to its deterministic catalog-grounded
   orchestrator.

   Vision (the measurement copilot) skips Groq — llama-3.3-70b is text-only
   — and runs Gemini → OpenAI → Anthropic, all multimodal.

   Only providers whose key is set are used, so the effective chain is
   whatever you configure. All keys are server-only. Configure per provider:
     GROQ_API_KEY / GROQ_MODEL (default llama-3.3-70b-versatile) / GROQ_API_URL
     ANTHROPIC_API_KEY / ANTHROPIC_MODEL (default claude-opus-4-8) / ANTHROPIC_API_URL
     OPENAI_API_KEY / OPENAI_MODEL (default gpt-4o) / OPENAI_API_URL
     GEMINI_API_KEY / GEMINI_MODEL (default gemini-2.0-flash) / GEMINI_API_URL
   ============================================================ */

export interface LlmTool {
  name: string;
  description: string;
  parameters: Record<string, unknown>; // JSON schema for the arguments
}

export interface LlmToolCall {
  id: string;
  name: string;
  arguments: string; // JSON string
}

export interface LlmMessage {
  role: "system" | "user" | "assistant" | "tool";
  content: string | null;
  toolCalls?: LlmToolCall[]; // assistant turns that call tools
  toolCallId?: string; // tool-result turns
}

export interface LlmResult {
  content: string;
  toolCalls: LlmToolCall[];
}

export interface ProviderCfg {
  name: "groq" | "anthropic" | "openai" | "gemini";
  kind: "openai" | "anthropic";
  baseUrl: string;
  key: string;
  model: string;
}

/* ---------------- provider config from env ---------------- */

const GROQ = {
  key: process.env.GROQ_API_KEY,
  url: process.env.GROQ_API_URL || "https://api.groq.com/openai/v1",
  model: process.env.GROQ_MODEL || "llama-3.3-70b-versatile",
};
const ANTHROPIC = {
  key: process.env.ANTHROPIC_API_KEY,
  url: process.env.ANTHROPIC_API_URL || "https://api.anthropic.com/v1",
  model: process.env.ANTHROPIC_MODEL || "claude-opus-4-8",
};
const OPENAI = {
  key: process.env.OPENAI_API_KEY,
  url: process.env.OPENAI_API_URL || "https://api.openai.com/v1",
  model: process.env.OPENAI_MODEL || "gpt-4o",
};
const GEMINI = {
  key: process.env.GEMINI_API_KEY || process.env.GOOGLE_API_KEY,
  url: process.env.GEMINI_API_URL || "https://generativelanguage.googleapis.com/v1beta/openai",
  model: process.env.GEMINI_MODEL || "gemini-2.0-flash",
};

const groqCfg = (): ProviderCfg | null =>
  GROQ.key ? { name: "groq", kind: "openai", baseUrl: GROQ.url, key: GROQ.key, model: GROQ.model } : null;
const anthropicCfg = (model?: string): ProviderCfg | null =>
  ANTHROPIC.key ? { name: "anthropic", kind: "anthropic", baseUrl: ANTHROPIC.url, key: ANTHROPIC.key, model: model || ANTHROPIC.model } : null;
const openaiCfg = (model?: string): ProviderCfg | null =>
  OPENAI.key ? { name: "openai", kind: "openai", baseUrl: OPENAI.url, key: OPENAI.key, model: model || OPENAI.model } : null;
const geminiCfg = (model?: string): ProviderCfg | null =>
  GEMINI.key ? { name: "gemini", kind: "openai", baseUrl: GEMINI.url, key: GEMINI.key, model: model || GEMINI.model } : null;

/** Chat fallback chain: Groq → Anthropic → OpenAI → Gemini (only the configured ones). */
export function chatChain(): ProviderCfg[] {
  return [groqCfg(), anthropicCfg(), openaiCfg(), geminiCfg()].filter((c): c is ProviderCfg => c !== null);
}

/** Vision chain: Gemini → OpenAI → Anthropic (Groq/Llama-3.3 is text-only, so it's skipped). */
export function visionChain(): ProviderCfg[] {
  return [
    geminiCfg(process.env.GEMINI_VISION_MODEL),
    openaiCfg(process.env.OPENAI_VISION_MODEL),
    anthropicCfg(process.env.ANTHROPIC_VISION_MODEL),
  ].filter((c): c is ProviderCfg => c !== null);
}

/* ---------------- shared HTTP helpers ---------------- */

const safeParse = (s: string): unknown => {
  try { return JSON.parse(s || "{}"); } catch { return {}; }
};

interface OaResponse {
  choices?: { message?: { content?: string | null; tool_calls?: { id?: string; function?: { name?: string; arguments?: string } }[] } }[];
}
interface AnthropicBlock { type: string; text?: string; id?: string; name?: string; input?: unknown }
interface AnthropicResponse { content?: AnthropicBlock[] }

/* ---------------- OpenAI-compatible (Groq, Gemini) ---------------- */

function toOpenAiMsg(m: LlmMessage): Record<string, unknown> {
  if (m.role === "assistant" && m.toolCalls?.length) {
    return {
      role: "assistant",
      content: m.content ?? "",
      tool_calls: m.toolCalls.map((tc) => ({ id: tc.id, type: "function", function: { name: tc.name, arguments: tc.arguments } })),
    };
  }
  if (m.role === "tool") return { role: "tool", tool_call_id: m.toolCallId, content: m.content ?? "" };
  return { role: m.role, content: m.content ?? "" };
}

async function openaiChat(cfg: ProviderCfg, messages: LlmMessage[], tools: LlmTool[] | null, jsonMode: boolean): Promise<LlmResult> {
  const body: Record<string, unknown> = {
    model: cfg.model,
    temperature: 0.4,
    messages: messages.map(toOpenAiMsg),
  };
  if (tools?.length) {
    body.tools = tools.map((t) => ({ type: "function", function: { name: t.name, description: t.description, parameters: t.parameters } }));
    body.tool_choice = "auto";
  } else if (jsonMode) {
    body.response_format = { type: "json_object" };
  }
  const r = await fetch(`${cfg.baseUrl}/chat/completions`, {
    method: "POST",
    headers: { "Content-Type": "application/json", Authorization: `Bearer ${cfg.key}` },
    body: JSON.stringify(body),
  });
  if (!r.ok) throw new Error(`${cfg.name} ${r.status}`);
  const data = (await r.json()) as OaResponse;
  const msg = data.choices?.[0]?.message ?? {};
  const toolCalls: LlmToolCall[] = (msg.tool_calls ?? [])
    .filter((tc) => tc.function?.name)
    .map((tc) => ({ id: tc.id ?? "", name: String(tc.function?.name), arguments: tc.function?.arguments ?? "{}" }));
  return { content: msg.content ?? "", toolCalls };
}

/* ---------------- Anthropic native Messages API ---------------- */

function anthropicHeaders(key: string): Record<string, string> {
  return { "content-type": "application/json", "x-api-key": key, "anthropic-version": "2023-06-01" };
}

async function anthropicChat(cfg: ProviderCfg, messages: LlmMessage[], tools: LlmTool[] | null, jsonMode: boolean): Promise<LlmResult> {
  // Anthropic takes `system` at the top level; the rest map to user/assistant
  // turns, with tool results grouped into a single user message.
  const system = messages.filter((m) => m.role === "system").map((m) => m.content).filter(Boolean).join("\n\n");
  const amsgs: { role: "user" | "assistant"; content: unknown }[] = [];

  for (const m of messages) {
    if (m.role === "system") continue;
    if (m.role === "assistant") {
      const blocks: Record<string, unknown>[] = [];
      if (m.content) blocks.push({ type: "text", text: m.content });
      for (const tc of m.toolCalls ?? []) blocks.push({ type: "tool_use", id: tc.id, name: tc.name, input: safeParse(tc.arguments) });
      amsgs.push({ role: "assistant", content: blocks.length ? blocks : [{ type: "text", text: "" }] });
    } else if (m.role === "tool") {
      const block = { type: "tool_result", tool_use_id: m.toolCallId, content: m.content ?? "" };
      const last = amsgs[amsgs.length - 1];
      const lastBlocks = last && last.role === "user" && Array.isArray(last.content) ? (last.content as Record<string, unknown>[]) : null;
      if (lastBlocks && lastBlocks[0]?.type === "tool_result") lastBlocks.push(block);
      else amsgs.push({ role: "user", content: [block] });
    } else {
      amsgs.push({ role: "user", content: m.content ?? "" });
    }
  }

  const body: Record<string, unknown> = {
    model: cfg.model,
    max_tokens: 1024,
    system: jsonMode ? `${system}\n\nRespond with ONLY a valid JSON object — no prose, no code fences.` : system,
    messages: amsgs,
  };
  if (tools?.length) body.tools = tools.map((t) => ({ name: t.name, description: t.description, input_schema: t.parameters }));

  const r = await fetch(`${cfg.baseUrl}/messages`, { method: "POST", headers: anthropicHeaders(cfg.key), body: JSON.stringify(body) });
  if (!r.ok) throw new Error(`anthropic ${r.status}`);
  const data = (await r.json()) as AnthropicResponse;
  const blocks = Array.isArray(data.content) ? data.content : [];
  const content = blocks.filter((b) => b.type === "text").map((b) => b.text ?? "").join("");
  const toolCalls: LlmToolCall[] = blocks
    .filter((b) => b.type === "tool_use" && b.name)
    .map((b) => ({ id: b.id ?? "", name: String(b.name), arguments: JSON.stringify(b.input ?? {}) }));
  return { content, toolCalls };
}

/** One chat turn against a provider, normalized to LlmResult. */
export function providerChat(cfg: ProviderCfg, messages: LlmMessage[], tools: LlmTool[] | null, jsonMode: boolean): Promise<LlmResult> {
  return cfg.kind === "anthropic" ? anthropicChat(cfg, messages, tools, jsonMode) : openaiChat(cfg, messages, tools, jsonMode);
}

/* ---------------- vision (single call, returns text) ---------------- */

function parseDataUrl(url: string): { mediaType: string; data: string } {
  const m = url.match(/^data:([^;]+);base64,([\s\S]*)$/);
  return m ? { mediaType: m[1], data: m[2] } : { mediaType: "image/jpeg", data: "" };
}

/** Send one image + instruction to a multimodal provider; returns the raw text
    reply (expected to be JSON). Throws on transport/HTTP error so the caller
    can try the next provider in the vision chain. */
export async function providerVision(cfg: ProviderCfg, system: string, instruction: string, imageDataUrl: string): Promise<string> {
  if (cfg.kind === "anthropic") {
    const { mediaType, data } = parseDataUrl(imageDataUrl);
    const body = {
      model: cfg.model,
      max_tokens: 1024,
      system,
      messages: [
        {
          role: "user",
          content: [
            { type: "text", text: instruction },
            { type: "image", source: { type: "base64", media_type: mediaType, data } },
          ],
        },
      ],
    };
    const r = await fetch(`${cfg.baseUrl}/messages`, { method: "POST", headers: anthropicHeaders(cfg.key), body: JSON.stringify(body) });
    if (!r.ok) throw new Error(`anthropic-vision ${r.status}`);
    const data2 = (await r.json()) as AnthropicResponse;
    return (Array.isArray(data2.content) ? data2.content : []).filter((b) => b.type === "text").map((b) => b.text ?? "").join("");
  }

  // OpenAI-compatible (Gemini): image_url content part
  const body = {
    model: cfg.model,
    temperature: 0.2,
    response_format: { type: "json_object" },
    messages: [
      { role: "system", content: system },
      { role: "user", content: [{ type: "text", text: instruction }, { type: "image_url", image_url: { url: imageDataUrl } }] },
    ],
  };
  const r = await fetch(`${cfg.baseUrl}/chat/completions`, {
    method: "POST",
    headers: { "Content-Type": "application/json", Authorization: `Bearer ${cfg.key}` },
    body: JSON.stringify(body),
  });
  if (!r.ok) throw new Error(`${cfg.name}-vision ${r.status}`);
  const data = (await r.json()) as OaResponse;
  return data.choices?.[0]?.message?.content ?? "";
}
