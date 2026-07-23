# Bethany Hub — Storefront API contract (Neema lead + shipping)

**Status: ✅ LIVE (2026-07).** Both endpoints are built, tested and deployed on
`hub.bethanyhouse.co.ke`. Lead capture and shipping estimates now persist in the
Hub instead of falling back to WhatsApp; the storefront needed no change. This
doc stays as the reference contract between the two repos. The optional auth
gate (§6) is wired on the Hub but dormant — see §6 to lock it down.

- **Consumer:** the storefront's Neema gateway, **server-side only** — `storefront/lib/hub.ts` (`createLead()`, `estimateShipping()`), invoked from `storefront/app/api/neema/route.ts` and `storefront/app/api/neema/lead/route.ts`. The browser never calls these.
- **Base URL:** `https://hub.bethanyhouse.co.ke/api/v1` (the storefront's `NEXT_PUBLIC_HUB_API`).
- **Fallback (on any Hub error, or before `NEXT_PUBLIC_HUB_API` is set):** both client functions return `null` and Neema falls back to a WhatsApp handoff with the enquiry pre-filled — so **nothing is lost**. Now that the endpoints are live, this is the *error* path, not the default.

These mirror the existing guest-checkout bridge (`POST /storefront/orders`, `bethany-house` PR #141) — same base path, same JSON style, same idempotency approach.

---

## Conventions

| Concern | Rule |
|---|---|
| Content type | Requests and responses are `application/json`. |
| Idempotency | Writes carry `client_request_id` (any string — not only UUIDs). Dedupe on it — a retry with the same id returns the **same** lead, not a duplicate (as the orders bridge does). |
| Currency | Follow the orders rule (`Order::resolveCurrency`): country `KE` → **KES**, everything else → **USD**. Applies to any `cost` string in the shipping response. |
| Auth | See §6. Recommended: a shared secret header on the write endpoint. The storefront sends these calls server-side, so a secret is safe. |
| Errors | The storefront treats **any non-2xx as "not available"** and falls back to WhatsApp — it never surfaces a Hub error to the customer. Still, return correct status codes (below) for observability. |
| Success | Return `200` (or `201` for a created lead) with the documented body. The storefront validates the body shape (see each endpoint) before using it. |

---

## 1. `POST /storefront/leads`

Persist a qualified lead captured by Neema (a quote request, a bulk/parish enquiry, a shipping enquiry, or a "have someone follow up").

### Request body

Exactly what `createLead()` sends (`storefront/lib/hub.ts`):

```jsonc
{
  "client_request_id": "3f1e…-uuid",     // required — idempotency key
  "intent": "quote",                      // required — see values below
  "readiness": "high",                    // optional — "low" | "medium" | "high"
  "customer": {
    "name": "Rev. Mwangi",                // optional
    "phone": "+254727891989",             // required — WhatsApp/phone, the key contact
    "email": "office@parish.org",         // optional
    "church": "St. Peter's Parish"        // optional
  },
  "location": {
    "country_code": "UG",                 // optional — ISO-2 (drives currency/zone)
    "city": "Kampala"                     // optional
  },
  "products": ["clergy-cassock", "chalice-royale"],  // optional — storefront product slugs
  "quantity": "20",                       // optional — free text ("12", "a dozen")
  "message": "Purple, for Advent ordination",        // optional — customer's note/summary
  "source_path": "/product/clergy-cassock"           // optional — page the enquiry came from
}
```

**Field notes / validation**

| Field | Type | Required | Notes |
|---|---|---|---|
| `client_request_id` | string (UUID) | ✅ | Unique per submission; **dedupe on it**. |
| `intent` | string | ✅ | One of `quote`, `shipping`, `product_inquiry`, `measurement`, `order_support`, `other`. Store as-is; treat unknown values as `other`. |
| `readiness` | enum | — | `low` \| `medium` \| `high`. Default `medium` if absent. |
| `customer.phone` | string | ✅ | The one field the storefront guarantees. Do not require email. |
| `customer.name/email/church` | string | — | Any may be absent. |
| `location.country_code` | ISO-2 | — | May be absent (customer didn't say). |
| `products` | string[] | — | Storefront slugs (e.g. `clergy-cassock`, `chalice-royale`, or a variant slug `parent--v12`). Map to Hub products by slug where possible; keep the raw slugs regardless. |
| `quantity` / `message` | string | — | Free text — never parse as a number blindly. |
| `source_path` | string | — | Attribution. |

### Response (success)

The storefront reads the new id as **`lead.id`** (falling back to a top-level `id`). Return either:

```jsonc
{ "lead": { "id": 4821 } }
```
```jsonc
{ "id": 4821 }        // also accepted
```

`id` may be a number or string. Anything else (or a missing id) makes the storefront treat the call as failed → WhatsApp fallback.

### Suggested Hub-side handling

- **Table** `leads`: `id`, `client_request_id` (unique index), `intent`, `readiness`, `name`, `phone`, `email`, `church`, `country_code`, `city`, `products` (json), `quantity`, `message`, `source_path`, `status`, `assigned_to`, `created_at`.
- **Status lifecycle** (for the Hub dashboard / speed-to-lead): `new → assigned → quoted → won | lost`.
- **Routing:** high-readiness or `intent=quote` leads should notify sales (the advisory's "speed-to-lead"). Consider the same WhatsApp/staff notification the orders flow uses.
- **Idempotency:** on duplicate `client_request_id`, return the existing lead with `200`.

### Example

```bash
curl -sX POST https://hub.bethanyhouse.co.ke/api/v1/storefront/leads \
  -H 'Content-Type: application/json' \
  -d '{"client_request_id":"11111111-1111-1111-1111-111111111111","intent":"quote",
       "readiness":"high","customer":{"name":"Rev. Mwangi","phone":"+254727891989"},
       "location":{"country_code":"KE","city":"Nakuru"},"products":["clergy-cassock"],
       "quantity":"20","message":"Purple, Advent ordination","source_path":"/shop"}'
# → {"lead":{"id":4821}}
```

---

## 2. `GET /storefront/shipping/estimate`

Return shipping options to a destination. Called when a customer asks about shipping and provides a country.

### Query parameters

Exactly what `estimateShipping()` sends:

| Param | Example | Required | Notes |
|---|---|---|---|
| `country_code` | `UG` | one of code/country | ISO-2. Prefer this. |
| `country` | `Uganda` | one of code/country | Free-text country name (sent when the code isn't known). Resolve to a zone. |
| `city` | `Kampala` | — | For finer estimates where zones are city-level. |
| `items` | `clergy-cassock,chalice-royale` | — | Comma-separated storefront slugs (for weight/volume). Absent ⇒ estimate for a typical parcel. |

`GET /storefront/shipping/estimate?country_code=UG&city=Kampala&items=clergy-cassock,chalice-royale`

### Response (success)

The storefront requires **`options` to be an array** (`ShippingEstimate` in `lib/hub.ts`); `destination` is shown to the customer.

```jsonc
{
  "destination": "Kampala, Uganda",       // required — string shown to the customer
  "options": [                            // required — array (may be empty)
    { "service": "Express (DHL)",  "range": "3–5 days",  "cost": "USD 48" },
    { "service": "Standard",       "range": "7–12 days", "cost": "USD 22" }
  ],
  "note": "Duties/taxes on arrival are the customer's responsibility."  // optional
}
```

| Field | Type | Required | Notes |
|---|---|---|---|
| `destination` | string | ✅ | Human-readable, e.g. `"Kampala, Uganda"` or `"Uganda"`. |
| `options[].service` | string | ✅ | Carrier/service label. |
| `options[].range` | string | ✅ | Transit time, free text. |
| `options[].cost` | string | — | Price **with currency** per the currency rule (KES for `KE`, else USD). Omit if genuinely unknown. |
| `note` | string | — | Caveats (customs, remote-area surcharge, etc.). |

Return `options: []` with a `note` if the destination is served only by manual quote — the storefront will still show the destination and route to staff.

### Examples

Kenya (KES):
```jsonc
{ "destination": "Nairobi, Kenya",
  "options": [ { "service": "Nairobi CBD",  "range": "same day", "cost": "Free over KES 10,000" },
               { "service": "Countrywide",  "range": "2–4 days", "cost": "KES 300" } ] }
```

International (USD):
```jsonc
{ "destination": "London, United Kingdom",
  "options": [ { "service": "Express (DHL)", "range": "3–5 days", "cost": "USD 55" } ],
  "note": "Import duties on arrival are the customer's responsibility." }
```

### Suggested Hub-side handling

- Resolve `country_code`/`country` → a shipping **zone**; pick 1–3 services for that zone.
- Use `items` (slugs → products) for billable weight/volume if your rates are weight-based; otherwise a flat per-zone estimate is fine for v1.
- This is an **estimate** — mark it as such in `note`; staff confirm the final rate.

---

## 3. Where the storefront consumes these

| Endpoint | Storefront function | Invoked from |
|---|---|---|
| `POST /storefront/leads` | `createLead()` — `storefront/lib/hub.ts` | `app/api/neema/lead/route.ts` (in-chat capture form) and the `create_lead` model tool in `app/api/neema/route.ts` |
| `GET /storefront/shipping/estimate` | `estimateShipping()` — `storefront/lib/hub.ts` | `app/api/neema/lead/route.ts` (shipping capture) and the `estimate_shipping` model tool |

When these return data, Neema shows the lead reference / the estimate inline; when they return non-2xx, Neema falls back to a pre-filled WhatsApp handoff. **Both behaviours are already built and tested** — the endpoints are the only missing piece.

---

## 4. Acceptance checklist — ✅ verified live (2026-07)

- [x] `POST /storefront/leads` persists a lead and returns `{ "lead": { "id": … } }` (201, or 200 on replay).
- [x] Re-POST with the same `client_request_id` returns the **same** lead (no duplicate) — any string, not just UUIDs.
- [x] `customer.phone` is the only required customer field; missing `name`/`email` is accepted.
- [x] Unknown `intent` is stored as `other`, not rejected.
- [x] `GET /storefront/shipping/estimate?country_code=UG` returns `{ destination, options: [...] }` with `options` an **array** (`[]` + `note` for unknown/unrated/disabled).
- [x] A Kenyan destination returns KES costs; a non-Kenyan destination returns USD costs; international carries a customs/duties note.
- [ ] Both endpoints respond within a sensible timeout (the storefront calls them inline during a chat turn — aim < 2s). *(latency not reported; confirm under load)*

---

## 5. Security & least privilege

- These are the **only** two endpoints Neema can trigger writes/lookups through — keep the surface this small.
- `POST /storefront/leads` is a write: protect it (see §6). `GET …/shipping/estimate` is a read and may be public, but the same protection is fine.
- Do not accept or echo any field not listed here. Ignore unknown JSON keys.
- Rate-limit per source as you see fit; the storefront also rate-limits per session before it ever calls you.

## 6. Auth (one small storefront change when you're ready)

Today the storefront calls both endpoints **without** an auth header, and the Hub's `X-Storefront-Key` gate is **wired but dormant** (both sides open). When you're ready to lock it down, use a shared secret the storefront sends server-side:

```
X-Storefront-Key: <secret>
```

To activate: (1) set the secret in a Hub env and enable the gate; (2) the storefront reads the same value from a new **server-only** env, `HUB_STOREFRONT_KEY`, and sends the header in `createLead()` / `estimateShipping()` — a one-line change per call, already staged for when the value is agreed. Pick the value, set it on the Hub, and tell the storefront side; both flip together. A header (not a query token) is the right choice here — this is a server-to-server call, so the secret never appears in a browser or in query-string logs. Until then, both endpoints work unauthenticated.

---

## 7. Interest ledger — the cross-channel cart *(NEW — needs Hub build)*

The storefront now mirrors every cart to the Hub as an **expression of interest**, keyed to a short cross-channel token (`BH-XXXX`). The row is **durable and never deleted**: it traces a customer's interest over time and lets Neema resume the *same cart* when a customer moves between the website, WhatsApp, Messenger and Instagram. When the cart resolves, its outcome is attributed to the channel where it closed.

- **Consumer:** `storefront/lib/hub.ts` (`upsertInterestCart()`, `markInterestOutcome()`), invoked server-side from `storefront/app/api/cart/route.ts`. The browser POSTs its cart to `/api/cart`; the storefront forwards here (so the write credential stays server-side). Same base path, JSON style, idempotency and `X-Storefront-Key` auth as §1–§6.
- **Status is authoritative on the Hub side too:** neema-ai (WhatsApp/Messenger/IG) reads and transitions the **same rows** — see `docs/NEEMA_WEB_CHAT_CONTRACT.md`.
- **Graceful:** until these endpoints exist, the storefront's calls just no-op (non-2xx → ignored); the cart keeps working. So this can ship on the storefront side ahead of the Hub build.

### Table `interest_carts`

| Column | Notes |
|---|---|
| `id` | pk |
| `token` | **unique index** — the cross-channel handle; POST **upserts on this** |
| `channel` | origin: `web` \| `whatsapp` \| `messenger` \| `instagram` \| `facebook` |
| `last_channel` | where it was last touched (update on each write) |
| `status` | `active_cart` → `checkout_started` → `online_order` \| `whatsapp_order` \| `abandoned` |
| `visitor_id` | durable first-party anchor (`bh_vid` cookie) — **groups a visitor's carts before a phone exists**; set server-side + httpOnly, doesn't rotate with the cart token (nullable) |
| `session_id` | the Neema chat session (`bh-neema-sid`) — links this cart to the customer's chat (nullable) |
| `phone`, `name`, `church` | customer identity, filled in as it becomes known (nullable) |
| `messenger_psid`, `instagram_psid` | for identity-linking from Meta channels (nullable) |
| `items` | json: `[{ slug, quantity, measurements?, size? }]` |
| `subtotal`, `currency` | snapshot for the ledger (nullable) |
| `order_ref` | links to the placed order once it converts (nullable) |
| `source_path` | attribution (nullable) |
| `created_at`, `updated_at`, `converted_at` | timestamps |

> **Identity ladder (anonymous → known).** Four handles on the row, weakest to strongest:
> `token` identifies **one cart** (rotates per order) · `visitor_id` (durable `bh_vid` cookie) groups **a visitor's carts** before any phone · `session_id` links the cart to their **chat** · `phone` is the **cross-channel key** (WhatsApp gives it free; web/Messenger capture it at checkout/quote) — matching carts to a known phone unifies interest across channels. A `customers` table (phone ↔ psids ↔ visitor_ids ↔ sessions) is the clean long-term home for these links; for now, keeping all four on the cart row already lets you group and unify.

### 7a. `POST /storefront/interest-carts` — upsert the live cart

Exactly what `upsertInterestCart()` sends:

```jsonc
{
  "client_request_id": "…uuid",          // retry idempotency
  "token": "BH-7QK2ZP9A",                // required — UPSERT KEY (per-cart, rotates per order)
  "channel": "web",                       // required
  "visitor_id": "v-…",                    // optional — durable per-visitor anchor (bh_vid cookie)
  "status": "active_cart",                // "active_cart" | "checkout_started"
  "session_id": "web-abc123",             // optional — the chat session (bh-neema-sid)
  "customer": { "name": "…", "phone": "+254…", "church": "…" },  // optional; any subset
  "items": [ { "slug": "holy-communion-bread-500pcs", "quantity": 2, "size": "500" } ],
  "subtotal": 1600, "currency": "KES",    // optional snapshot
  "source_path": "/product/…"             // optional
}
```

- **Upsert on `token`** (last write wins) — a customer editing their cart sends repeated POSTs; keep one row per token.
- Never drop a status backwards (don't let `active_cart` overwrite a converted `online_order`).
- Response: `{ "interest_cart": { "token": "…", "status": "…" } }` (200/201). The storefront only checks for 2xx.

### 7b. `GET /storefront/interest-carts?token=…`  /  `?phone=…` — load & resume

Used by neema-ai to resume a cart (by token from the WhatsApp handoff, or by matching the customer's phone) and to read interest history.

```jsonc
// ?token=BH-7QK2ZP9A  → the one cart
{ "interest_cart": { "token": "…", "status": "active_cart", "channel": "web",
    "items": [ { "slug": "…", "quantity": 2, "size": "500" } ],
    "subtotal": 1600, "currency": "KES", "phone": null, "updated_at": "…" } }

// ?phone=+254727891989  → this customer's carts, most-recent first (interest history)
{ "interest_carts": [ { "token": "…", "status": "abandoned", "items": [...], "updated_at": "…" }, … ] }
```

### 7c. `PATCH /storefront/interest-carts/{token}` — transition the outcome

Exactly what `markInterestOutcome()` sends:

```jsonc
{ "status": "online_order",               // online_order | whatsapp_order | abandoned
  "order_ref": "ORD-9F2K",                // optional — the placed order's number/ref
  "customer": { "name": "…", "phone": "+254…", "church": "…" } }  // optional — attach when known
```

- The **storefront** PATCHes `online_order` (with `order_ref`) at web checkout.
- **neema-ai** PATCHes `whatsapp_order` when a cart closes on WhatsApp.
- `abandoned` may be set by a Hub cron after N days of no activity — but the row is **kept** (that's the interest signal).

### Acceptance

- [ ] POST upserts on `token` (repeat POSTs update one row, don't duplicate).
- [ ] GET `?token=` returns the cart; `?phone=` returns the customer's carts newest-first.
- [ ] PATCH transitions status and records `order_ref`; won't regress a converted cart.
- [ ] Row is never hard-deleted; `abandoned` is a status, not a delete.
- [ ] Same `X-Storefront-Key` gate as §6.

---

*Contract v1. Matches `storefront/lib/hub.ts` as of this branch. Questions on the storefront side → see `docs/AI_INTEGRATION_ADVISORY.md` §4.*
