#!/bin/bash

# =================================================================
# Minecraft Hosting Billing - One-Command Installer
# =================================================================
# Usage: curl -fsSL https://raw.githubusercontent.com/anshpatel2/minecraft-hosting-billing/main/install.sh | sudo bash -s -- yourdomain.com your@email.com
# =================================================================

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
NC='\033[0m'

print_status() { echo -e "${BLUE}🔄 [INFO]${NC} $1"; }
print_success() { echo -e "${GREEN}✅ [SUCCESS]${NC} $1"; }
print_warning() { echo -e "${YELLOW}⚠️  [WARNING]${NC} $1"; }
print_error() { echo -e "${RED}❌ [ERROR]${NC} $1"; }

# Banner
echo -e "${PURPLE}"
cat << 'EOF'
╔══════════════════════════════════════════════════════════════╗
║                 MINECRAFT HOSTING BILLING                   ║
║                  One-Command Installer                      ║
║                                                              ║
║  🚀 Deploy a complete hosting platform in under 5 minutes   ║
║                                                              ║
║                    Made by Ansh Patel                       ║
║                 GitHub: @anshpatel2                         ║
╚══════════════════════════════════════════════════════════════╝
EOF
echo -e "${NC}"

# Check root
if [ "$EUID" -ne 0 ]; then
    print_error "Please run as root or with sudo"
    exit 1
fi

# Get parameters
DOMAIN=${1:-""}
EMAIL=${2:-""}

if [ -z "$DOMAIN" ]; then
    read -p "🌐 Enter your domain (e.g., hosting.example.com): " DOMAIN
fi

if [ -z "$EMAIL" ]; then
    read -p "📧 Enter your email for SSL certificates: " EMAIL
fi

# Generate passwords
DB_PASSWORD=$(openssl rand -base64 16 | tr -d "=+/")
ADMIN_PASSWORD=$(openssl rand -base64 16 | tr -d "=+/")
MYSQL_ROOT_PASSWORD=$(openssl rand -base64 16 | tr -d "=+/")

print_status "🚀 Starting deployment for $DOMAIN..."

# Update system
print_status "📦 Updating system packages"
export DEBIAN_FRONTEND=noninteractive
apt update -qq
apt upgrade -y -qq

# Install software-properties-common first
print_status "📦 Installing prerequisites"
apt install -y -qq software-properties-common curl wget unzip git

# Add PHP repository
print_status "📦 Adding PHP repository"
add-apt-repository -y ppa:ondrej/php
apt update -qq

# Detect available PHP version
print_status "🔍 Detecting available PHP version"
if apt-cache show php8.3-fpm >/dev/null 2>&1; then
    PHP_VERSION="8.3"
    print_success "Using PHP 8.3"
elif apt-cache show php8.2-fpm >/dev/null 2>&1; then
    PHP_VERSION="8.2"
    print_success "Using PHP 8.2"
elif apt-cache show php8.1-fpm >/dev/null 2>&1; then
    PHP_VERSION="8.1"
    print_warning "Using PHP 8.1"
else
    print_error "No suitable PHP version found!"
    exit 1
fi

# Install packages
print_status "📦 Installing required packages"
# Install in stages to handle any package conflicts
apt install -y -qq nginx mysql-server
apt install -y -qq php${PHP_VERSION}-fpm php${PHP_VERSION}-mysql php${PHP_VERSION}-xml php${PHP_VERSION}-curl
apt install -y -qq php${PHP_VERSION}-mbstring php${PHP_VERSION}-zip php${PHP_VERSION}-gd php${PHP_VERSION}-bcmath
apt install -y -qq php${PHP_VERSION}-intl php${PHP_VERSION}-cli
apt install -y -qq composer git curl wget unzip
apt install -y -qq certbot python3-certbot-nginx
apt install -y -qq redis-server supervisor fail2ban ufw

# Verify critical packages are installed
if ! command -v nginx >/dev/null; then
    print_error "Nginx installation failed"
    exit 1
fi

if ! command -v mysql >/dev/null; then
    print_error "MySQL installation failed"
    exit 1
fi

if ! command -v "php${PHP_VERSION}" >/dev/null; then
    print_error "PHP ${PHP_VERSION} installation failed"
    exit 1
fi

print_success "All packages installed successfully"

# Configure firewall
print_status "🔒 Configuring firewall"
ufw --force enable
ufw allow 22
ufw allow 80
ufw allow 443

# Configure MySQL
print_status "🗄️ Configuring MySQL"
# Ensure MySQL is running
systemctl enable mysql
systemctl start mysql
sleep 5

# Secure MySQL installation and set root password
print_status "Securing MySQL installation"
# First, try without password (fresh install)
mysql -u root -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '$MYSQL_ROOT_PASSWORD'; FLUSH PRIVILEGES;" 2>/dev/null || \
# If that fails, try with empty password
mysql -u root -p'' -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '$MYSQL_ROOT_PASSWORD'; FLUSH PRIVILEGES;" 2>/dev/null || \
# If that fails, MySQL might already have a password set
mysql -u root -p"$MYSQL_ROOT_PASSWORD" -e "SELECT 1;" 2>/dev/null || {
    print_warning "MySQL root password setup may have failed. Continuing with database creation..."
}

