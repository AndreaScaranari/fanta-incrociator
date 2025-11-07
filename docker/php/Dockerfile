FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    build-base \
    libzip-dev \
    zip \
    curl \
    git

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    bcmath

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

EXPOSE 9000

CMD ["php-fpm"]