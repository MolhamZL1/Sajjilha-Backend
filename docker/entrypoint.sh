#!/bin/sh
set -e

# clear cached config (مهم لما تغيّر DB / ENV)
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# migrate (لا تخبي الأخطاء نهائياً)
php artisan migrate --force || true

# start apache in foreground
apache2-foreground
