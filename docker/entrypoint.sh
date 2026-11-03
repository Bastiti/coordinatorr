#!/bin/sh
set -e

cd /var/www/html

# refuse insecure configurations
if [ -z "$APP_KEY" ]; then
    echo "ERROR: APP_KEY is not set (generate one with: echo base64:\$(openssl rand -base64 32))" >&2
    exit 1
fi
if [ -z "$ADMIN_PANEL_SECRET" ] || [ "$ADMIN_PANEL_SECRET" = "simsalabim" ]; then
    echo "ERROR: ADMIN_PANEL_SECRET is unset or still the public default" >&2
    exit 1
fi
if [ "$APP_DEBUG" = "true" ] && [ "$APP_ENV" = "production" ]; then
    echo "ERROR: APP_DEBUG must be false in production" >&2
    exit 1
fi

# report the PHP version at every start (offline, never blocks startup)
echo "Coordinatorr $(cat storage/app/version.txt 2>/dev/null) on PHP $(php -r 'echo PHP_VERSION;')"

# prepare database
[ -f /data/database.sqlite ] || touch /data/database.sqlite
php artisan migrate --force --no-interaction

php artisan config:cache
php artisan view:cache

exec "$@"
