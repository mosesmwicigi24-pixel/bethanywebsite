# AI SEO & portable services

This app now ships a **framework-agnostic service layer** under [`services/`](../services)
(PHP namespace `Bethany\Services\*`). It has **zero CodeIgniter dependency** by design, so
it survives a future CodeIgniter 4 / Laravel migration untouched — you rewrite the thin
CI3 adapters, not the business logic.

```
services/src/
  Security/PasswordHasher.php   bcrypt hashing + transparent md5 migration
  Ai/AiClient.php               single-turn text-completion port (interface)
  Ai/AnthropicClient.php        Claude Messages API implementation
  Ai/HttpTransport.php          tiny HTTP port (GuzzleTransport in prod, fake in tests)
  Seo/SeoMetaService.php        product -> {title, description, keywords} via AiClient
  Seo/StructuredData.php        schema.org JSON-LD builder (no AI, no network)
services/tests/                 PHPUnit suite, runs offline with fakes (no API key needed)
```

CI3 adapters:

- [`application/libraries/Ai_seo.php`](../application/libraries/Ai_seo.php) — wires the
  services with config; reads the API key from the environment.
- [`application/controllers/be/Ai_seo.php`](../application/controllers/be/Ai_seo.php) —
  admin endpoint `POST be/ai_seo/generate` (RBAC: `seo_edit`).

## Configuration

The Anthropic API key is provided by the environment (12-factor), never committed:

1. On the VPS create `/opt/bethanywebsite/.env`:
   ```
   ANTHROPIC_API_KEY=sk-ant-...
   ```
   `docker compose` reads it automatically and passes it to the container
   (see `docker-compose.yml` → `environment`) and Apache exposes it to PHP (`PassEnv`).
2. Redeploy. With no key set, the AI features simply stay disabled — the rest of the
   site is unaffected.

Model default: `claude-haiku-4-5` (cost-efficient; good for SEO copy and bulk jobs).

## Using the AI meta generator

`POST be/ai_seo/generate` with `name`, `description`, `category`, `brand`, `price`
returns:

```json
{ "status": "SUCCESS",
  "meta": { "title": "...", "description": "...", "keywords": ["...", "..."] } }
```

Wire a "✨ Generate with AI" button on the product add/edit form to POST the current
field values here and fill in the meta fields. To persist per-product meta, run
[`db/migrations/2026-07-07_add_product_seo_meta.sql`](../db/migrations/2026-07-07_add_product_seo_meta.sql)
and add the three fields to the product form.

## Structured data (JSON-LD) — zero-cost SEO win

`$this->load->library('ai_seo'); $sd = $this->ai_seo->structured_data();`
then in the product view `<head>`:

```php
echo $sd->script($sd->product([
    'name' => $product->product_name,
    'description' => $product->description,
    'image' => $product->image_url,
    'price' => $product->price,
    'availability' => $in_stock ? 'in_stock' : 'out',
    'url' => site_url('product/'.$product->slug),
    'brand' => $product->brand_name,
]));
```

This emits schema.org `Product` markup that powers Google rich snippets / Shopping —
no API call, no cost. `breadcrumbs()` and `organization()` are available too.

## Tests

```bash
composer install
vendor/bin/phpunit
```

The suite uses in-memory fakes (`FakeAiClient`, `FakeTransport`) so it runs with no
network and no API key. It also runs in CI on every PR (`.github/workflows/ci.yml`).
