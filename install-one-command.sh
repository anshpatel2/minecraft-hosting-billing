#!/bin/bash

# =================================================================
# Minecraft Hosting Billing - Ultimate One-Command Installer
# =================================================================
# Usage: curl -fsSL https://raw.githubusercontent.com/anshpatel2/minecraft-hosting-billing/main/install-one-command.sh | sudo bash -s -- yourdomain.com your@email.com
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
print_header() { echo -e "${PURPLE}🚀 $1${NC}"; }

# Banner
echo -e "${PURPLE}"
cat << 'EOF'
╔══════════════════════════════════════════════════════════════╗
║                 MINECRAFT HOSTING BILLING                   ║
║                  One-Command Installer                      ║
║                                                              ║
║  🚀 Deploy a complete hosting platform in under 5 minutes   ║
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
    read -p "📧 Enter your email: " EMAIL
fi

# Generate secure passwords
DB_PASSWORD=$(openssl rand -base64 16 | tr -d "=+/")
ADMIN_PASSWORD=$(openssl rand -base64 16 | tr -d "=+/")
INSTALL_ID=$(date +%s)

# Function to fix Laravel permissions
fix_laravel_permissions() {
    print_status "Applying Laravel permission fixes..."
    
    # Set ownership to www-data
    chown -R www-data:www-data .
    
    # Set base permissions
    find . -type f -exec chmod 644 {} \;
    find . -type d -exec chmod 755 {} \;
    
    # Set writable directories
    chmod -R 775 storage bootstrap/cache
    
    # Set executable permissions for artisan
    chmod 755 artisan
    
    # Ensure .env files have correct permissions
    if [ -f .env.example ]; then
        chmod 644 .env.example
        chown www-data:www-data .env.example
    fi
    
    if [ -f .env ]; then
        chmod 664 .env
        chown www-data:www-data .env
    fi
    
    print_success "Laravel permissions applied"
}

print_header "Starting deployment for $DOMAIN..."

# Update system
print_status "Updating system packages"
export DEBIAN_FRONTEND=noninteractive
apt update -qq
apt upgrade -y -qq

# Add PHP repository for PHP 8.2+
print_status "Adding PHP repository"
apt install -y -qq software-properties-common
add-apt-repository -y ppa:ondrej/php
apt update -qq

# Detect available PHP version (fallback mechanism)
print_status "Detecting available PHP version"
if apt-cache show php8.2-fpm >/dev/null 2>&1; then
    PHP_VERSION="8.2"
    print_success "Using PHP 8.2"
elif apt-cache show php8.1-fpm >/dev/null 2>&1; then
    PHP_VERSION="8.1"
    print_warning "PHP 8.2 not available, falling back to PHP 8.1"
elif apt-cache show php8.0-fpm >/dev/null 2>&1; then
    PHP_VERSION="8.0"
    print_warning "PHP 8.2/8.1 not available, falling back to PHP 8.0"
else
    print_error "No suitable PHP version found!"
    exit 1
fi

# Install essential packages
print_status "Installing required packages"
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
    git \
    curl \
    wget \
    unzip \
    certbot \
    python3-certbot-nginx \
    redis-server \
    supervisor \
    fail2ban \
    ufw

# Configure firewall
print_status "Configuring firewall"
ufw --force enable
ufw allow ssh
ufw allow 'Nginx Full'

# Setup MySQL securely
print_status "Setting up database"

