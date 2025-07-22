#!/bin/bash

# =================================================================
# Quick Fix for Laravel Key Generation Permission Error
# =================================================================
# Run this on your Linux server to fix the .env permission issue
# =================================================================

echo "🔧 Fixing Laravel .env permission error..."

# Load PROJECT_DIR from config file or environment variable
if [ -f "./project.conf" ]; then
    source ./project.conf
fi

PROJECT_DIR="${PROJECT_DIR:-/var/www/minecraft-hosting-billing}"

if [ ! -d "$PROJECT_DIR" ]; then
    echo "❌ Error: Project directory not found at $PROJECT_DIR"
    echo "Please make sure the installation reached the point where the project was cloned."
    exit 1
fi

cd "$PROJECT_DIR"

echo "📁 Current .env file status:"
ls -la .env 2>/dev/null || echo "❌ .env file not found"

echo "🔧 Fixing permissions..."

# Ensure .env.example exists and has correct permissions
if [ -f .env.example ]; then
    echo "✅ .env.example found"
    chmod 644 .env.example
    chown www-data:www-data .env.example
else
    echo "❌ .env.example not found"
fi

# Remove existing .env if it has wrong permissions
if [ -f .env ]; then
    echo "🗑️ Removing existing .env with wrong permissions"
    rm .env
fi

# Copy .env.example to .env with correct permissions
echo "📄 Creating .env file with correct permissions..."
cp .env.example .env
chown www-data:www-data .env
chmod 664 .env

# Verify permissions
echo "📋 New .env file permissions:"
ls -la .env

echo "🔑 Generating application key..."
sudo -u www-data php artisan key:generate --force

if [ $? -eq 0 ]; then
    echo "✅ Application key generated successfully!"
    echo "🎯 You can now continue with the installation."
else
    echo "❌ Key generation failed. Additional troubleshooting needed."
    echo "🔍 Check if PHP and composer dependencies are properly installed:"
    echo "   sudo -u www-data composer install --no-dev"
    echo "   sudo chown -R www-data:www-data ."
    echo "   sudo chmod -R 755 ."
    echo "   sudo chmod -R 775 storage bootstrap/cache"
fi
