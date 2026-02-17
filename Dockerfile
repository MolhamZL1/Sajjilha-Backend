FROM php:8.2-apache

# System deps + PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev \
    && docker-php-ext-install pdo_mysql bcmath zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy project
COPY . .

# Install Laravel deps
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Apache: document root -> /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && printf "\n<Directory /var/www/html/public>\nAllowOverride All\nRequire all granted\n</Directory>\n" >> /etc/apache2/apache2.conf

# Entrypoint
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80
CMD ["/entrypoint.sh"]
