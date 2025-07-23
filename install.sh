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
apt install -y -qq \
    nginx \
    mysql-server \
    php${PHP_VERSION}-fpm \
    php${PHP_VERSION}-mysql \
    php${PHP_VERSION}-xml \
    php${PHP_VERSION}-curl \
    php${PHP_VERSION}-mbstring \
    php${PHP_VERSION}-zip \
    php${PHP_VERSION}-gd \
    php${PHP_VERSION}-bcmath \
    php${PHP_VERSION}-intl \
    php${PHP_VERSION}-cli \
    composer \
    certbot \
    python3-certbot-nginx \
    redis-server \
    supervisor \
    fail2ban \
    ufw

# Configure firewall
print_status "🔒 Configuring firewall"
ufw --force enable
ufw allow 22
ufw allow 80
ufw allow 443

# Configure MySQL
print_status "🗄️ Configuring MySQL"
mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '$MYSQL_ROOT_PASSWORD';" 2>/dev/null || true
mysql -u root -p$MYSQL_ROOT_PASSWORD -e "CREATE DATABASE IF NOT EXISTS minecraft_hosting CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p$MYSQL_ROOT_PASSWORD -e "CREATE USER IF NOT EXISTS 'minecraft_user'@'localhost' IDENTIFIED BY '$DB_PASSWORD';"
mysql -u root -p$MYSQL_ROOT_PASSWORD -e "GRANT ALL PRIVILEGES ON minecraft_hosting.* TO 'minecraft_user'@'localhost';"
mysql -u root -p$MYSQL_ROOT_PASSWORD -e "FLUSH PRIVILEGES;"

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

# Run database migrations
print_status "🗄️ Setting up database"
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan db:seed --force

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
nginx -t

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
systemctl restart nginx
systemctl restart php${PHP_VERSION}-fpm
systemctl restart mysql
systemctl restart redis-server
supervisorctl reread
supervisorctl update
supervisorctl start minecraft-hosting-worker:*

# Configure SSL
print_status "🔒 Configuring SSL certificate"
certbot --nginx -d $DOMAIN --email $EMAIL --agree-tos --non-interactive --redirect

# Create admin user
print_status "👤 Creating admin user"
sudo -u www-data php artisan user:create-admin \
    --name="Admin" \
    --email="$EMAIL" \
    --password="$ADMIN_PASSWORD" 2>/dev/null || true

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
