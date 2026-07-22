# Neema web-chat contract (`/api/web/chat`)

How the **website** talks to the shared **neema-ai** brain, and what the brain
should send back so the site can render product cards, add-to-cart buttons and
one-tap replies — i.e. close the sale on the page.

> **Web channel only.** These extra fields change *nothing* for WhatsApp,
> Messenger or Instagram. Those channels can't render cards, so only attach the
> structured fields when the request arrives on this endpoint (it always carries
> the `X-Storefront-Key` header). Same brain, same memory, same tools — richer
> rendering on the web.

---

## 1. Request the website sends

```
POST /api/web/chat
Content-Type: application/json
X-Storefront-Key: <shared secret>

{ "session_id": "web-abc123", "message": "do you have brass chalices?" }
```

- `session_id` — stable per visitor; thread the conversation off this.
- `message` — the latest customer message only (you keep the history).

## 2. Response the website expects

Only `reply` is required. Everything else is optional and additive — a plain
text reply keeps working exactly as today.

```jsonc
{
  "reply": "Yes — our Brass Communion Chalice is a favourite, KES 4,500. Want me to add one to your cart?",
  "handled_by": "ai",              // "human" flips the widget to "a team member is replying"
  "session_id": "web-abc123",

  // ── the extras that turn a text answer into a checkout ──
  "products": [
    { "slug": "brass-communion-chalice", "reason": "Most popular, ships today" }
  ],
  "actions": [
    { "type": "add_to_cart",  "label": "Add to cart — KES 4,500", "value": "brass-communion-chalice" },
    { "type": "view_product", "label": "See details",             "value": "brass-communion-chalice" }
  ],
  "quick_replies": [
    { "id": "gold",  "label": "Show the gold one" },
    { "id": "bulk",  "label": "Price for 12" }
  ]
}
```

### Field rules (the site validates + trims these)

| Field | Shape | Limits |
|---|---|---|
| `products` | `[{ slug, reason? }]` | max **6**; `slug` **must** match a real catalog slug (see §3) or the card is dropped |
| `actions` | `[{ type, label, value? }]` | max **4**; `label` ≤ 40 chars |
| `quick_replies` | `[{ id, label }]` | max **4**; `label` ≤ 60 chars |

**Action `type` values:**

| type | `value` | what it does on the site |
|---|---|---|
| `add_to_cart` | product `slug` | **the closer.** Ready-made item → drops into the cart and opens the drawer. Made-to-order item → opens the product page so measurements are taken first. |
| `view_product` | product `slug` | opens `/product/<slug>` |
| `whatsapp` | message text *or* a `wa.me` URL | opens WhatsApp (site makes it a valid link) |
| `request_quote` | message text | same as whatsapp, for bulk/quote intent |
| `find_orders` | — | opens the order-tracking page |
| `shop` | — | opens the shop |

> The site always adds its own "Chat on WhatsApp" button unless you already sent
> a `whatsapp` / `request_quote` action — so no need to add one just in case.

## 3. Slugs must match the catalog

A product card only renders when its `slug` matches a product the storefront
knows. Use the **same slug that appears in the product URL** —
`bethanyhouse.co.ke/product/<slug>` — which is the Hub product slug. If your
product tool already returns Hub slugs, use them verbatim. If you're unsure of a
slug, prefer `view_product`/text over guessing — a wrong slug just falls back to
text, never a broken card.

## 4. Personality on the web: the natural closer

Same warm, human Neema — tuned to **move to the sale**, not to explain.

- **Open warm and human — never with a link.** Greet, say you have great items,
  and show a few. Example for a broad ask like "communion elements": _"Welcome to
  Bethany House! We have wonderful communion items — let me share a few here. And
  if you have a specific one in mind, just tell me and I'll get it sorted for you."_
- **Never paste a catalog URL.** The website renders products as tappable cards
  with Add to cart built in, so a raw link only pulls the customer off the page —
  away from the close. Show the cards instead. (The storefront now strips off-site
  links as a safety net, but don't rely on it — just don't send them.)
