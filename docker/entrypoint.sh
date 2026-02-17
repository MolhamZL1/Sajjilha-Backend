#!/bin/sh
set -e

cd /var/www/html

# Laravel dirs
mkdir -p storage/framework/cache/data \
         storage/framework/sessions \
         storage/framework/views \
         storage/logs \
         bootstrap/cache

# حاول تصلّح الملكية/الصلاحيات (K8s ممكن يمنع chown)
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

# الصلاحيات الأهم: الكتابة للمجموعة (أفضل من 777)
chmod -R ug+rwX storage bootstrap/cache 2>/dev/null || true

# لو المنصة شغالة بـ UID غريب وما بيقبل chown:
# افتحها للكل كحل اضطراري (قبيح بس يخلصك)
chmod -R 777 storage bootstrap/cache 2>/dev/null || true

# Clear caches
php artisan config:clear || true
php artisan cache:clear  || true
php artisan route:clear  || true
php artisan view:clear   || true

# Migrate
php artisan migrate --force || true

# Silence apache ServerName warning (مو ضروري بس نظافة)
echo "ServerName localhost" >> /etc/apache2/apache2.conf

exec apache2-foreground
