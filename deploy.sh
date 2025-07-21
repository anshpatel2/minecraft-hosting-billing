#!/bin/bash

# =================================================================
# Minecraft Hosting Billing - VPS Deployment Script
# =================================================================
# This script automates the complete deployment of the application
# Usage: bash deploy.sh [domain] [email] [admin_email] [admin_password]
# =================================================================

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
DOMAIN=${1:-""}
EMAIL=${2:-""}
ADMIN_EMAIL=${3:-"admin@example.com"}
ADMIN_PASSWORD=${4:-""}
APP_NAME="minecraft-hosting-billing"
DB_NAME="minecraft_hosting"
DB_USER="minecraft_user"
DB_PASSWORD=""
PROJECT_DIR="/var/www/$APP_NAME"

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Function to generate random password
generate_password() {
    openssl rand -base64 32 | tr -d "=+/" | cut -c1-16
}

# Function to check if running as root
check_root() {
    if [ "$EUID" -ne 0 ]; then
        print_error "Please run this script as root (use sudo)"
        exit 1
    fi
}

# Function to get user input
get_user_input() {
    if [ -z "$DOMAIN" ]; then
        read -p "Enter your domain (e.g., hosting.example.com): " DOMAIN
    fi
    
    if [ -z "$EMAIL" ]; then
        read -p "Enter your email for SSL certificate: " EMAIL
    fi
    
    if [ -z "$ADMIN_PASSWORD" ]; then
        read -s -p "Enter admin password (leave empty for auto-generated): " ADMIN_PASSWORD
        echo
        if [ -z "$ADMIN_PASSWORD" ]; then
            ADMIN_PASSWORD=$(generate_password)
            print_warning "Auto-generated admin password: $ADMIN_PASSWORD"
        fi
    fi
    
    # Generate database password
    DB_PASSWORD=$(generate_password)
}

# Function to update system
update_system() {
    print_status "Updating system packages..."
    apt update && apt upgrade -y
    print_success "System updated successfully"
}

# Function to install required packages
install_packages() {
    print_status "Installing required packages..."
    apt install -y \
        nginx \
        mysql-server \
        php8.2 \
        php8.2-fpm \
        php8.2-mysql \
        php8.2-xml \
        php8.2-curl \
        php8.2-mbstring \
        php8.2-zip \
        php8.2-gd \
        php8.2-bcmath \
        php8.2-intl \
        php8.2-cli \
        composer \
        git \
        curl \
        wget \
        unzip \
        certbot \
        python3-certbot-nginx \
        supervisor \
        redis-server \
        fail2ban \
        ufw
    print_success "Packages installed successfully"
}

# Function to configure firewall
configure_firewall() {
    print_status "Configuring firewall..."
    ufw --force enable
    ufw allow ssh
    ufw allow 'Nginx Full'
    ufw allow 80
    ufw allow 443
    print_success "Firewall configured successfully"
}

# Function to setup MySQL
setup_mysql() {
    print_status "Setting up MySQL database..."
    
    # Secure MySQL installation
    mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '$DB_PASSWORD';"
    mysql -u root -p$DB_PASSWORD -e "DELETE FROM mysql.user WHERE User='';"
    mysql -u root -p$DB_PASSWORD -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');"
    mysql -u root -p$DB_PASSWORD -e "DROP DATABASE IF EXISTS test;"
    mysql -u root -p$DB_PASSWORD -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';"
    
    # Create application database and user
    mysql -u root -p$DB_PASSWORD -e "CREATE DATABASE $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    mysql -u root -p$DB_PASSWORD -e "CREATE USER '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASSWORD';"
    mysql -u root -p$DB_PASSWORD -e "GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';"
    mysql -u root -p$DB_PASSWORD -e "FLUSH PRIVILEGES;"
    
    print_success "MySQL database setup completed"
}

