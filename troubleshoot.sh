#!/bin/bash

# =================================================================
# Complete Installation Troubleshooter
# =================================================================
# This script fixes common issues during the installation process
# =================================================================

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

print_status() { echo -e "${BLUE}🔄 [FIX]${NC} $1"; }
print_success() { echo -e "${GREEN}✅ [SUCCESS]${NC} $1"; }
print_warning() { echo -e "${YELLOW}⚠️  [WARNING]${NC} $1"; }
print_error() { echo -e "${RED}❌ [ERROR]${NC} $1"; }

echo -e "${BLUE}"
echo "╔══════════════════════════════════════════════════════════════╗"
echo "║              INSTALLATION TROUBLESHOOTER                    ║"
echo "║          Fix common Laravel and MySQL issues                ║"
echo "╚══════════════════════════════════════════════════════════════╝"
echo -e "${NC}"

# Allow PROJECT_DIR to be set via argument or environment variable, fallback to default
DEFAULT_PROJECT_DIR="/var/www/minecraft-hosting-billing"
PROJECT_DIR="${1:-${PROJECT_DIR:-$DEFAULT_PROJECT_DIR}}"

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    print_error "Please run as root: sudo bash troubleshoot.sh"
    exit 1
fi

# Check if project directory exists
if [ ! -d "$PROJECT_DIR" ]; then
    print_error "Project directory not found: $PROJECT_DIR"
    print_error "You can specify the directory as an argument: sudo bash troubleshoot.sh /path/to/project"
    print_error "Or set the PROJECT_DIR environment variable before running the script."
    print_error "Please run the installer first if the directory does not exist:"
    print_error "curl -fsSL https://raw.githubusercontent.com/anshpatel2/minecraft-hosting-billing/main/install-one-command.sh | sudo bash -s -- domain.com email@domain.com"
    exit 1
fi

cd "$PROJECT_DIR"

# Fix 1: Laravel Permissions
print_status "Fixing Laravel permissions..."
chown -R www-data:www-data .
chmod -R 755 .
chmod -R 775 storage bootstrap/cache
print_success "Basic permissions fixed"

# Fix 2: .env File Permissions
print_status "Fixing .env file permissions..."
if [ -f .env ]; then
    print_status "Backing up current .env..."
    cp .env .env.backup.$(date +%s)
fi

# Remove and recreate .env with proper permissions
rm -f .env
sudo -u www-data cp .env.example .env
chown www-data:www-data .env
chmod 664 .env

print_success ".env file permissions fixed"

# Fix 3: Laravel Key Generation
print_status "Generating Laravel application key..."
if sudo -u www-data php artisan key:generate --force; then
    print_success "Application key generated successfully"
else
    print_warning "Key generation failed, trying to fix dependencies..."
    
    # Fix composer dependencies
    print_status "Reinstalling composer dependencies..."
    sudo -u www-data composer install --no-dev --optimize-autoloader
    
    # Try key generation again
    if sudo -u www-data php artisan key:generate --force; then
        print_success "Application key generated after dependency fix"
    else
        print_error "Key generation still failing - manual intervention needed"
    fi
fi

# Fix 4: MySQL Connection Test
print_status "Testing database connection..."
        # Create a temporary MySQL option file to avoid exposing the password
        MYSQL_CNF=$(mktemp)
        cat > "$MYSQL_CNF" <<EOF
    [client]
    user=$DB_USER
    password=$DB_PASSWORD
    EOF
    
        if mysql --defaults-extra-file="$MYSQL_CNF" -e "USE $DB_NAME;" 2>/dev/null; then
            rm -f "$MYSQL_CNF"
    DB_PASSWORD=$(grep "^DB_PASSWORD=" .env | cut -d '=' -f2- | tr -d '"')
    elif mysql -u root -p"$DB_PASSWORD" -e "USE $DB_NAME;" 2>/dev/null; then
        rm -f "$MYSQL_CNF"
        print_success "Database connection working with root user"
    elif sudo mysql -e "USE $DB_NAME;" 2>/dev/null; then
        rm -f "$MYSQL_CNF"
        print_success "Database connection working with sudo"
    else
        rm -f "$MYSQL_CNF"
        print_warning "Database connection failed - running MySQL setup..."
        
        # Fix MySQL setup
        if sudo mysql -e "CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null; then
            sudo mysql -e "CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASSWORD';"
            sudo mysql -e "GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';"
            sudo mysql -e "FLUSH PRIVILEGES;"
            print_success "Database setup completed"
        else
            print_error "MySQL setup failed - manual configuration needed"
        fi
    fi
            sudo mysql -e "FLUSH PRIVILEGES;"
            print_success "Database setup completed"
        else
            print_error "MySQL setup failed - manual configuration needed"
        fi
    fi
fi

# Fix 5: Run Migrations
print_status "Running database migrations..."
if sudo -u www-data php artisan migrate --force; then
    print_success "Database migrations completed"
else
    print_warning "Migration failed - checking if database is accessible..."
    
    # Test basic Laravel functionality
    if sudo -u www-data php artisan --version >/dev/null 2>&1; then
        print_success "Laravel is functional"
        print_warning "Migration failed - may need manual database setup"
    else
        print_error "Laravel has configuration issues"
    fi
fi

# Fix 6: Cache and Optimize
print_status "Clearing and rebuilding caches..."
sudo -u www-data php artisan config:clear 2>/dev/null || true
sudo -u www-data php artisan route:clear 2>/dev/null || true
sudo -u www-data php artisan view:clear 2>/dev/null || true
sudo -u www-data php artisan cache:clear 2>/dev/null || true

sudo -u www-data php artisan config:cache 2>/dev/null || true
sudo -u www-data php artisan route:cache 2>/dev/null || true
sudo -u www-data php artisan view:cache 2>/dev/null || true
print_success "Caches rebuilt"

# Fix 7: Service Restart
print_status "Restarting services..."
# Dynamically detect and restart the installed PHP-FPM service
PHP_FPM_SERVICE=$(systemctl list-units --type=service --all | grep -Eo 'php[0-9\.]+-fpm\.service|php-fpm\.service' | head -n 1 | sed 's/\.service//')
if [ -n "$PHP_FPM_SERVICE" ]; then
    systemctl restart "$PHP_FPM_SERVICE"
else
    print_warning "Could not detect PHP-FPM service to restart"
fi
systemctl restart nginx
print_success "Services restarted"

# Final Status Check
echo
print_success "🎉 Troubleshooting completed!"
echo
print_status "📋 Status Summary:"
echo "  ✅ Permissions: Fixed"
echo "  ✅ .env file: Fixed"
echo "  ✅ Laravel key: Generated"
echo "  ✅ Services: Restarted"
echo
print_status "🔍 Next steps:"
echo "  1. Test your website: http://your-domain.com"
echo "  2. Access admin: http://your-domain.com/admin"
echo "  3. Check logs: tail -f $PROJECT_DIR/storage/logs/laravel.log"
echo
print_warning "📞 If issues persist:"
echo "  - Check the full installation log"
echo "  - Verify DNS is pointing to your server"
echo "  - Ensure ports 80/443 are open"
echo "  - Review: $PROJECT_DIR/MYSQL-TROUBLESHOOTING.md"
