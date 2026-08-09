# CSRF protection — operator flip runbook

**Status: the codebase is CSRF-READY but protection is OFF until you flip the
host-mounted config.** Deploying the readiness code changes nothing at runtime:
the token-injection JS (`application/views/shared/csrf_boot.php`) is wrapped in
`if ($CI->config->item('csrf_protection'))` and emits zero bytes while
protection is off.

Production reads `application/config/config.php` from the **host**, not the
repo/image:

```
/opt/bethanywebsite/config/config.php  ->  /var/www/html/application/config/config.php (ro)
```

so enabling CSRF is an **operator step on the VPS**, done after the readiness
image is live.

---

## 1. Prerequisite — the readiness image must be deployed first

Flip order matters. The config flip only works against an image that contains
the injection JS. If you flip the config while an older image is running,
**every POST in the app 403s and staff are locked out of POS/admin.** Verify
first:

```bash
docker exec bethanywebsite ls /var/www/html/application/views/shared/csrf_boot.php
```

If that file is missing, do NOT flip.

## 2. The flip — exact lines for /opt/bethanywebsite/config/config.php

Edit `/opt/bethanywebsite/config/config.php` on the VPS. Find the existing
CSRF block (around line 453, currently `csrf_protection = FALSE`) and replace
it with:

```php
$config['csrf_protection'] = TRUE;
$config['csrf_token_name'] = 'bh_csrf_token';
$config['csrf_cookie_name'] = 'bh_csrf_cookie';
$config['csrf_expire'] = 28800;
$config['csrf_regenerate'] = FALSE;
$config['csrf_exclude_uris'] = array(
    'api/.*',   // M-Pesa C2B registerurl/validation/confirmation, stk_cb, pesapal_callback
    'cron/.*',  // scheduled jobs hit via HTTP
);
```

Also confirm (both already the current values, but they are load-bearing now):

```php
$config['cookie_httponly'] = FALSE;   // the JS must read the CSRF cookie
$config['global_xss_filtering'] = TRUE;
```

Then restart the container so the config re-reads cleanly:

```bash
cd /opt/bethanywebsite && docker compose restart web
```

(Config is read per-request, so a restart is not strictly required, but it
gives a clean cut-over point for the smoke test.)

### Why these values

- **`csrf_regenerate = FALSE`** — token is stable per session. With TRUE every
  POST rotates the token, and any second admin tab, queued AJAX call, or
  report/print window holding the old token 403s — a staff-lockout footgun.
  Tradeoff: a leaked token stays valid for the cookie lifetime instead of one
  request. Accepted.
- **`csrf_expire = 28800` (8 h)** — covers a full POS shift. A tab idle longer
  than this 403s on its next POST; reloading the page fixes it.
- **`csrf_exclude_uris`** — external servers (Safaricom, Pesapal, cron) can
  never send our token. Everything under `api/` and `cron/` skips the check.
  These endpoints have their own authentication concerns independent of CSRF.

## 3. Smoke checklist (run immediately after the flip)

All of these are ordinary browser actions — do them in a normal window, and
keep an SSH session open with the rollback ready.

| # | Check | Expected |
|---|-------|----------|
| 1 | `/be` — log OUT and log back IN | login succeeds |
| 2 | `/pos` — log out, log in, select outlet | login + outlet select succeed |
| 3 | One admin save (e.g. Products → edit a product → Save, or Brands → add/delete a test brand) | normal success JSON, no 403 |
| 4 | One POS sale end-to-end (add item → tender → print) | sale completes |
| 5 | One report/print POST (e.g. BE → Reports → Sales → print view) | report renders (this exercises the `$.redirect` POST path) |
| 6 | Affiliate login (`/affiliates`) + storefront customer login | both succeed |
| 7 | Storefront checkout POST (add to cart → checkout → submit address) | proceeds normally |
| 8 | Blog/product comment or review submit, contact-us form | accepted |
| 9 | Payment callback replay: `curl -s -o /dev/null -w '%{http_code}' -X POST https://bethanyhouse.co.ke/api/confirmation -d '{}'` | **NOT 403** (the CSRF layer must not block it — expect whatever the endpoint normally returns for a junk payload) |
| 10 | Negative control: `curl -s -X POST https://bethanyhouse.co.ke/home/ajax_search -d 'q=x'` | **403** "The action you have requested is not allowed" (proves protection is actually on) |
| 11 | `docker logs bethanywebsite --since 10m` and MPesa/Pesapal settings pages | no CSRF error spam |

Then watch logs for the first real M-Pesa/Pesapal payment of the day.

## 4. Instant rollback

Edit `/opt/bethanywebsite/config/config.php`:

```php
$config['csrf_protection'] = FALSE;
```

Effective on the next request (restart optional). The injection JS goes dormant
again automatically — no rebuild, no redeploy, no client-side cleanup.

---

## How the readiness code works (reference)

One shared partial, `application/views/shared/csrf_boot.php`, is included at
the end of every page-serving layout:

- `be/includes/footer.php`, `pos/includes/footer.php`, `fe/includes/footer.php`
  (all templated pages), plus the standalone full-HTML pages that accept POSTs:
  `be/login.php`, `be/recover.php`, `be/register.php`, `pos/login.php`,
  `pos/reset_password.php`, `pos/outlet_select.php`, `fe/landing.php`.

It covers the three POST styles used in this codebase:

1. **Forms** — a hidden token input is injected into every `method="post"`
   form at DOM-ready and again (capture phase) on every `submit` event, so
   handlers that build `new FormData(form)` / `$(form).serialize()` include
   the token automatically.
2. **jQuery AJAX** — a `$.ajaxPrefilter` appends the token to every
   same-origin POST: `FormData` bodies (`append`), url-encoded string bodies,
   plain-object bodies, and empty bodies (e.g. POS `select_outlet` posts
   `data: ''`).
3. **`$.redirect` POST navigations** (report/print pages, site search) —
   `$.redirect.getForm` is wrapped to add the token, because the plugin calls
   the native `form.submit()` which fires no `submit` event.

The freshest hash is always read from the CSRF cookie (JS-readable —
`cookie_httponly FALSE`), falling back to the hash rendered at page load, so
token expiry mid-session self-heals on the next server response.

Cross-origin POSTs are never touched (the token is not leaked off-origin).