# Function to clone or update project
setup_project() {
    print_status "Setting up project files..."
    
    # Create project directory
    mkdir -p $PROJECT_DIR
    cd $PROJECT_DIR
    
    # Clone project (adjust repository URL as needed)
    if [ ! -d ".git" ]; then
        print_status "Cloning project repository..."
        # Replace with your actual repository URL
        git clone https://github.com/anshpatel2/minecraft-hosting-billing.git .
    else
        print_status "Updating project repository..."
        git pull origin main
    fi
    
    # Set proper ownership
    chown -R www-data:www-data $PROJECT_DIR
    chmod -R 755 $PROJECT_DIR
    chmod -R 775 $PROJECT_DIR/storage
    chmod -R 775 $PROJECT_DIR/bootstrap/cache
    
    print_success "Project files setup completed"
}

# Function to setup Laravel environment
setup_laravel() {
    print_status "Setting up Laravel environment..."
    
    cd $PROJECT_DIR
    
    # Install PHP dependencies
    sudo -u www-data composer install --no-dev --optimize-autoloader
    
    # Copy environment file
    if [ ! -f ".env" ]; then
        cp .env.example .env
    fi
    
    # Generate application key
    sudo -u www-data php artisan key:generate
    
    # Configure environment variables
    sed -i "s/^APP_NAME=.*/APP_NAME=\"Minecraft Hosting Billing\"/" .env
    sed -i "s/^APP_ENV=.*/APP_ENV=production/" .env
    sed -i "s/^APP_DEBUG=.*/APP_DEBUG=false/" .env
    sed -i "s/^APP_URL=.*/APP_URL=https:\/\/$DOMAIN/" .env
    
    sed -i "s/^DB_CONNECTION=.*/DB_CONNECTION=mysql/" .env
    sed -i "s/^DB_HOST=.*/DB_HOST=127.0.0.1/" .env
    sed -i "s/^DB_PORT=.*/DB_PORT=3306/" .env
    sed -i "s/^DB_DATABASE=.*/DB_DATABASE=$DB_NAME/" .env
    sed -i "s/^DB_USERNAME=.*/DB_USERNAME=$DB_USER/" .env
    sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env
    
    # Configure cache and session
    sed -i "s/^CACHE_DRIVER=.*/CACHE_DRIVER=redis/" .env
    sed -i "s/^SESSION_DRIVER=.*/SESSION_DRIVER=redis/" .env
    sed -i "s/^QUEUE_CONNECTION=.*/QUEUE_CONNECTION=redis/" .env
    
    # Configure mail (you can customize this)
    sed -i "s/^MAIL_MAILER=.*/MAIL_MAILER=smtp/" .env
    sed -i "s/^MAIL_HOST=.*/MAIL_HOST=smtp.gmail.com/" .env
    sed -i "s/^MAIL_PORT=.*/MAIL_PORT=587/" .env
    sed -i "s/^MAIL_USERNAME=.*/MAIL_USERNAME=$EMAIL/" .env
    sed -i "s/^MAIL_ENCRYPTION=.*/MAIL_ENCRYPTION=tls/" .env
    sed -i "s/^MAIL_FROM_ADDRESS=.*/MAIL_FROM_ADDRESS=$EMAIL/" .env
    
    # Run migrations and seeders
    sudo -u www-data php artisan migrate --force
    sudo -u www-data php artisan db:seed --force
    
    # Clear and cache config
    sudo -u www-data php artisan config:cache
    sudo -u www-data php artisan route:cache
    sudo -u www-data php artisan view:cache
    
    print_success "Laravel environment setup completed"
}

# Function to create admin user
create_admin_user() {
    print_status "Creating admin user..."
    
    cd $PROJECT_DIR
    
    # Create admin user using tinker
    sudo -u www-data php artisan tinker --execute="
        \$user = App\Models\User::create([
            'name' => 'Administrator',
            'email' => '$ADMIN_EMAIL',
            'password' => Hash::make('$ADMIN_PASSWORD'),
            'email_verified_at' => now(),
        ]);
        
        \$adminRole = Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        \$user->assignRole(\$adminRole);
        
        echo 'Admin user created successfully';
    "
    
    print_success "Admin user created: $ADMIN_EMAIL"
    print_warning "Admin password: $ADMIN_PASSWORD"
}

