FROM composer:latest AS composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist

FROM php:8.4-fpm

ENV APP_DIR=/var/www
ENV TMPDIR=/var/www/storage/tmp

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    sqlite3 \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libsqlite3-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_sqlite \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

WORKDIR ${APP_DIR}

COPY --from=composer /app/vendor ./vendor

COPY . .

RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    storage/tmp \
    bootstrap/cache

RUN chown -R www-data:www-data ${APP_DIR} && \
    chmod -R 775 storage bootstrap/cache && \
    chmod -R 777 storage/tmp

EXPOSE 9000

CMD ["php-fpm"]
