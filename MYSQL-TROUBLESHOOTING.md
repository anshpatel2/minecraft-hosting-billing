# MySQL Troubleshooting Guide

This guide helps resolve common MySQL issues during installation.

## Common MySQL Authentication Errors

### Error: Access denied for user 'root'@'localhost'

This happens when MySQL has authentication issues. Here are the solutions:

#### Solution 1: Ubuntu 20.04+ (Recommended)
```bash
# Use sudo to access MySQL
sudo mysql -e "CREATE DATABASE minecraft_hosting CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER 'minecraft_user'@'localhost' IDENTIFIED BY 'your_password';"
sudo mysql -e "GRANT ALL PRIVILEGES ON minecraft_hosting.* TO 'minecraft_user'@'localhost';"
sudo mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'your_password';"
sudo mysql -e "FLUSH PRIVILEGES;"
```

#### Solution 2: Reset MySQL Root Password
```bash
# Stop MySQL
sudo systemctl stop mysql

# Create password reset file
sudo tee /tmp/mysql_reset.sql << EOF
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'your_new_password';
FLUSH PRIVILEGES;
EOF

# Start MySQL with reset file
sudo mysqld --init-file=/tmp/mysql_reset.sql --user=mysql &
sleep 10

# Kill the process and clean up
sudo pkill mysqld
sudo rm /tmp/mysql_reset.sql

# Start MySQL normally
sudo systemctl start mysql

# Test connection
mysql -u root -p
```

#### Solution 3: Secure Installation
```bash
# Run MySQL secure installation
sudo mysql_secure_installation

# Follow the prompts to set root password
# Then create the database manually
mysql -u root -p -e "CREATE DATABASE minecraft_hosting CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p -e "CREATE USER 'minecraft_user'@'localhost' IDENTIFIED BY 'your_password';"
mysql -u root -p -e "GRANT ALL PRIVILEGES ON minecraft_hosting.* TO 'minecraft_user'@'localhost';"
mysql -u root -p -e "FLUSH PRIVILEGES;"
```

#### Solution 4: Fresh MySQL Installation
```bash
# Completely remove and reinstall MySQL
sudo systemctl stop mysql
sudo apt remove --purge mysql-server mysql-client mysql-common mysql-server-core-* mysql-client-core-*
sudo rm -rf /etc/mysql /var/lib/mysql
sudo apt autoremove
sudo apt autoclean

# Reinstall
sudo apt update
sudo apt install mysql-server

# Then run the installer again
```

## MySQL Configuration Files

### Check MySQL Authentication Method
```bash
sudo mysql -e "SELECT user,authentication_string,plugin,host FROM mysql.user WHERE user='root';"
```

### Important Configuration Files
- `/etc/mysql/mysql.conf.d/mysqld.cnf` - Main configuration
- `/etc/mysql/debian.cnf` - Debian maintenance credentials
- `/var/log/mysql/error.log` - Error logs

## Manual Database Setup

If the automated setup fails, create the database manually:

```bash
# Access MySQL as root
sudo mysql
# OR
mysql -u root -p

# Create database and user
CREATE DATABASE minecraft_hosting CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'minecraft_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON minecraft_hosting.* TO 'minecraft_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Then update your `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=minecraft_hosting
DB_USERNAME=minecraft_user
DB_PASSWORD=your_secure_password
```

## Test Database Connection

Test if the database connection works:
```bash
cd /var/www/minecraft-hosting-billing
sudo -u www-data php artisan tinker --execute="echo 'Database connection: ' . (DB::connection()->getPdo() ? 'SUCCESS' : 'FAILED') . PHP_EOL;"
```

## Common Issues and Fixes

### Issue: Plugin 'auth_socket' cannot be loaded
```bash
sudo mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'password';"
```

### Issue: Can't connect to local MySQL server
```bash
sudo systemctl status mysql
sudo systemctl start mysql
sudo systemctl enable mysql
```

### Issue: Access denied for user 'debian-sys-maint'
```bash
sudo mysql -e "GRANT ALL PRIVILEGES ON *.* TO 'debian-sys-maint'@'localhost' IDENTIFIED BY PASSWORD (SELECT password FROM mysql.user WHERE user='debian-sys-maint');"
```

## Recovery Commands

If you need to recover from a failed installation:

```bash
# Check MySQL status
sudo systemctl status mysql

# View MySQL logs
sudo tail -f /var/log/mysql/error.log

# Reset MySQL completely
sudo mysql_install_db --user=mysql --basedir=/usr --datadir=/var/lib/mysql

# Start MySQL in safe mode
sudo mysqld_safe --skip-grant-tables --skip-networking &
```

## Contact Support

If you continue to have issues:
1. Check the error logs: `sudo tail -f /var/log/mysql/error.log`
2. Save the error output
3. Open an issue on GitHub with the complete error message
4. Include your Ubuntu/Debian version: `lsb_release -a`