# Function to configure Nginx
configure_nginx() {
    print_status "Configuring Nginx..."
    
    # Create Nginx configuration
    cat > /etc/nginx/sites-available/$APP_NAME << EOF
server {
    listen 80;
    server_name $DOMAIN www.$DOMAIN;
    root $PROJECT_DIR/public;
    index index.php index.html index.htm;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static files
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|pdf|txt)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Deny access to sensitive files
    location ~ /(\.env|\.git|composer\.(json|lock)|package\.json|yarn\.lock|webpack\.mix\.js|artisan) {
        deny all;
        return 404;
    }
}
EOF

    # Enable site
    ln -sf /etc/nginx/sites-available/$APP_NAME /etc/nginx/sites-enabled/
    rm -f /etc/nginx/sites-enabled/default
    
    # Test Nginx configuration
    nginx -t
    systemctl reload nginx
    
    print_success "Nginx configured successfully"
}

# Function to setup SSL certificate
setup_ssl() {
    print_status "Setting up SSL certificate..."
    
    # Get SSL certificate
    certbot --nginx -d $DOMAIN -d www.$DOMAIN --non-interactive --agree-tos --email $EMAIL --redirect
    
    # Setup auto-renewal
    systemctl enable certbot.timer
    
    print_success "SSL certificate setup completed"
}

# Function to setup queue worker
setup_queue_worker() {
    print_status "Setting up queue worker..."
    
    # Create supervisor configuration for queue worker
    cat > /etc/supervisor/conf.d/$APP_NAME-worker.conf << EOF
[program:$APP_NAME-worker]
process_name=%(program_name)s_%(process_num)02d
command=php $PROJECT_DIR/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=$PROJECT_DIR/storage/logs/worker.log
stopwaitsecs=3600
EOF

    # Create scheduler cron job
    echo "* * * * * www-data cd $PROJECT_DIR && php artisan schedule:run >> /dev/null 2>&1" > /etc/cron.d/$APP_NAME-scheduler
    
    # Reload supervisor
    supervisorctl reread
    supervisorctl update
    supervisorctl start $APP_NAME-worker:*
    
    print_success "Queue worker setup completed"
}

# Function to setup monitoring and logs
setup_monitoring() {
    print_status "Setting up monitoring and logging..."
    
    # Configure log rotation
    cat > /etc/logrotate.d/$APP_NAME << EOF
$PROJECT_DIR/storage/logs/*.log {
    daily
    missingok
    rotate 52
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
    postrotate
        sudo -u www-data php $PROJECT_DIR/artisan config:cache
    endscript
}
EOF

    # Setup fail2ban for Laravel
    cat > /etc/fail2ban/jail.d/$APP_NAME.conf << EOF
[$APP_NAME]
enabled = true
port = http,https
filter = $APP_NAME
logpath = $PROJECT_DIR/storage/logs/laravel.log
maxretry = 5
bantime = 3600
findtime = 600
EOF

    cat > /etc/fail2ban/filter.d/$APP_NAME.conf << EOF
[Definition]
failregex = .*\[.*\] production.ERROR: Illuminate\\\\Auth\\\\AuthenticationException.*{"ip":"<HOST>".*
            .*\[.*\] production.WARNING: Failed login attempt.*{"ip":"<HOST>".*
ignoreregex =
EOF

    systemctl restart fail2ban
    
    print_success "Monitoring and logging setup completed"
}

# Function to optimize system
optimize_system() {
    print_status "Optimizing system performance..."
    
    # Configure PHP-FPM
    sed -i 's/pm.max_children = .*/pm.max_children = 50/' /etc/php/8.2/fpm/pool.d/www.conf
    sed -i 's/pm.start_servers = .*/pm.start_servers = 5/' /etc/php/8.2/fpm/pool.d/www.conf
    sed -i 's/pm.min_spare_servers = .*/pm.min_spare_servers = 5/' /etc/php/8.2/fpm/pool.d/www.conf
    sed -i 's/pm.max_spare_servers = .*/pm.max_spare_servers = 35/' /etc/php/8.2/fpm/pool.d/www.conf
    
    # Configure MySQL
    cat >> /etc/mysql/mysql.conf.d/mysqld.cnf << EOF

# Custom optimizations
innodb_buffer_pool_size = 256M
innodb_log_file_size = 64M
max_connections = 100
query_cache_type = 1
query_cache_size = 32M
EOF

    # Restart services
    systemctl restart php8.2-fpm
    systemctl restart mysql
    systemctl restart nginx
    systemctl restart redis-server
    
    print_success "System optimization completed"
}

