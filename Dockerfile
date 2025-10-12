# صورة جاهزة فيها Nginx + PHP-FPM
FROM webdevops/php-nginx:8.2-alpine

ENV WEB_DOCUMENT_ROOT=/var/www/html/public \
    APP_ENV=production \
    APP_DEBUG=0

# حزم لازمة وامتدادات PHP و Composer
RUN apk add --no-cache git curl zip unzip bash icu-dev libzip-dev oniguruma-dev \
    && docker-php-ext-install pdo pdo_mysql \
    && rm -rf /var/cache/apk/*

WORKDIR /var/www/html
COPY . /var/www/html

# تثبيت الاعتمادات
RUN set -ex \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --prefer-dist --optimize-autoloader \
    # لو ما في .env، انسخه من المثال (القيم الحقيقية رح نحطها من لوحة غيمة)
    && if [ ! -f .env ]; then cp .env.example .env || true; fi \
    && php artisan storage:link || true \
    && chown -R application:application storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# سكربت تمهيدي قبل تشغيل الخدمات
RUN mkdir -p /opt/docker/provision/entrypoint.d
COPY docker/10-laravel-boot.sh /opt/docker/provision/entrypoint.d/10-laravel-boot.sh
RUN chmod +x /opt/docker/provision/entrypoint.d/10-laravel-boot.sh

EXPOSE 80
