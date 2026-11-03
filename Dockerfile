# Coordinatorr - hardened Apache/PHP image
#
# The PHP branch floats (php:8.4-apache) so every rebuild picks up the newest
# patch release. The build then refuses to finish if:
#   - the PHP branch is end-of-life or the installed PHP is older than the
#     latest patch release published on php.net (checked online)
#   - any locked Composer dependency has a known security advisory
ARG PHP_VERSION=8.4

FROM composer:2 AS composer

FROM php:${PHP_VERSION}-apache

# set to 1 to build even if php.net reports a newer patch release
# (official Docker images can lag php.net by a day or two after a release)
ARG ALLOW_OUTDATED_PHP=0

WORKDIR /var/www/html
ENV DEBIAN_FRONTEND=noninteractive

COPY --chmod=0755 docker/check-php-version.php /usr/local/bin/check-php-version.php

# PHP version check: fail the build on an outdated or unsupported PHP
RUN ALLOW_OUTDATED_PHP=${ALLOW_OUTDATED_PHP} php /usr/local/bin/check-php-version.php

# PHP extensions
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN install-php-extensions gd opcache bcmath zip intl

# Production php.ini, no version disclosure
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && printf 'expose_php = Off\n' > "$PHP_INI_DIR/conf.d/zz-hardening.ini"

# Apache: document root on public/, .htaccess allowed, unprivileged port, no banners
RUN a2enmod rewrite headers \
    && sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
    && sed -ri 's!Listen 80$!Listen 8080!' /etc/apache2/ports.conf \
    && sed -ri 's!<VirtualHost \*:80>!<VirtualHost *:8080>!' /etc/apache2/sites-available/000-default.conf \
    && printf '%s\n' \
        'ServerTokens Prod' \
        'ServerSignature Off' \
        'TraceEnable Off' \
        'SetEnvIf X-Forwarded-Proto "^https$" HTTPS=on' \
        '<Directory /var/www/html/public>' \
        '    AllowOverride All' \
        '    Require all granted' \
        '</Directory>' \
        'Header always set X-Content-Type-Options "nosniff"' \
        'Header always set X-Frame-Options "SAMEORIGIN"' \
        'Header always set Referrer-Policy "strict-origin-when-cross-origin"' \
        > /etc/apache2/conf-available/zz-hardening.conf \
    && a2enconf zz-hardening

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

# Install dependencies first (better layer caching), then audit them
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --no-interaction --prefer-dist \
    && composer audit --locked --no-dev --abandoned=ignore

COPY . .
RUN composer dump-autoload --no-dev --optimize --classmap-authoritative \
    && rm -f /usr/local/bin/composer \
    && rm -f .env \
    # SQLite database lives on the /data volume
    && mkdir -p /data \
    && ln -sf /data/database.sqlite database/database.sqlite \
    && php artisan storage:link \
    && chown -R www-data:www-data storage bootstrap/cache /data \
    && chmod -R u=rwX,go=rX /var/www/html

COPY --chmod=0755 docker/entrypoint.sh /usr/local/bin/coordinatorr-entrypoint

VOLUME /data
USER www-data
EXPOSE 8080

HEALTHCHECK --interval=30s --timeout=5s --start-period=30s --retries=3 \
    CMD php -r 'exit(@file_get_contents("http://127.0.0.1:8080/") === false ? 1 : 0);'

ENTRYPOINT ["coordinatorr-entrypoint"]
CMD ["apache2-foreground"]
