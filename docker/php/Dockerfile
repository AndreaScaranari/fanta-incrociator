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

# Copia lo script entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /var/www/html

EXPOSE 9000

# Usa l'entrypoint invece del CMD diretto
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]