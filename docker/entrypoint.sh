#!/bin/sh
set -e

# logs/cache are baked into the image as www-data-owned. uploads is a host bind
# mount that is chowned to www-data:www-data once at deploy setup (not here — a
# recursive chown of the whole media library on every boot would be slow). We only
# make sure the uploads mount point itself is writable so new files can be created.
chown www-data:www-data /var/www/html/uploads 2>/dev/null || true

exec "$@"
