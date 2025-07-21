#!/bin/bash

# =================================================================
# Minecraft Hosting Billing - Smart Update System
# =================================================================
# This script handles updates while preserving all user data
# Usage: sudo minecraft-hosting-update [--check-only] [--rollback]
# =================================================================

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
NC='\033[0m'

print_status() { echo -e "${BLUE}🔄 [UPDATE]${NC} $1"; }
print_success() { echo -e "${GREEN}✅ [SUCCESS]${NC} $1"; }
print_warning() { echo -e "${YELLOW}⚠️  [WARNING]${NC} $1"; }
print_error() { echo -e "${RED}❌ [ERROR]${NC} $1"; }
print_header() { echo -e "${PURPLE}🚀 $1${NC}"; }

# Configuration
PROJECT_DIR="/var/www/minecraft-hosting-billing"
BACKUP_DIR="/var/backups/minecraft-hosting"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
GITHUB_API="https://api.github.com/repos/anshpatel2/minecraft-hosting-billing"

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    print_error "Please run as root: sudo minecraft-hosting-update"
    exit 1
fi

# Create backup directories
mkdir -p $BACKUP_DIR/{database,files,logs}

# Function to get current version
get_current_version() {
    if [ -f "$PROJECT_DIR/.installation-info" ]; then
        grep "VERSION=" $PROJECT_DIR/.installation-info | cut -d '=' -f2 || echo "unknown"
    else
        echo "unknown"
    fi
}

# Function to get latest version from GitHub
get_latest_version() {
    curl -s $GITHUB_API/releases/latest | grep -Po '"tag_name": "\K[^"]*' 2>/dev/null || echo "main"
}

# Function to check for updates
check_for_updates() {
    print_header "Checking for updates..."
    
    CURRENT_VERSION=$(get_current_version)
    LATEST_VERSION=$(get_latest_version)
    
    print_status "Current version: $CURRENT_VERSION"
    print_status "Latest version: $LATEST_VERSION"
    
    if [ "$CURRENT_VERSION" = "$LATEST_VERSION" ]; then
        print_success "You are running the latest version!"
        return 1
    else
        print_warning "Update available: $CURRENT_VERSION → $LATEST_VERSION"
        return 0
    fi
}

# Function to backup database
backup_database() {
    print_status "Creating database backup..."
    
    if [ -f "$PROJECT_DIR/.env" ]; then
        DB_PASSWORD=$(grep "^DB_PASSWORD=" $PROJECT_DIR/.env | cut -d '=' -f2- | sed 's/^"//' | sed 's/"$//')
        DB_NAME=$(grep "^DB_DATABASE=" $PROJECT_DIR/.env | cut -d '=' -f2- | sed 's/^"//' | sed 's/"$//')
        DB_USER=$(grep "^DB_USERNAME=" $PROJECT_DIR/.env | cut -d '=' -f2- | sed 's/^"//' | sed 's/"$//')
        
        mysqldump -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" | gzip > "$BACKUP_DIR/database/backup_$TIMESTAMP.sql.gz"
        print_success "Database backed up to: backup_$TIMESTAMP.sql.gz"
    else
        print_error "Could not find .env file for database backup"
        exit 1
    fi
}

# Function to backup important files
backup_files() {
    print_status "Creating files backup..."
    
    # Backup critical files and directories
    tar -czf "$BACKUP_DIR/files/backup_$TIMESTAMP.tar.gz" \
        -C "$PROJECT_DIR" \
        .env \
        storage/app \
        storage/logs \
        public/uploads \
        .installation-info \
        2>/dev/null || true
    
    print_success "Files backed up to: backup_$TIMESTAMP.tar.gz"
}