# Function to setup MySQL with multiple fallback methods
setup_mysql() {
    local db_pass="$1"
    
    # Method 1: Try with sudo mysql (Ubuntu 18.04+)
    if sudo mysql -e "SELECT 1;" 2>/dev/null; then
        print_status "Using sudo mysql authentication method"
        sudo mysql -e "CREATE DATABASE IF NOT EXISTS minecraft_hosting CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
        sudo mysql -e "CREATE USER IF NOT EXISTS 'minecraft_user'@'localhost' IDENTIFIED BY '$db_pass';"
        sudo mysql -e "GRANT ALL PRIVILEGES ON minecraft_hosting.* TO 'minecraft_user'@'localhost';"
        sudo mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '$db_pass';"
        sudo mysql -e "FLUSH PRIVILEGES;"
        return 0
    fi
    
    # Method 2: Try without password (fresh installation)
    if mysql -u root -e "SELECT 1;" 2>/dev/null; then
        print_status "Using passwordless root access"
        mysql -u root -e "CREATE DATABASE IF NOT EXISTS minecraft_hosting CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
        mysql -u root -e "CREATE USER IF NOT EXISTS 'minecraft_user'@'localhost' IDENTIFIED BY '$db_pass';"
        mysql -u root -e "GRANT ALL PRIVILEGES ON minecraft_hosting.* TO 'minecraft_user'@'localhost';"
        mysql -u root -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '$db_pass';"
        mysql -u root -e "FLUSH PRIVILEGES;"
        return 0
    fi
    
    # Method 3: Reset MySQL using debian-sys-maint (Debian/Ubuntu specific)
    if [ -f /etc/mysql/debian.cnf ]; then
        print_status "Using debian-sys-maint credentials"
        mysql --defaults-file=/etc/mysql/debian.cnf -e "CREATE DATABASE IF NOT EXISTS minecraft_hosting CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null
        mysql --defaults-file=/etc/mysql/debian.cnf -e "CREATE USER IF NOT EXISTS 'minecraft_user'@'localhost' IDENTIFIED BY '$db_pass';" 2>/dev/null
        mysql --defaults-file=/etc/mysql/debian.cnf -e "GRANT ALL PRIVILEGES ON minecraft_hosting.* TO 'minecraft_user'@'localhost';" 2>/dev/null
        mysql --defaults-file=/etc/mysql/debian.cnf -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '$db_pass';" 2>/dev/null
        mysql --defaults-file=/etc/mysql/debian.cnf -e "FLUSH PRIVILEGES;" 2>/dev/null
        return 0
    fi
    
    # Method 4: Reset using safe mode
    print_status "Attempting MySQL safe mode reset..."
    systemctl stop mysql
    
    # Create init file
    local init_file="/tmp/mysql_init_$(date +%s).sql"
    cat > "$init_file" << EOF
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '$db_pass';
CREATE DATABASE IF NOT EXISTS minecraft_hosting CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'minecraft_user'@'localhost' IDENTIFIED BY '$db_pass';
GRANT ALL PRIVILEGES ON minecraft_hosting.* TO 'minecraft_user'@'localhost';
FLUSH PRIVILEGES;
EOF
    
    # Start with init file
    mysqld --init-file="$init_file" --user=mysql &
    local mysql_pid=$!
    sleep 10
    kill $mysql_pid 2>/dev/null || true
    rm -f "$init_file"
    
    systemctl start mysql
    sleep 3
    
    # Test connection
    if mysql -u root -p"$db_pass" -e "SELECT 1;" 2>/dev/null; then
        return 0
    fi
    
    return 1
}

# Try to setup MySQL
if setup_mysql "$DB_PASSWORD"; then
    print_success "MySQL configured successfully"
else
    print_error "MySQL setup failed. Please manually configure MySQL:"
    print_error "1. Run: sudo mysql_secure_installation"
    print_error "2. Set root password to: $DB_PASSWORD"
    print_error "3. Restart the installer"
    exit 1
fi

# Clone and setup project
print_status "Setting up application"
cd /var/www
rm -rf minecraft-hosting-billing 2>/dev/null || true
git clone https://github.com/anshpatel2/minecraft-hosting-billing.git
cd minecraft-hosting-billing

# Set permissions
fix_laravel_permissions

# Install dependencies
print_status "Installing PHP dependencies"
sudo -u www-data composer install --no-dev --optimize-autoloader --quiet

# Setup environment
print_status "Configuring environment"

# Create .env file with proper permissions from the start
sudo -u www-data cp .env.example .env
chown www-data:www-data .env
chmod 664 .env

# Generate application key as www-data user
sudo -u www-data php artisan key:generate --force

