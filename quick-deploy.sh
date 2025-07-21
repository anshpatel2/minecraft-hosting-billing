#!/bin/bash

# =================================================================
# Minecraft Hosting Billing - Quick Setup Script
# =================================================================
# This is a simplified version for quick deployment
# Usage: bash quick-deploy.sh [domain] [email]
# =================================================================

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

print_status() { echo -e "${BLUE}[INFO]${NC} $1"; }
print_success() { echo -e "${GREEN}[SUCCESS]${NC} $1"; }
print_warning() { echo -e "${YELLOW}[WARNING]${NC} $1"; }
print_error() { echo -e "${RED}[ERROR]${NC} $1"; }

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    print_error "Please run as root: sudo bash quick-deploy.sh"
    exit 1
fi

# Get parameters
DOMAIN=${1:-""}
EMAIL=${2:-""}

if [ -z "$DOMAIN" ]; then
    read -p "Enter your domain: " DOMAIN
fi

if [ -z "$EMAIL" ]; then
    read -p "Enter your email: " EMAIL
fi

# Generate passwords
DB_PASSWORD=$(openssl rand -base64 16 | tr -d "=+/")
ADMIN_PASSWORD=$(openssl rand -base64 16 | tr -d "=+/")

print_status "🚀 Starting quick deployment for $DOMAIN..."

# Update system
print_status "📦 Installing packages..."
apt update
apt install -y nginx mysql-server php8.2-fpm php8.2-mysql php8.2-xml php8.2-curl php8.2-mbstring php8.2-zip php8.2-gd php8.2-bcmath composer git certbot python3-certbot-nginx redis-server

# Setup firewall
print_status "🔥 Configuring firewall..."
ufw --force enable
ufw allow ssh
ufw allow 'Nginx Full'

# Setup MySQL
print_status "🗄️ Setting up database..."
mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '$DB_PASSWORD';"
mysql -u root -p$DB_PASSWORD -e "CREATE DATABASE minecraft_hosting CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p$DB_PASSWORD -e "CREATE USER 'minecraft_user'@'localhost' IDENTIFIED BY '$DB_PASSWORD';"
mysql -u root -p$DB_PASSWORD -e "GRANT ALL PRIVILEGES ON minecraft_hosting.* TO 'minecraft_user'@'localhost';"
mysql -u root -p$DB_PASSWORD -e "FLUSH PRIVILEGES;"

# Clone project
print_status "📁 Setting up project..."
cd /var/www
git clone https://github.com/anshpatel2/minecraft-hosting-billing.git
cd minecraft-hosting-billing
chown -R www-data:www-data .
chmod -R 755 .
chmod -R 775 storage bootstrap/cache

# Setup Laravel
print_status "⚡ Configuring Laravel..."
sudo -u www-data composer install --no-dev --optimize-autoloader
cp .env.example .env
sudo -u www-data php artisan key:generate

# Configure environment
sed -i "s/^APP_NAME=.*/APP_NAME=\"Minecraft Hosting\"/" .env
sed -i "s/^APP_ENV=.*/APP_ENV=production/" .env
sed -i "s/^APP_DEBUG=.*/APP_DEBUG=false/" .env
sed -i "s/^APP_URL=.*/APP_URL=https:\/\/$DOMAIN/" .env
sed -i "s/^DB_DATABASE=.*/DB_DATABASE=minecraft_hosting/" .env
sed -i "s/^DB_USERNAME=.*/DB_USERNAME=minecraft_user/" .env
sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env

# Run migrations
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan config:cache

# Create admin user
print_status "👤 Creating admin user..."
sudo -u www-data php artisan tinker --execute="
\$user = App\Models\User::create([
    'name' => 'Administrator',
    'email' => '$EMAIL',
    'password' => Hash::make('$ADMIN_PASSWORD'),
    'email_verified_at' => now(),
]);

\$adminRole = Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
\$user->assignRole(\$adminRole);
echo 'Admin user created';
"

# Configure Nginx
print_status "🌐 Configuring Nginx..."
cat > /etc/nginx/sites-available/minecraft-hosting << EOF
server {
    listen 80;
    server_name $DOMAIN www.$DOMAIN;
    root /var/www/minecraft-hosting-billing/public;
    index index.php;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

ln -sf /etc/nginx/sites-available/minecraft-hosting /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
nginx -t
systemctl reload nginx

# Setup SSL
print_status "🔒 Setting up SSL..."
certbot --nginx -d $DOMAIN -d www.$DOMAIN --non-interactive --agree-tos --email $EMAIL --redirect

# Final restart
systemctl restart nginx
systemctl restart php8.2-fpm

print_success "🎉 Deployment completed!"
echo
print_success "🌐 Website: https://$DOMAIN"
print_success "📧 Admin Email: $EMAIL"
print_warning "🔑 Admin Password: $ADMIN_PASSWORD"
print_warning "🔐 Database Password: $DB_PASSWORD"
echo
print_warning "⚠️ Save these credentials securely!"
print_status "📖 Logs: tail -f /var/www/minecraft-hosting-billing/storage/logs/laravel.log"
