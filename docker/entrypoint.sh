#!/bin/sh
set -e

# نعمل migrate كل مرة يشتغل الكونتينر (لو مافي تغييرات ما بيعمل شي)
php artisan migrate --force || true

# نشغل السيرفر تبع Laravel
php artisan serve --host=0.0.0.0 --port=8000