# Verify key generation succeeded
if grep -q "APP_KEY=base64:" .env; then
    print_success "Application key generated successfully"
else
    print_error "Application key generation failed"
    print_error "Attempting manual fix..."
    chown www-data:www-data .env
    chmod 664 .env
    sudo -u www-data php artisan key:generate --force
fi

# Configure .env
sed -i "s|^APP_NAME=.*|APP_NAME=\"Minecraft Hosting Billing\"|" .env
sed -i "s|^APP_ENV=.*|APP_ENV=production|" .env
sed -i "s|^APP_DEBUG=.*|APP_DEBUG=false|" .env
sed -i "s|^APP_URL=.*|APP_URL=https://$DOMAIN|" .env
sed -i "s|^DB_DATABASE=.*|DB_DATABASE=minecraft_hosting|" .env
sed -i "s|^DB_USERNAME=.*|DB_USERNAME=minecraft_user|" .env
sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=$DB_PASSWORD|" .env
sed -i "s|^CACHE_DRIVER=.*|CACHE_DRIVER=redis|" .env
sed -i "s|^SESSION_DRIVER=.*|SESSION_DRIVER=redis|" .env
sed -i "s|^QUEUE_CONNECTION=.*|QUEUE_CONNECTION=redis|" .env
sed -i "s|^MAIL_FROM_ADDRESS=.*|MAIL_FROM_ADDRESS=$EMAIL|" .env

# Ensure .env permissions are still correct after modifications
chown www-data:www-data .env
chmod 664 .env

# Run migrations and setup
print_status "Setting up database"
sudo -u www-data php artisan migrate --force --seed

# Create admin user
print_status "Creating admin user"
sudo -u www-data php artisan tinker --execute="
try {
    \$user = App\Models\User::create([
        'name' => 'Administrator',
        'email' => '$EMAIL',
        'password' => Hash::make('$ADMIN_PASSWORD'),
        'email_verified_at' => now(),
    ]);
    \$adminRole = Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
    \$user->assignRole(\$adminRole);
    echo 'Admin user created successfully\n';
} catch (Exception \$e) {
    echo 'Admin user creation failed: ' . \$e->getMessage() . '\n';
}
"

# Cache configuration
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# Configure Nginx
print_status "Configuring web server"
cat > /etc/nginx/sites-available/minecraft-hosting << EOF
server {
    listen 80;
    server_name $DOMAIN www.$DOMAIN;
    root /var/www/minecraft-hosting-billing/public;
    index index.php index.html;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php${PHP_VERSION}-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
        fastcgi_busy_buffers_size 256k;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    location ~* \.(jpg|jpeg|png|gif|ico|css|js|pdf|txt)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    location ~ /(\.env|\.git|composer\.(json|lock)|package\.json|artisan) {
        deny all;
        return 404;
    }
}
EOF

ln -sf /etc/nginx/sites-available/minecraft-hosting /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
nginx -t && systemctl reload nginx

# Setup SSL
print_status "Setting up SSL certificate"
certbot --nginx -d $DOMAIN -d www.$DOMAIN --non-interactive --agree-tos --email $EMAIL --redirect || {
    print_warning "SSL setup failed, continuing with HTTP"
}

