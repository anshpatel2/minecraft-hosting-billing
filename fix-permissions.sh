#!/bin/bash

# =================================================================
# Fix Laravel Permissions Script
# =================================================================
# This script fixes common permission issues with Laravel
# =================================================================

echo "🔧 Fixing Laravel permissions for Minecraft Hosting Billing..."

PROJECT_DIR="/var/www/minecraft-hosting-billing"

if [ ! -d "$PROJECT_DIR" ]; then
    echo "❌ Project directory not found: $PROJECT_DIR"
    exit 1
fi

cd "$PROJECT_DIR"

echo "📁 Setting directory ownership to www-data..."
chown -R www-data:www-data .

echo "📄 Setting file permissions..."
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;

echo "📝 Setting writable permissions for Laravel directories..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

echo "🎯 Setting executable permissions..."
chmod 755 artisan

echo "🔐 Setting .env file permissions..."
if [ -f .env ]; then
    chmod 664 .env
    chown www-data:www-data .env
    echo "✅ .env permissions fixed"
else
    echo "⚠️ .env file not found, creating from .env.example..."
    if [ -f .env.example ]; then
        sudo -u www-data cp .env.example .env
        chmod 664 .env
        chown www-data:www-data .env
        echo "✅ .env created with proper permissions"
        
        # Generate application key if needed
        if ! grep -q "APP_KEY=base64:" .env; then
            echo "🔑 Generating application key..."
            sudo -u www-data php artisan key:generate --force
        fi
    else
        echo "❌ .env.example not found"
    fi
fi

echo "📦 Setting vendor permissions..."
if [ -d vendor ]; then
    chmod -R 755 vendor
fi

echo "🎨 Setting public assets permissions..."
if [ -d public ]; then
    chmod -R 755 public
fi

echo "📊 Setting log permissions..."
if [ -d storage/logs ]; then
    chmod -R 775 storage/logs
    touch storage/logs/laravel.log 2>/dev/null || true
    chown www-data:www-data storage/logs/laravel.log 2>/dev/null || true
    chmod 664 storage/logs/laravel.log 2>/dev/null || true
fi

echo "✅ Laravel permissions fixed successfully!"
echo
echo "🔄 Restarting services..."
systemctl restart php8.2-fpm 2>/dev/null || systemctl restart php8.1-fpm 2>/dev/null || systemctl restart php-fpm
systemctl restart nginx

echo "🎯 Done! You can now run:"
echo "   cd $PROJECT_DIR"
echo "   sudo -u www-data php artisan key:generate --force"
echo "   sudo -u www-data php artisan migrate"

echo
echo "🧪 Test your installation:"
echo "   sudo -u www-data php artisan --version"
