#!/bin/bash

set -e

echo "⚙️ Update composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

if ! grep -q '^APP_KEY=' .env || [ -z "$(grep '^APP_KEY=' .env | cut -d'=' -f2)" ]; then
    echo "🔑 APP_KEY is empty. Generating new key..."
    php artisan key:generate --force
else
    echo "🔑 APP_KEY already set. Skipping key generation."
fi

echo "📦 Running Laravel migrations..."
php artisan migrate

echo "🧵 Starting Supervisor..."
exec /usr/bin/supervisord -n

echo "🔧 Running Laravel tests..."
php artisan test
