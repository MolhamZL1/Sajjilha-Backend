# صورة PHP رسمية
FROM php:8.2-cli

# تثبيت الإضافات اللي يحتاجها Laravel
RUN docker-php-ext-install pdo_mysql bcmath


# تثبيت Composer من صورة جاهزة
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# مجلد العمل داخل الكونتينر
WORKDIR /var/www/html

# نسخ كل ملفات المشروع للداخل
COPY . .

# تثبيت باكجات Laravel
RUN composer install --no-dev --optimize-autoloader --no-interaction

# التأكد إن مجلدات التخزين قابلة للكتابة
RUN mkdir -p storage bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# نسخ سكربت التشغيل
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# البورت اللي راح نسمع عليه
EXPOSE 8000

# الأمر النهائي عند تشغيل الكونتينر
CMD ["/entrypoint.sh"]
