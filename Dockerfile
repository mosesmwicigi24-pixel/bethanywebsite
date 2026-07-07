# Bethany House storefront (CodeIgniter 3) — production image.
# PHP 8.3: PHP 8.1 reached end-of-life on 2025-12-31 (no more security patches).
# CodeIgniter 3.1.13 runs on 8.3 with error_reporting tuned in application/config;
# Apache reads the app's .htaccess directly.
FROM php:8.3-apache

# --- System + PHP extensions the app needs (mysqli, gd, zip, intl, mbstring, etc.) ---
# git + unzip are needed by Composer to fetch/extract dependencies.
RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        git unzip \
        libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
        libzip-dev libicu-dev libonig-dev libxml2-dev; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j"$(nproc)" \
        mysqli pdo_mysql gd zip intl bcmath exif mbstring soap opcache; \
    apt-get clean; rm -rf /var/lib/apt/lists/*

# --- Composer (installs Guzzle + the portable Bethany\Services\* autoloader) ---
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# --- Apache: rewrite + headers + forwarded-proto handling ---
RUN a2enmod rewrite headers deflate expires remoteip
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php.ini    /usr/local/etc/php/conf.d/zz-bethany.ini

# --- Application source ---
WORKDIR /var/www/html
COPY . /var/www/html

# Install PHP dependencies (Guzzle) + generate the optimized autoloader for the
# portable Bethany\Services\* packages. --no-dev keeps PHPUnit out of the image.
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Container-safe .htaccess (drops the cPanel php73 handler + force-HTTPS loop;
# nginx terminates TLS and forces HTTPS at the edge).
COPY docker/htaccess /var/www/html/.htaccess

# Runtime-writable dirs (uploads is overlaid by a host volume at deploy time).
RUN set -eux; \
    mkdir -p application/logs application/cache uploads; \
    chown -R www-data:www-data /var/www/html; \
    chmod -R 775 application/logs application/cache uploads

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]