# Create database and user with better error handling
print_status "Creating database and user"
mysql -u root -p"$MYSQL_ROOT_PASSWORD" -e "
SET sql_mode = '';
DROP DATABASE IF EXISTS minecraft_hosting;
CREATE DATABASE minecraft_hosting CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
DROP USER IF EXISTS 'minecraft_user'@'localhost';
CREATE USER 'minecraft_user'@'localhost' IDENTIFIED BY '$DB_PASSWORD';
GRANT ALL PRIVILEGES ON minecraft_hosting.* TO 'minecraft_user'@'localhost';
FLUSH PRIVILEGES;
" 2>/dev/null || {
    # Fallback: try without password or with common default passwords
    print_warning "Trying alternative MySQL root access methods..."
    mysql -u root -e "
    SET sql_mode = '';
    DROP DATABASE IF EXISTS minecraft_hosting;
    CREATE DATABASE minecraft_hosting CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    DROP USER IF EXISTS 'minecraft_user'@'localhost';
    CREATE USER 'minecraft_user'@'localhost' IDENTIFIED BY '$DB_PASSWORD';
    GRANT ALL PRIVILEGES ON minecraft_hosting.* TO 'minecraft_user'@'localhost';
    FLUSH PRIVILEGES;
    " 2>/dev/null || {
        print_error "Failed to configure MySQL. Please check MySQL installation."
        exit 1
    }
}

# Test the connection
print_status "Testing database connection"
mysql -u minecraft_user -p"$DB_PASSWORD" -e "USE minecraft_hosting; SELECT 1 as test;" || {
    print_error "Failed to connect to database with minecraft_user"
    exit 1
}

print_success "MySQL configured successfully"

# Clone repository
print_status "📥 Downloading application"
cd /var/www
rm -rf minecraft-hosting-billing
git clone https://github.com/anshpatel2/minecraft-hosting-billing.git
cd minecraft-hosting-billing

# Set permissions
print_status "🔐 Setting permissions"
chown -R www-data:www-data /var/www/minecraft-hosting-billing
chmod -R 755 /var/www/minecraft-hosting-billing
chmod -R 775 /var/www/minecraft-hosting-billing/storage
chmod -R 775 /var/www/minecraft-hosting-billing/bootstrap/cache

# Install Composer dependencies
print_status "📦 Installing PHP dependencies"
sudo -u www-data composer install --no-dev --optimize-autoloader --no-interaction

# Configure environment
print_status "⚙️ Configuring environment"
sudo -u www-data cp .env.example .env
sudo -u www-data php artisan key:generate

# Configure .env file
print_status "⚙️ Updating configuration"
sed -i "s/^APP_NAME=.*/APP_NAME=\"Minecraft Hosting\"/" .env
sed -i "s/^APP_ENV=.*/APP_ENV=production/" .env
sed -i "s/^APP_DEBUG=.*/APP_DEBUG=false/" .env
sed -i "s|^APP_URL=.*|APP_URL=https://$DOMAIN|" .env
sed -i "s/^DB_CONNECTION=.*/DB_CONNECTION=mysql/" .env
sed -i "s/^DB_HOST=.*/DB_HOST=127.0.0.1/" .env
sed -i "s/^DB_PORT=.*/DB_PORT=3306/" .env
sed -i "s/^DB_DATABASE=.*/DB_DATABASE=minecraft_hosting/" .env
sed -i "s/^DB_USERNAME=.*/DB_USERNAME=minecraft_user/" .env
sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env

# Test Laravel database connection
print_status "Testing Laravel database connection"
# Only clear config, not cache (cache table doesn't exist yet)
sudo -u www-data php artisan config:clear
# Add a retry mechanism for database connection
for i in {1..3}; do
    if sudo -u www-data php -r "
require 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
try {
    \$pdo = DB::connection()->getPdo();
    if (\$pdo) {
        echo 'Database connection successful';
        exit(0);
    }
} catch (Exception \$e) {
    echo 'Database connection failed: ' . \$e->getMessage();
    exit(1);
}
"; then
        print_success "Laravel database connection verified"
        break
    else
        if [ $i -eq 3 ]; then
            print_error "Laravel cannot connect to database after 3 attempts"
            print_status "Checking .env file configuration..."
            cat .env | grep -E "^DB_"
            exit 1
        fi
        print_warning "Database connection attempt $i failed, retrying in 5 seconds..."
        sleep 5
    fi
done

# Run database migrations
print_status "🗄️ Setting up database"
# Clear config cache first (this doesn't require database)
sudo -u www-data php artisan config:clear

# Try fresh migration first (drops all tables and recreates)
if sudo -u www-data php artisan migrate:fresh --force --seed; then
    print_success "Database setup completed with fresh migration"
else
    print_warning "Fresh migration failed, trying reset method..."
    # Fallback: reset and migrate
    sudo -u www-data php artisan migrate:reset --force 2>/dev/null || true
    sudo -u www-data php artisan migrate --force
    sudo -u www-data php artisan db:seed --force
    print_success "Database setup completed with reset method"
