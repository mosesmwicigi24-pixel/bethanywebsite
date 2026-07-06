# bethanywebsite

Source for the **Bethany House storefront** — the CodeIgniter 3 e-commerce site live at
<https://bethanyhouse.co.ke> (the #1 supplier of Holy Communion elements, clergy apparel
and Christian gifts). Separate from the `bethany-house` app that powers `hub.bethanyhouse.co.ke`.

## Stack

- **CodeIgniter 3** (PHP 8.1), MySQL (`mysqli`).
- Served in production as a Docker image (`php:8.1-apache`) behind the VPS nginx, which
  terminates TLS and proxies `https://bethanyhouse.co.ke` → the container on `127.0.0.1:8090`.

## Edit → GitHub → production

1. Branch, edit, open a PR against `main`.
2. Merge to `main`. [`.github/workflows/deploy.yml`](.github/workflows/deploy.yml) then:
   builds the image → pushes to `ghcr.io/mosesmwicigi24-pixel/bethanywebsite` → SSHes to the
   VPS and runs `docker compose pull && up -d` in `/opt/bethanywebsite`.

## Secrets & state (never in git)

Provided on the host, mounted into the container by [`docker-compose.yml`](docker-compose.yml):

| Path on VPS | Mounted to | What |
|---|---|---|
| `/opt/bethanywebsite/config/database.php` | `application/config/database.php` | DB credentials |
| `/opt/bethanywebsite/config/config.php`   | `application/config/config.php`   | `encryption_key`, base_url |
| `/var/www/bethanyhouse/uploads`           | `uploads/`                        | user-uploaded product media |
| `/run/mysqld/mysqld.sock`                 | (socket)                          | host MySQL (`localhost`) |

Templates for the two config files are committed as `*.example`.

## Local build (smoke test, no DB)

```bash
docker build -t bethanywebsite .
docker run --rm -p 8090:80 bethanywebsite   # serves; DB calls fail without the mounts
```

## CI secrets (GitHub → repo → Settings → Secrets)

- `VPS_HOST`, `VPS_USER` — the VPS SSH target.
- `VPS_SSH_KEY` — private key whose public half is in the VPS `authorized_keys`.
- `GITHUB_TOKEN` is provided automatically (GHCR push + ephemeral pull login).