# Setup queue worker
print_status "Setting up background workers"
cat > /etc/supervisor/conf.d/minecraft-hosting-worker.conf << EOF
[program:minecraft-hosting-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/minecraft-hosting-billing/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/minecraft-hosting-billing/storage/logs/worker.log
stopwaitsecs=3600
EOF

# Setup scheduler
echo "* * * * * www-data cd /var/www/minecraft-hosting-billing && php artisan schedule:run >> /dev/null 2>&1" > /etc/cron.d/minecraft-hosting-scheduler

# Create update script
print_status "Creating update system"
cat > /usr/local/bin/minecraft-hosting-update << 'EOF'
#!/bin/bash
set -e

print_status() { echo -e "\033[0;34m🔄 [UPDATE]\033[0m $1"; }
print_success() { echo -e "\033[0;32m✅ [SUCCESS]\033[0m $1"; }
print_error() { echo -e "\033[0;31m❌ [ERROR]\033[0m $1"; }

PROJECT_DIR="/var/www/minecraft-hosting-billing"
BACKUP_DIR="/var/backups/minecraft-hosting"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

print_status "Starting Minecraft Hosting Billing update..."

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
print_status "Backing up database..."
DB_PASSWORD=$(grep DB_PASSWORD $PROJECT_DIR/.env | cut -d '=' -f2)
mysqldump -u minecraft_user -p$DB_PASSWORD minecraft_hosting | gzip > $BACKUP_DIR/database_backup_$TIMESTAMP.sql.gz

# Backup files
print_status "Backing up files..."
tar -czf $BACKUP_DIR/files_backup_$TIMESTAMP.tar.gz -C $PROJECT_DIR .env storage/app/public

# Enter maintenance mode
cd $PROJECT_DIR
sudo -u www-data php artisan down

# Update code
print_status "Updating application code..."
git fetch origin
git reset --hard origin/main

# Update dependencies
print_status "Updating dependencies..."
sudo -u www-data composer install --no-dev --optimize-autoloader

# Run migrations
print_status "Running database migrations..."
sudo -u www-data php artisan migrate --force

# Clear caches
print_status "Clearing caches..."
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# Fix permissions
print_status "Fixing permissions..."
chown -R www-data:www-data $PROJECT_DIR
chmod -R 755 $PROJECT_DIR
chmod -R 775 $PROJECT_DIR/storage $PROJECT_DIR/bootstrap/cache

# Exit maintenance mode
sudo -u www-data php artisan up

# Restart services
print_status "Restarting services..."
systemctl restart php${PHP_VERSION}-fpm
supervisorctl restart minecraft-hosting-worker:*

print_success "Update completed successfully!"
print_status "Backup saved to: $BACKUP_DIR/files_backup_$TIMESTAMP.tar.gz"
print_status "Database backup: $BACKUP_DIR/database_backup_$TIMESTAMP.sql.gz"
EOF

chmod +x /usr/local/bin/minecraft-hosting-update

# Create installation info
cat > /var/www/minecraft-hosting-billing/.installation-info << EOF
INSTALLATION_DATE=$(date)
INSTALLATION_ID=$INSTALL_ID
DOMAIN=$DOMAIN
EMAIL=$EMAIL
VERSION=1.0.0
LAST_UPDATE=$(date)
EOF

# Restart services
print_status "Applying final permission fixes..."
cd /var/www/minecraft-hosting-billing
fix_laravel_permissions

print_status "Restarting services..."
supervisorctl reread
supervisorctl update
supervisorctl start minecraft-hosting-worker:*
systemctl restart nginx
systemctl restart php${PHP_VERSION}-fpm
systemctl restart redis-server

# Final banner
echo -e "${GREEN}"
cat << 'EOF'
╔══════════════════════════════════════════════════════════════╗
║                    🎉 INSTALLATION COMPLETE! 🎉             ║
╚══════════════════════════════════════════════════════════════╝
EOF
echo -e "${NC}"

print_success "🌐 Your Minecraft Hosting Platform is ready!"
echo
print_success "🔗 Website: https://$DOMAIN"
print_success "🔗 Admin Panel: https://$DOMAIN/admin"
print_success "📧 Admin Email: $EMAIL"
print_warning "🔑 Admin Password: $ADMIN_PASSWORD"
echo
print_warning "⚠️  SAVE THESE CREDENTIALS SECURELY!"
echo
print_status "📖 View logs: tail -f /var/www/minecraft-hosting-billing/storage/logs/laravel.log"
print_status "🔄 Update anytime: sudo minecraft-hosting-update"
print_status "🔧 Restart services: sudo systemctl restart nginx php${PHP_VERSION}-fpm"
echo
print_success "🎯 Installation ID: $INSTALL_ID"
print_success "✨ Ready to host Minecraft servers!"
echo