- **Return only products that match the ask.** For "communion elements", show
  communion items (cups, wafers, wine, trays, sets) — never a gown or a dress.
  Relevance is what closes; an off-topic card breaks trust.
- **Lead with the answer, then the next step.** "Yes, KES 4,500 — add one to your cart?" beats a paragraph about brass.
- **Always offer the close.** When you name a product, send its card + an
  `add_to_cart` action. Don't wait to be asked.
- **Remove friction with `quick_replies`** — size, colour, quantity, "the gold
  one" — so the customer taps instead of types.
- **Ground every price in the catalog.** Never invent a price or a slug.
- **One clear ask at a time.** Card → add to cart → checkout. Keep momentum.
- **Bulk / clergy orders** → `request_quote` (or capture a number) rather than a
  raw cart.
- **Keep it short.** Less story, more "shall I add it?"

### Example reply shape (item with sizes)

When an item comes in sizes, affirm, lay the options out cleanly with prices,
ask for size **and** quantity (that's the close), then point to the cards — with
**no link**, they render right below:

> Yes, we do! Communion Bread comes in two sizes:
> - 500 pieces — $10
> - 1,000 pieces — $14
>
> Which size would you like, and how many packs shall I set aside for you?
>
> See our catalog here below:

Then attach the matching `products` so the cards (each with **Add to cart**)
appear under that line. Never end with a catalog URL — "here below" means the
cards, not an off-site page.

The website then carries it the rest of the way: **card → add to cart →
checkout → Hub receipt → track order by phone** — exactly the flow customers had
before, now driven by the one Neema brain.

---

## 5. Cross-channel carts & the interest ledger

The website now mirrors every cart to the Hub as an **interest record**, keyed to
a short token `BH-XXXX` (see `docs/HUB_CONTRACT.md` §7 — the `interest_carts`
table + 3 endpoints). This lets Neema **carry a cart between channels** and get
wiser about each customer over time. Neema's part, on **WhatsApp / Messenger /
Instagram**:

### Resume a cart the customer started on the web
When a web visitor taps "Continue on WhatsApp", the deep-link message carries the
token, e.g.:
> "Hello Bethany House! I want to buy Holy Communion Bread (500 pcs). **(cart BH-7QK2ZP9A)** Can you pick up from Neema and process my order?"

On that first WhatsApp message:
1. Parse a `BH-XXXX` token if present → `GET /storefront/interest-carts?token=BH-XXXX`.
2. If no token, match by the customer's **phone** (WhatsApp gives it) → `GET …?phone=+254…` and take the most recent open cart.
3. Load those items and **continue without re-asking** — *"Karibu! I've got your cart: 2 × Holy Communion Bread (500 pcs). Shall I confirm and take delivery details?"*
4. When it closes on WhatsApp → `PATCH /storefront/interest-carts/{token}` with `status: "whatsapp_order"` (and `order_ref` if you place the order). If it's a fresh WhatsApp cart with no token, create one via `POST …/interest-carts` with `channel: "whatsapp"`.

### Be wiser using interest history
Before answering a returning customer on any channel, you may `GET …?phone=+254…`
to see their past carts/orders and tailor the reply — *"Last week you were looking
at the purple cassock — still interested, alongside the communion bread?"* Keep it
natural; use it to relate, not to over-pitch.

### Rules
- **Phone is the cross-channel key.** Capture it early and warmly on Meta channels; it's what links WhatsApp ↔ Messenger ↔ web into one customer.
- **Don't drop the cart.** Loading and continuing an existing cart always beats starting over.
- **Attribute honestly:** a cart that closes on WhatsApp is `whatsapp_order`; one that closes on the web is `online_order` (the storefront sets that). The row is never deleted — an unfinished cart stays as `abandoned`, which is still useful interest.
- **Web channel is unchanged** by this: the storefront already mirrors the web cart and sets `online_order` at checkout. This section is only about what the *brain* does on the Meta channels.