# Function to perform the update
perform_update() {
    print_header "Starting update process..."
    
    cd "$PROJECT_DIR"
    
    # Enter maintenance mode
    print_status "Entering maintenance mode..."
    sudo -u www-data php artisan down --message="System is being updated. Please check back in a few minutes." || true
    
    # Update code from GitHub
    print_status "Fetching latest code..."
    git fetch origin
    
    # Store current commit for potential rollback
    CURRENT_COMMIT=$(git rev-parse HEAD)
    echo "PREVIOUS_COMMIT=$CURRENT_COMMIT" >> "$BACKUP_DIR/files/rollback_info_$TIMESTAMP.txt"
    
    # Update to latest version
    git reset --hard origin/main
    
    # Update dependencies
    print_status "Updating dependencies..."
    sudo -u www-data composer install --no-dev --optimize-autoloader --quiet
    
    # Run database migrations (safe - won't lose data)
    print_status "Running database migrations..."
    sudo -u www-data php artisan migrate --force
    
    # Update configuration cache
    print_status "Updating configuration..."
    sudo -u www-data php artisan config:cache
    sudo -u www-data php artisan route:cache
    sudo -u www-data php artisan view:cache
    
    # Fix file permissions
    print_status "Fixing permissions..."
    chown -R www-data:www-data "$PROJECT_DIR"
    chmod -R 755 "$PROJECT_DIR"
    chmod -R 775 "$PROJECT_DIR/storage" "$PROJECT_DIR/bootstrap/cache"
    
    # Update version info
    LATEST_VERSION=$(get_latest_version)
    cat > "$PROJECT_DIR/.installation-info" << EOF
INSTALLATION_DATE=$(grep "INSTALLATION_DATE=" $PROJECT_DIR/.installation-info 2>/dev/null | cut -d '=' -f2- || date)
INSTALLATION_ID=$(grep "INSTALLATION_ID=" $PROJECT_DIR/.installation-info 2>/dev/null | cut -d '=' -f2- || date +%s)
DOMAIN=$(grep "DOMAIN=" $PROJECT_DIR/.installation-info 2>/dev/null | cut -d '=' -f2- || "unknown")
EMAIL=$(grep "EMAIL=" $PROJECT_DIR/.installation-info 2>/dev/null | cut -d '=' -f2- || "unknown")
VERSION=$LATEST_VERSION
LAST_UPDATE=$(date)
PREVIOUS_BACKUP=$TIMESTAMP
EOF
    
    # Restart services
    print_status "Restarting services..."
    systemctl restart php8.2-fpm
    supervisorctl restart minecraft-hosting-worker:* 2>/dev/null || true
    
    # Exit maintenance mode
    print_status "Exiting maintenance mode..."
    sudo -u www-data php artisan up
    
    print_success "Update completed successfully!"
}

# Function to rollback to previous version
rollback() {
    print_header "Rolling back to previous version..."
    
    # Find latest backup
    LATEST_BACKUP=$(ls -t $BACKUP_DIR/files/backup_*.tar.gz 2>/dev/null | head -1)
    LATEST_DB_BACKUP=$(ls -t $BACKUP_DIR/database/backup_*.sql.gz 2>/dev/null | head -1)
    
    if [ -z "$LATEST_BACKUP" ]; then
        print_error "No backup found for rollback!"
        exit 1
    fi
    
    print_warning "This will restore your system to the previous backup."
    read -p "Are you sure you want to continue? (y/N): " -n 1 -r
    echo
    
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        print_status "Rollback cancelled."
        exit 0
    fi
    
    cd "$PROJECT_DIR"
    
    # Enter maintenance mode
    sudo -u www-data php artisan down --message="System is being restored from backup." || true
    
    # Restore files
    print_status "Restoring files from backup..."
    tar -xzf "$LATEST_BACKUP" -C "$PROJECT_DIR"
    
    # Restore database if available
    if [ -n "$LATEST_DB_BACKUP" ]; then
        print_status "Restoring database from backup..."
        DB_PASSWORD=$(grep "^DB_PASSWORD=" $PROJECT_DIR/.env | cut -d '=' -f2- | sed 's/^"//' | sed 's/"$//')
        DB_NAME=$(grep "^DB_DATABASE=" $PROJECT_DIR/.env | cut -d '=' -f2- | sed 's/^"//' | sed 's/"$//')
        DB_USER=$(grep "^DB_USERNAME=" $PROJECT_DIR/.env | cut -d '=' -f2- | sed 's/^"//' | sed 's/"$//')
        
        gunzip -c "$LATEST_DB_BACKUP" | mysql -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME"
    fi
    
    # Fix permissions
    chown -R www-data:www-data "$PROJECT_DIR"
    chmod -R 755 "$PROJECT_DIR"
    chmod -R 775 "$PROJECT_DIR/storage" "$PROJECT_DIR/bootstrap/cache"
    
    # Clear caches
    sudo -u www-data php artisan config:cache
    sudo -u www-data php artisan route:cache
    sudo -u www-data php artisan view:cache
    
    # Restart services
    systemctl restart php8.2-fpm
    supervisorctl restart minecraft-hosting-worker:* 2>/dev/null || true
    
    # Exit maintenance mode
    sudo -u www-data php artisan up
    
    print_success "Rollback completed successfully!"
}

