#!/bin/bash

# =================================================================
# Docker Deployment Script for Minecraft Hosting Billing
# =================================================================
# Usage: bash docker-deploy.sh [domain] [email]
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

# Get parameters
DOMAIN=${1:-"localhost"}
EMAIL=${2:-"admin@example.com"}

# Generate passwords
DB_PASSWORD=$(openssl rand -base64 16 | tr -d "=+/")
DB_ROOT_PASSWORD=$(openssl rand -base64 16 | tr -d "=+/")
ADMIN_PASSWORD=$(openssl rand -base64 16 | tr -d "=+/")

print_status "🐳 Starting Docker deployment..."

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    print_error "Docker is not installed. Installing Docker..."
    curl -fsSL https://get.docker.com -o get-docker.sh
    sh get-docker.sh
    usermod -aG docker $USER
    print_success "Docker installed successfully"
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    print_error "Docker Compose is not installed. Installing..."
    curl -L "https://github.com/docker/compose/releases/download/v2.20.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
    chmod +x /usr/local/bin/docker-compose
    print_success "Docker Compose installed successfully"
fi

# Create environment file
print_status "📝 Creating environment configuration..."
cat > .env << EOF
APP_NAME="Minecraft Hosting Billing"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://$DOMAIN

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=minecraft_hosting
DB_USERNAME=minecraft_user
DB_PASSWORD=$DB_PASSWORD
DB_ROOT_PASSWORD=$DB_ROOT_PASSWORD

BROADCAST_DRIVER=log
CACHE_DRIVER=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=$EMAIL
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=$EMAIL
MAIL_FROM_NAME="\${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="\${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="\${PUSHER_HOST}"
VITE_PUSHER_PORT="\${PUSHER_PORT}"
VITE_PUSHER_SCHEME="\${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="\${PUSHER_APP_CLUSTER}"
EOF

# Create docker directories
mkdir -p docker/nginx docker/php docker/mysql

# Create nginx configuration
cat > docker/nginx/default.conf << 'EOF'
server {
    listen 80;
    server_name _;
    root /var/www/html/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

# Create PHP configuration
cat > docker/php/php.ini << 'EOF'
memory_limit = 256M
upload_max_filesize = 20M
post_max_size = 20M
max_execution_time = 300
date.timezone = UTC
expose_php = Off
EOF

# Create MySQL configuration
cat > docker/mysql/my.cnf << 'EOF'
[mysqld]
innodb_buffer_pool_size = 256M
innodb_log_file_size = 64M
max_connections = 100
query_cache_type = 1
query_cache_size = 32M
EOF

# Create supervisor configuration
cat > docker/supervisord.conf << 'EOF'
[supervisord]
nodaemon=true
user=root

[program:nginx]
command=nginx -g "daemon off;"
autostart=true
autorestart=true
stderr_logfile=/var/log/nginx_err.log
stdout_logfile=/var/log/nginx_out.log

[program:php-fpm]
command=php-fpm
autostart=true
autorestart=true
stderr_logfile=/var/log/php_fpm_err.log
stdout_logfile=/var/log/php_fpm_out.log
EOF

# Build and start containers
print_status "🏗️ Building and starting containers..."
docker-compose up -d --build

# Wait for MySQL to be ready
print_status "⏳ Waiting for MySQL to be ready..."
sleep 30

# Generate application key
print_status "🔑 Generating application key..."
docker-compose exec app php artisan key:generate

# Run migrations
print_status "🗄️ Running database migrations..."
docker-compose exec app php artisan migrate --force

# Create admin user
print_status "👤 Creating admin user..."
docker-compose exec app php artisan tinker --execute="
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

# Cache configuration
print_status "⚡ Caching configuration..."
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

print_success "🎉 Docker deployment completed!"
echo
print_success "🌐 Application URL: http://$DOMAIN (or http://localhost)"
print_success "📧 Admin Email: $EMAIL"
print_warning "🔑 Admin Password: $ADMIN_PASSWORD"
print_warning "🗄️ Database Password: $DB_PASSWORD"
echo
print_status "🐳 Container Status:"
docker-compose ps
echo
print_status "📖 View logs: docker-compose logs -f"
print_status "🛠️ Access container: docker-compose exec app bash"
print_status "🔄 Restart: docker-compose restart"
print_status "🛑 Stop: docker-compose down"