# Function to create backup script
create_backup_script() {
    print_status "Creating backup script..."
    
    cat > /usr/local/bin/$APP_NAME-backup.sh << 'EOF'
#!/bin/bash

BACKUP_DIR="/var/backups/minecraft-hosting"
DATE=$(date +%Y%m%d_%H%M%S)
PROJECT_DIR="/var/www/minecraft-hosting-billing"
DB_NAME="minecraft_hosting"
DB_USER="minecraft_user"

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u $DB_USER -p$DB_PASSWORD $DB_NAME | gzip > $BACKUP_DIR/database_$DATE.sql.gz

# Backup files
tar -czf $BACKUP_DIR/files_$DATE.tar.gz -C $PROJECT_DIR .

# Keep only last 7 days of backups
find $BACKUP_DIR -name "*.gz" -mtime +7 -delete

echo "Backup completed: $DATE"
EOF

    chmod +x /usr/local/bin/$APP_NAME-backup.sh
    
    # Schedule daily backups
    echo "0 2 * * * root /usr/local/bin/$APP_NAME-backup.sh >> /var/log/$APP_NAME-backup.log 2>&1" > /etc/cron.d/$APP_NAME-backup
    
    print_success "Backup script created"
}

# Function to print deployment summary
print_summary() {
    print_success "==================================================================="
    print_success "           DEPLOYMENT COMPLETED SUCCESSFULLY!"
    print_success "==================================================================="
    echo
    print_success "🌐 Application URL: https://$DOMAIN"
    print_success "📧 Admin Email: $ADMIN_EMAIL"
    print_warning "🔑 Admin Password: $ADMIN_PASSWORD"
    print_success "🗄️  Database: $DB_NAME"
    print_success "👤 Database User: $DB_USER"
    print_warning "🔐 Database Password: $DB_PASSWORD"
    echo
    print_status "📂 Project Directory: $PROJECT_DIR"
    print_status "📋 Nginx Config: /etc/nginx/sites-available/$APP_NAME"
    print_status "📊 Supervisor Config: /etc/supervisor/conf.d/$APP_NAME-worker.conf"
    print_status "🔄 Backup Script: /usr/local/bin/$APP_NAME-backup.sh"
    echo
    print_warning "⚠️  IMPORTANT: Save these credentials in a secure location!"
    print_status "📖 Check application logs: tail -f $PROJECT_DIR/storage/logs/laravel.log"
    print_status "🔧 Restart services: systemctl restart nginx php8.2-fpm mysql"
    echo
    print_success "==================================================================="
}

# Main execution
main() {
    print_status "Starting Minecraft Hosting Billing deployment..."
    
    check_root
    get_user_input
    
    # Core system setup
    update_system
    install_packages
    configure_firewall
    
    # Database and application setup
    setup_mysql
    setup_project
    setup_laravel
    create_admin_user
    
    # Web server and SSL
    configure_nginx
    setup_ssl
    
    # Background services
    setup_queue_worker
    setup_monitoring
    
    # Optimization and backup
    optimize_system
    create_backup_script
    
    # Final summary
    print_summary
}

# Run main function
main "$@"
