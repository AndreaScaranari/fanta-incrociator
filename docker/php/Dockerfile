FROM php:8.2-fpm-alpine

# Installa dipendenze
RUN apk add --no-cache \
    build-base \
    libzip-dev \
    zip \
    curl \
    git \
    icu-dev

# Installa estensioni PHP
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    bcmath \
    zip \
    intl

# Installa Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Setup permessi Laravel
RUN mkdir -p /var/www/html/storage/framework/{sessions,views,cache} && \
    mkdir -p /var/www/html/storage/logs && \
    mkdir -p /var/www/html/bootstrap/cache

WORKDIR /var/www/html

EXPOSE 9000

# 🟢 NIENTE ENTRYPOINT, solo CMD
CMD ["sh", "-c", "chmod -R 777 storage bootstrap/cache 2>/dev/null || true && php-fpm"]