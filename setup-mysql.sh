#!/bin/bash

# =================================================================
# MySQL Setup Helper Script
# =================================================================
# This script handles MySQL setup for different Ubuntu/Debian versions
# =================================================================

DB_PASSWORD=$1
MYSQL_ROOT_PASSWORD=$2

if [ -z "$DB_PASSWORD" ]; then
    echo "Usage: $0 <db_password> [mysql_root_password]"
    exit 1
fi

print_status() { echo -e "\033[0;34m🔄 [MYSQL]\033[0m $1"; }
print_success() { echo -e "\033[0;32m✅ [MYSQL]\033[0m $1"; }
print_error() { echo -e "\033[0;31m❌ [MYSQL]\033[0m $1"; }

print_status "Configuring MySQL database..."

# Method 1: Try with sudo mysql (Ubuntu 18.04+)
if sudo mysql -e "SELECT 1;" 2>/dev/null; then
    print_status "Using sudo mysql authentication method"
    sudo mysql -e "CREATE DATABASE IF NOT EXISTS minecraft_hosting CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    sudo mysql -e "CREATE USER IF NOT EXISTS 'minecraft_user'@'localhost' IDENTIFIED BY '$DB_PASSWORD';"
    sudo mysql -e "GRANT ALL PRIVILEGES ON minecraft_hosting.* TO 'minecraft_user'@'localhost';"
    sudo mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '$DB_PASSWORD';"
    sudo mysql -e "FLUSH PRIVILEGES;"
    print_success "Database setup completed with sudo method"
    exit 0
fi

# Method 2: Try with existing root password
if [ -n "$MYSQL_ROOT_PASSWORD" ]; then
    if mysql -u root -p$MYSQL_ROOT_PASSWORD -e "SELECT 1;" 2>/dev/null; then
        print_status "Using existing root password"
        mysql -u root -p$MYSQL_ROOT_PASSWORD -e "CREATE DATABASE IF NOT EXISTS minecraft_hosting CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
        mysql -u root -p$MYSQL_ROOT_PASSWORD -e "CREATE USER IF NOT EXISTS 'minecraft_user'@'localhost' IDENTIFIED BY '$DB_PASSWORD';"
        mysql -u root -p$MYSQL_ROOT_PASSWORD -e "GRANT ALL PRIVILEGES ON minecraft_hosting.* TO 'minecraft_user'@'localhost';"
        mysql -u root -p$MYSQL_ROOT_PASSWORD -e "FLUSH PRIVILEGES;"
        print_success "Database setup completed with existing password"
        exit 0
    fi
fi

# Method 3: Try without password (fresh installation)
if mysql -u root -e "SELECT 1;" 2>/dev/null; then
    print_status "Using passwordless root access"
    mysql -u root -e "CREATE DATABASE IF NOT EXISTS minecraft_hosting CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    mysql -u root -e "CREATE USER IF NOT EXISTS 'minecraft_user'@'localhost' IDENTIFIED BY '$DB_PASSWORD';"
    mysql -u root -e "GRANT ALL PRIVILEGES ON minecraft_hosting.* TO 'minecraft_user'@'localhost';"
    mysql -u root -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '$DB_PASSWORD';"
    mysql -u root -e "FLUSH PRIVILEGES;"
    print_success "Database setup completed with passwordless method"
    exit 0
fi

# Method 4: Reset MySQL root password using safe mode
print_status "Attempting MySQL password reset..."

# Stop MySQL
systemctl stop mysql

# Create temporary init file
TEMP_INIT_FILE="/tmp/mysql_init_$(date +%s).sql"
cat > $TEMP_INIT_FILE << EOF
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '$DB_PASSWORD';
CREATE DATABASE IF NOT EXISTS minecraft_hosting CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'minecraft_user'@'localhost' IDENTIFIED BY '$DB_PASSWORD';
GRANT ALL PRIVILEGES ON minecraft_hosting.* TO 'minecraft_user'@'localhost';
FLUSH PRIVILEGES;
EOF

# Start MySQL with init file
mysqld --init-file=$TEMP_INIT_FILE --user=mysql &
MYSQL_PID=$!
sleep 10

# Kill the temporary process
kill $MYSQL_PID 2>/dev/null || true
sleep 2

# Clean up
rm -f $TEMP_INIT_FILE

# Start MySQL normally
systemctl start mysql
sleep 3

# Test the connection
if mysql -u root -p$DB_PASSWORD -e "SELECT 1;" 2>/dev/null; then
    print_success "MySQL password reset successful"
    # Verify database exists
    mysql -u root -p$DB_PASSWORD -e "USE minecraft_hosting;" 2>/dev/null || {
        mysql -u root -p$DB_PASSWORD -e "CREATE DATABASE minecraft_hosting CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    }
    print_success "Database setup completed with reset method"
    exit 0
else
    print_error "MySQL setup failed. Please run: sudo mysql_secure_installation"
    exit 1
fi
