#!/bin/bash
echo "🚀 Starting deployment..."

cd ~/demo.vsinfosys.in

echo "📥 Pulling latest code..."
git pull origin main

echo "🗄️ Running migrations..."
php artisan migrate --force

echo "🧹 Clearing cache..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear

echo "⚡ Optimizing..."
php artisan config:cache
php artisan view:cache

echo "✅ Deployment complete!"