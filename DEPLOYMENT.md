# 🚀 Minecraft Hosting Billing - One-Command Deployment

Deploy your complete Minecraft hosting billing system to any VPS with a single command!

## ⚡ **ONE-COMMAND INSTALLATION**

```bash
curl -fsSL https://raw.githubusercontent.com/anshpatel2/minecraft-hosting-billing/main/install-one-command.sh | sudo bash -s -- yourdomain.com your@email.com
```

**That's it!** In under 5 minutes you'll have:
- ✅ Complete hosting platform running
- ✅ SSL certificate installed  
- ✅ Admin account created
- ✅ Database configured
- ✅ All services running

## 📋 Prerequisites

- Fresh Ubuntu 20.04+ or Debian 11+ VPS
- Root access to the server
- Domain name pointed to your server's IP
- Email address for SSL certificates

## 🔄 **ONE-COMMAND UPDATES**

Update your platform anytime without losing data:

```bash
sudo minecraft-hosting-update
```

**Update Features:**
- ✅ Zero downtime updates
- ✅ Automatic backups before update
- ✅ Database migration handling
- ✅ Rollback capability
- ✅ Version checking

## 🎯 Alternative Installation Methods

### Manual Installation (Advanced Users)

If you prefer to customize the installation:

```bash
# Clone the repository
git clone https://github.com/anshpatel2/minecraft-hosting-billing.git
cd minecraft-hosting-billing

# Run with custom options
sudo bash install-one-command.sh yourdomain.com your@email.com
```

### Docker Installation

For containerized deployment:

```bash
# Clone and deploy with Docker
git clone https://github.com/anshpatel2/minecraft-hosting-billing.git
cd minecraft-hosting-billing
bash docker-deploy.sh yourdomain.com your@email.com
```

## 🛠️ **Update Management**

### Check for Updates
```bash
sudo minecraft-hosting-update check
```

### Update with Confirmation
```bash
sudo minecraft-hosting-update
```

### Rollback if Needed
```bash
sudo minecraft-hosting-update rollback
```

### System Status
```bash
sudo minecraft-hosting-update status
```

## ⚙️ Configuration

### Custom Configuration

Copy and modify the configuration file:

```bash
cp deploy.conf.example deploy.conf
nano deploy.conf
```

Then run with custom config:

```bash
sudo bash deploy.sh --config deploy.conf
```

### Environment Variables

Key configuration options:

```env
# Application
APP_NAME="Your Hosting Company"
APP_URL=https://yourdomain.com

# Database
DB_NAME=minecraft_hosting
DB_USER=minecraft_user

# Admin User
ADMIN_EMAIL=admin@yourdomain.com
ADMIN_NAME="Administrator"

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_USERNAME=your@email.com
```

## 🔧 Post-Deployment

### Access Your Application

1. **Admin Panel**: `https://yourdomain.com/admin`
2. **User Dashboard**: `https://yourdomain.com/dashboard`
3. **Login**: `https://yourdomain.com/login`

### Default Credentials

The deployment script will create an admin user with:
- **Email**: The email you provided during setup
- **Password**: Auto-generated (displayed at the end of deployment)

### Important Files & Locations

```bash
# Application files
/var/www/minecraft-hosting-billing/

# Nginx configuration
/etc/nginx/sites-available/minecraft-hosting-billing

# Logs
/var/www/minecraft-hosting-billing/storage/logs/

# Backups (if enabled)
/var/backups/minecraft-hosting/

# SSL certificates
/etc/letsencrypt/live/yourdomain.com/
```

## 🛠️ Management Commands

### Application Management

```bash
# Restart all services
sudo systemctl restart nginx php8.2-fpm mysql

# View application logs
tail -f /var/www/minecraft-hosting-billing/storage/logs/laravel.log

# Update application
cd /var/www/minecraft-hosting-billing
git pull origin main
sudo -u www-data composer install --no-dev
sudo -u www-data php artisan migrate
sudo -u www-data php artisan config:cache
```

### Docker Management

```bash
# View containers
docker-compose ps

# View logs
docker-compose logs -f

# Restart services
docker-compose restart

# Update application
docker-compose down
git pull origin main
docker-compose up -d --build
```

### Database Management

```bash
# Access MySQL
mysql -u minecraft_user -p minecraft_hosting

# Create backup
mysqldump -u minecraft_user -p minecraft_hosting > backup.sql

# Restore backup
mysql -u minecraft_user -p minecraft_hosting < backup.sql
```

## 🔒 Security Features

### Included Security Measures

- ✅ **Firewall**: UFW configured with essential ports
- ✅ **SSL/TLS**: Automatic Let's Encrypt certificates
- ✅ **Fail2ban**: Protection against brute force attacks
- ✅ **Secure Headers**: XSS protection, HSTS, etc.
- ✅ **Database Security**: Non-root MySQL user with limited privileges
- ✅ **File Permissions**: Proper ownership and permissions

### Additional Security Recommendations

```bash
# Change SSH port (optional)
sudo nano /etc/ssh/sshd_config
# Change Port 22 to Port 2222

# Disable root login
sudo nano /etc/ssh/sshd_config
# Set PermitRootLogin no

# Restart SSH
sudo systemctl restart ssh
```

## 📊 Monitoring & Maintenance

### Log Monitoring

```bash
# Application logs
tail -f /var/www/minecraft-hosting-billing/storage/logs/laravel.log

# Nginx logs
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log

# MySQL logs
tail -f /var/log/mysql/error.log
```

### Performance Monitoring

```bash
# Check system resources
htop
df -h
free -m

# Check service status
sudo systemctl status nginx
sudo systemctl status php8.2-fpm
sudo systemctl status mysql
```

### Automated Backups

The full deployment includes automated daily backups:

```bash
# Manual backup
sudo /usr/local/bin/minecraft-hosting-billing-backup.sh

# View backup files
ls -la /var/backups/minecraft-hosting/
```

## 🚨 Troubleshooting

### Common Issues

**Issue**: Website shows 502 Bad Gateway
```bash
# Check PHP-FPM status
sudo systemctl status php8.2-fpm
sudo systemctl restart php8.2-fpm
```

**Issue**: Database connection errors
```bash
# Check MySQL status
sudo systemctl status mysql
# Verify database credentials in .env file
```

**Issue**: SSL certificate problems
```bash
# Renew SSL certificate
sudo certbot renew
sudo systemctl reload nginx
```

**Issue**: Permission errors
```bash
# Fix file permissions
cd /var/www/minecraft-hosting-billing
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 storage bootstrap/cache
```

### Getting Help

1. Check the logs first
2. Verify all services are running
3. Check firewall settings
4. Review the configuration files

## 🔄 Updates & Maintenance

### Application Updates

```bash
# Pull latest changes
cd /var/www/minecraft-hosting-billing
git pull origin main

# Update dependencies
sudo -u www-data composer update

# Run migrations
sudo -u www-data php artisan migrate

# Clear caches
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
```

### System Updates

```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Update SSL certificates
sudo certbot renew
```

## 📞 Support

For issues or questions:

1. Check this documentation
2. Review application logs
3. Verify system requirements
4. Check GitHub issues

## 📄 License

This project is licensed under the MIT License.