fi

# Now clear all caches after tables exist
sudo -u www-data php artisan cache:clear 2>/dev/null || true

# Configure Nginx
print_status "🌐 Configuring web server"
cat > /etc/nginx/sites-available/minecraft-hosting << EOF
server {
    listen 80;
    server_name $DOMAIN;
    root /var/www/minecraft-hosting-billing/public;
    index index.php index.html;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php${PHP_VERSION}-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    client_max_body_size 100M;
}
EOF

# Enable site
ln -sf /etc/nginx/sites-available/minecraft-hosting /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Test nginx configuration
if nginx -t; then
    print_success "Nginx configuration is valid"
else
    print_error "Nginx configuration is invalid"
    exit 1
fi

# Configure queue worker
print_status "⚡ Configuring queue worker"
cat > /etc/supervisor/conf.d/minecraft-hosting-worker.conf << EOF
[program:minecraft-hosting-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/minecraft-hosting-billing/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/minecraft-hosting-billing/storage/logs/worker.log
EOF

# Start services
print_status "🚀 Starting services"
systemctl enable nginx
systemctl restart nginx
systemctl enable php${PHP_VERSION}-fpm
systemctl restart php${PHP_VERSION}-fpm
systemctl enable mysql
systemctl restart mysql
systemctl enable redis-server
systemctl restart redis-server
systemctl enable supervisor
systemctl restart supervisor

# Wait for services to start
sleep 5

# Verify services are running
services_failed=()
for service in nginx "php${PHP_VERSION}-fpm" mysql redis-server supervisor; do
    if ! systemctl is-active --quiet "$service"; then
        services_failed+=("$service")
    fi
done

if [ ${#services_failed[@]} -gt 0 ]; then
    print_error "Failed to start services: ${services_failed[*]}"
    for service in "${services_failed[@]}"; do
        print_status "Checking $service status:"
        systemctl status "$service" --no-pager -l
    done
    exit 1
fi

# Configure supervisor for queue worker
supervisorctl reread
supervisorctl update
supervisorctl start minecraft-hosting-worker:* || print_warning "Queue worker will be configured after first boot"

print_success "All services started successfully"

# Configure SSL
print_status "🔒 Configuring SSL certificate"
# Wait for nginx to be fully ready
sleep 5
# Test if domain resolves to this server
if ! ping -c 1 "$DOMAIN" >/dev/null 2>&1; then
    print_warning "Domain $DOMAIN may not be pointing to this server yet"
    print_status "You can configure SSL later with: certbot --nginx -d $DOMAIN"
else
    # Try to get SSL certificate
    if certbot --nginx -d "$DOMAIN" --email "$EMAIL" --agree-tos --non-interactive --redirect; then
        print_success "SSL certificate configured successfully"
    else
        print_warning "SSL certificate setup failed. You can configure it later with:"
        print_status "certbot --nginx -d $DOMAIN --email $EMAIL --agree-tos --redirect"
    fi
fi

# Create admin user
print_status "👤 Creating admin user"
# Check if the create-admin command exists
if sudo -u www-data php artisan list | grep -q "user:create-admin"; then
    sudo -u www-data php artisan user:create-admin \
        --name="Admin" \
        --email="$EMAIL" \
        --password="$ADMIN_PASSWORD" 2>/dev/null || {
        print_warning "Admin user creation failed, you can create one manually later"
    }
else
    print_warning "Admin user creation command not found, you can create one manually in the admin panel"
fi

# Final optimizations
print_status "⚡ Optimizing application"
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# Create update script
print_status "📝 Creating update script"
cat > /usr/local/bin/minecraft-hosting-update << 'EOF'
#!/bin/bash
cd /var/www/minecraft-hosting-billing
git pull origin main
sudo -u www-data composer install --no-dev --optimize-autoloader --no-interaction
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
supervisorctl restart minecraft-hosting-worker:*
systemctl reload nginx
echo "✅ Update completed!"
EOF
chmod +x /usr/local/bin/minecraft-hosting-update

# Success message
echo -e "${GREEN}"
cat << 'EOF'
╔══════════════════════════════════════════════════════════════╗
║                    🎉 INSTALLATION COMPLETE! 🎉             ║
╚══════════════════════════════════════════════════════════════╝
EOF
echo -e "${NC}"

print_success "🌐 Website: https://$DOMAIN"
print_success "📧 Admin Email: $EMAIL"
print_success "🔑 Admin Password: $ADMIN_PASSWORD"
print_success "🗄️ Database Password: $DB_PASSWORD"
print_success "🔑 MySQL Root Password: $MYSQL_ROOT_PASSWORD"
echo
print_status "📖 View logs: tail -f /var/www/minecraft-hosting-billing/storage/logs/laravel.log"
print_status "🔄 Update anytime: sudo minecraft-hosting-update"
print_status "🔧 Restart services: sudo systemctl restart nginx php${PHP_VERSION}-fpm"
echo
print_warning "⚠️  IMPORTANT: Save the admin password and database password!"
print_success "✨ Ready to host Minecraft servers!"
echo
