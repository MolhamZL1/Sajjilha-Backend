#!/bin/sh
set -e

cd /var/www/html

# ضروري: إنشاء مجلدات لارافيل
mkdir -p storage bootstrap/cache

# صلاحيات وقت التشغيل (مهم بكوبيرنيتس/فوليوم)
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R ug+rwX storage bootstrap/cache 2>/dev/null || true

# تنظيف كاشات (بدون ما يطيّح الكونتينر إذا فشل شيء)
php artisan config:clear || true
php artisan cache:clear  || true
php artisan route:clear  || true
php artisan view:clear   || true

# تشغيل مايغريشن (لا تخبي كلشي، بس لا تقتل الستارت)
php artisan migrate --force || true

# تحذير Apache ServerName مو مشكلة تشغيل، بس منسكتها
echo "ServerName localhost" >> /etc/apache2/apache2.conf

# شغّل Apache للأبد
exec apache2-foreground