# Function to show system status
show_status() {
    print_header "System Status"
    
    echo -e "${BLUE}📊 Application Information:${NC}"
    echo "  Current Version: $(get_current_version)"
    echo "  Installation Directory: $PROJECT_DIR"
    echo "  Last Update: $(grep "LAST_UPDATE=" $PROJECT_DIR/.installation-info 2>/dev/null | cut -d '=' -f2- || 'Unknown')"
    
    echo -e "\n${BLUE}🔧 Service Status:${NC}"
    systemctl is-active nginx && echo "  ✅ Nginx: Running" || echo "  ❌ Nginx: Stopped"
    systemctl is-active php8.2-fpm && echo "  ✅ PHP-FPM: Running" || echo "  ❌ PHP-FPM: Stopped"
    systemctl is-active mysql && echo "  ✅ MySQL: Running" || echo "  ❌ MySQL: Stopped"
    systemctl is-active redis-server && echo "  ✅ Redis: Running" || echo "  ❌ Redis: Stopped"
    
    echo -e "\n${BLUE}💾 Recent Backups:${NC}"
    ls -lt $BACKUP_DIR/files/backup_*.tar.gz 2>/dev/null | head -3 | while read line; do
        echo "  📦 $(echo $line | awk '{print $9}' | xargs basename)"
    done
    
    echo -e "\n${BLUE}📈 System Resources:${NC}"
    echo "  Disk Usage: $(df -h $PROJECT_DIR | tail -1 | awk '{print $5}')"
    echo "  Memory Usage: $(free -m | grep Mem | awk '{printf "%.1f%%", $3/$2 * 100.0}')"
}

# Main execution
case "${1:-update}" in
    "--check-only"|"check")
        check_for_updates
        exit $?
        ;;
    "--rollback"|"rollback")
        rollback
        ;;
    "--status"|"status")
        show_status
        ;;
    "update"|"")
        # Check for updates first
        if check_for_updates; then
            echo
            read -p "🔄 Would you like to update now? (Y/n): " -n 1 -r
            echo
            
            if [[ $REPLY =~ ^[Nn]$ ]]; then
                print_status "Update cancelled."
                exit 0
            fi
            
            backup_database
            backup_files
            perform_update
            
            echo
            print_success "🎉 Update completed successfully!"
            print_status "📊 Run 'sudo minecraft-hosting-update status' to check system status"
            print_status "🔙 Run 'sudo minecraft-hosting-update rollback' if you need to revert"
        fi
        ;;
    "--help"|"help"|"-h")
        echo "Minecraft Hosting Billing Update Tool"
        echo
        echo "Usage: sudo minecraft-hosting-update [command]"
        echo
        echo "Commands:"
        echo "  update          Update to the latest version (default)"
        echo "  check           Check for updates without installing"
        echo "  rollback        Rollback to previous version"
        echo "  status          Show system status"
        echo "  help            Show this help message"
        echo
        ;;
    *)
        print_error "Unknown command: $1"
        print_status "Run 'sudo minecraft-hosting-update help' for usage information"
        exit 1
        ;;
esac
