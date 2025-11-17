#!/bin/sh

# Aspetta che i volumi siano montati
echo "Setting up Laravel permissions..."

# Crea le directory se non esistono
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Sistema i permessi
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Se esiste composer.json e non esiste vendor, installa le dipendenze
if [ -f /var/www/html/composer.json ] && [ ! -d /var/www/html/vendor ]; then
    echo "Installing composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Se .env non esiste, copialo da .env.example
if [ ! -f /var/www/html/.env ] && [ -f /var/www/html/.env.example ]; then
    echo "Creating .env file..."
    cp /var/www/html/.env.example /var/www/html/.env
    php artisan key:generate
fi

echo "Laravel setup completed!"

# Avvia PHP-FPM
exec php-fpm