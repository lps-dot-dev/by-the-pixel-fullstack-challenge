#!/bin/bash

set -e

echo "📦 Running Laravel migrations..."
php artisan migrate

echo "🧵 Starting Supervisor..."
exec /usr/bin/supervisord -n

echo "🔧 Running Laravel tests..."
php artisan test
