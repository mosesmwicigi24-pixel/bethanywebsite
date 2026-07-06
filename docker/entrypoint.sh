#!/bin/sh
set -e

# Bind-mounted dirs (uploads, logs) may arrive with host ownership. www-data is
# uid 33 both on the host and in this Debian image, so this is normally a no-op,
# but keep it defensive in case the host uid differs.
for d in uploads application/logs application/cache; do
    [ -d "/var/www/html/$d" ] && chown -R www-data:www-data "/var/www/html/$d" 2>/dev/null || true
done

exec "$@"
