FROM php:8.4-cli

# Install system dependencies
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    unzip \
    git \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    sqlite3 \
    libsqlite3-dev \
    curl \
    && docker-php-ext-install pdo pdo_sqlite mbstring zip gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy app
COPY . /var/www/html


RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache database \
    && touch database/database.sqlite \
    && chown -R www-data:www-data /var/www/html || true

EXPOSE 8000

CMD ["sh", "-c", "set -e; [ -f .env ] || cp .env.example .env; touch database/database.sqlite; composer install --no-interaction --prefer-dist --ignore-platform-reqs; if [ -z \"$APP_KEY\" ]; then php artisan key:generate --force; fi; php artisan config:clear; php artisan migrate --force; php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]
