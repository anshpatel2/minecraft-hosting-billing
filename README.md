# 🚀 Minecraft Hosting Billing Platform

Deploy a complete, production-ready Minecraft server hosting and billing platform in under 5 minutes with a single command!

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.1+-brightgreen.svg)
![Laravel](https://img.shields.io/badge/Laravel-10+-red.svg)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)

## ⚡ **One-Command Installation**

### **Interactive Installation:**
```bash
curl -fsSL https://raw.githubusercontent.com/anshpatel2/minecraft-hosting-billing/main/install.sh | sudo bash
```

### **Automated Installation:**
```bash
curl -fsSL https://raw.githubusercontent.com/anshpatel2/minecraft-hosting-billing/main/install.sh | sudo bash -s -- yourdomain.com your@email.com
```

**That's it!** Your complete Minecraft hosting platform will be ready at `https://yourdomain.com` in under 5 minutes.

## 🎯 **What You Get Instantly**

✅ **Complete Hosting Platform** - Ready to sell Minecraft servers  
✅ **Admin Dashboard** - Manage users, orders, servers, and billing  
✅ **Customer Portal** - Self-service account management  
✅ **Payment Integration Ready** - Stripe, PayPal integration support  
✅ **SSL Certificate** - Automatic HTTPS setup with Let's Encrypt  
✅ **Database & Caching** - MySQL + Redis configured and optimized  
✅ **Background Workers** - Queue processing for server provisioning  
✅ **Security Hardened** - Firewall, fail2ban, secure headers  
✅ **Smart PHP Detection** - Automatically uses PHP 8.1, 8.2, or 8.3  
✅ **Robust Error Handling** - Installation succeeds on first try  

## 🖥️ **System Requirements**

- **Operating System**: Ubuntu 18.04, 20.04, 22.04, or 24.04
- **RAM**: 2GB minimum (4GB recommended for production)
- **Storage**: 20GB minimum (SSD recommended)
- **Network**: Public IP with ports 80/443 accessible
- **Domain**: DNS A record pointing to your server's IP
- **Email**: Valid email address for SSL certificates

## 🚀 **Quick Start Guide**

### 1. **Get a VPS**
- Any Ubuntu VPS from DigitalOcean, Linode, AWS, Vultr, etc.
- Point your domain's DNS A record to the server's IP
- SSH into your server as root

### 2. **Run the Installation Command**
```bash
curl -fsSL https://raw.githubusercontent.com/anshpatel2/minecraft-hosting-billing/main/install.sh | sudo bash -s -- yourdomain.com your@email.com
```

### 3. **Wait 5 Minutes**
The script will automatically:
- Install and configure all required packages
- Set up MySQL database with secure passwords
- Configure Nginx with SSL certificate
- Deploy the Laravel application
- Create admin user and provide login credentials

### 4. **Access Your Platform**
- Visit `https://yourdomain.com` 
- Login with the provided admin credentials
- Start configuring your hosting packages!

## 🛠️ **Post-Installation**

After installation, you'll receive:
- 🌐 **Website URL**: `https://yourdomain.com`
- 📧 **Admin Email**: Your provided email
- 🔑 **Admin Password**: Auto-generated secure password
- 🗄️ **Database Password**: Auto-generated secure password
- 🔑 **MySQL Root Password**: Auto-generated secure password

**⚠️ Important**: Save all passwords shown at the end of installation!

## 🔄 **Management Commands**

### **Update Your Platform:**
```bash
sudo minecraft-hosting-update
```

### **View Application Logs:**
```bash
tail -f /var/www/minecraft-hosting-billing/storage/logs/laravel.log
```

### **Restart Services:**
```bash
sudo systemctl restart nginx php-fpm mysql redis-server
```

### **Check Service Status:**
```bash
sudo systemctl status nginx
sudo systemctl status mysql
sudo systemctl status redis-server
```

### 3. **Access Your Platform**
- **Website**: `https://yourdomain.com`
- **Admin Panel**: `https://yourdomain.com/admin`
- **User Dashboard**: `https://yourdomain.com/dashboard`

The installer will display your admin credentials at the end - save them securely!

## 🔄 **Easy Updates**

Keep your platform updated with zero data loss:

```bash
# Update to latest version
sudo minecraft-hosting-update

# Check system status
sudo minecraft-hosting-update status

# Rollback if needed (just in case)
sudo minecraft-hosting-update rollback
```

## 🎛️ **Platform Features**

### 👨‍💼 **Admin Features**
- **📊 Dashboard**: Revenue overview, order analytics, system health
- **👥 User Management**: Create, edit, suspend, and manage customers
- **🖥️ Server Management**: Provision, configure, and monitor servers
- **📦 Plan Management**: Create hosting packages with custom specs
- **💳 Order Processing**: Handle customer orders and billing cycles
- **💰 Payment Tracking**: Monitor payments, invoices, and revenue
- **📈 Analytics**: Detailed reports on sales and server usage
- **🔧 System Monitoring**: Server health, performance metrics
- **📞 Support System**: Ticket management and customer support

### 🎮 **Customer Features**
- **🏠 Account Dashboard**: Overview of services and account status
- **🎛️ Server Control Panel**: Start, stop, restart Minecraft servers
- **📁 File Manager**: Upload plugins, worlds, configurations
- **💾 Backup System**: Automated and manual server backups
- **💳 Billing Portal**: View invoices, payment history, upgrade plans
- **🎫 Support Tickets**: Submit and track support requests
- **📊 Usage Statistics**: Monitor server performance and resources
- **👥 Sub-user Management**: Grant access to team members

### ⚙️ **Technical Features**
- **🔄 Queue System**: Background processing for heavy operations
- **⚡ Redis Caching**: Lightning-fast performance optimization
- **🔒 Role-based Permissions**: Secure access control system
- **📦 Automated Backups**: Database and file backups with retention
- **📊 Health Monitoring**: Application and system monitoring
- **🔄 Smart Updates**: Version control with automatic rollback
- **🌐 Multi-server Support**: Manage multiple Minecraft servers
- **🔌 API Ready**: RESTful API for integrations

## 🏗️ **Architecture Overview**

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│     Nginx       │    │   Laravel App   │    │     MySQL       │
│  (Web Server)   │◄──►│  (PHP 8.2)     │◄──►│   (Database)    │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         │              ┌─────────────────┐              │
         │              │     Redis       │              │
         └──────────────►│   (Caching)     │◄─────────────┘
                        └─────────────────┘
                                 │
                        ┌─────────────────┐
                        │ Queue Workers   │
                        │ (Background)    │
                        └─────────────────┘
```

## 💻 **Installation Methods**

### Method 1: One-Command (Recommended)
```bash
curl -fsSL https://raw.githubusercontent.com/anshpatel2/minecraft-hosting-billing/main/install-one-command.sh | sudo bash -s -- yourdomain.com your@email.com
```

### Method 2: Manual Installation
```bash
# Clone the repository
git clone https://github.com/anshpatel2/minecraft-hosting-billing.git
cd minecraft-hosting-billing

# Run the installer
sudo bash install-one-command.sh yourdomain.com your@email.com
```

## 🔧 **Post-Installation Setup**

### Initial Configuration
1. **Login to Admin Panel**: Use the credentials provided during installation
2. **Configure Payment Methods**: Set up Stripe, PayPal, or other payment gateways
3. **Create Server Plans**: Define hosting packages (RAM, storage, player slots)
4. **Setup Email**: Configure SMTP for customer notifications
5. **Customize Branding**: Update logo, colors, and company information

### Server Integration
1. **Add Minecraft Servers**: Connect your game servers to the platform
2. **Configure Pterodactyl**: Integrate with Pterodactyl panel (optional)
3. **Setup Backups**: Configure automated backup destinations
4. **Monitor Resources**: Set up resource monitoring and alerts

## 🛠️ **Management Commands**

### Application Management
```bash
# View application logs
tail -f /var/www/minecraft-hosting-billing/storage/logs/laravel.log

# Restart all services
sudo systemctl restart nginx php8.2-fpm mysql redis-server

# Clear application cache
cd /var/www/minecraft-hosting-billing
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan config:cache

# Run database migrations
sudo -u www-data php artisan migrate

# Queue worker status
sudo supervisorctl status minecraft-hosting-worker:*
```

### System Maintenance
```bash
# Check service status
sudo systemctl status nginx php8.2-fpm mysql redis-server

# Monitor system resources
htop
df -h
free -m

# View recent backups
ls -la /var/backups/minecraft-hosting/

# SSL certificate renewal (automatic)
sudo certbot renew --dry-run
```

## 🔒 **Security Features**

### Built-in Security
- **🔥 Firewall**: UFW configured with minimal required ports
- **🔒 SSL/TLS**: Automatic Let's Encrypt certificates with auto-renewal
- **🛡️ Fail2ban**: Protection against brute force attacks
- **🔐 Secure Headers**: XSS, CSRF, and clickjacking protection
- **👤 Database Security**: Non-root MySQL user with limited privileges
- **📁 File Permissions**: Proper ownership and permission settings
- **🔍 Input Validation**: All user inputs sanitized and validated

## 📊 **Performance Optimization**

The platform comes pre-optimized with:

- **PHP OPcache**: Bytecode caching for faster execution
- **Redis Caching**: Database query and session caching
- **Gzip Compression**: Reduced bandwidth usage
- **Static File Caching**: Browser caching for assets
- **Database Indexing**: Optimized database queries
- **Queue Workers**: Background processing for heavy tasks
- **CDN Ready**: Easy integration with CloudFlare or AWS CloudFront

## 🆘 **Troubleshooting**

### Quick Fix Scripts

**🔧 Complete Troubleshooter (Recommended)**
```bash
# Download and run the complete troubleshooter
curl -fsSL https://raw.githubusercontent.com/anshpatel2/minecraft-hosting-billing/main/troubleshoot.sh | sudo bash
```

**🔑 Laravel Key Generation Error Fix**
```bash
# If you get "file_put_contents(.env): Permission denied"
curl -fsSL https://raw.githubusercontent.com/anshpatel2/minecraft-hosting-billing/main/fix-laravel-key.sh | sudo bash
```

### Common Issues

**🚫 502 Bad Gateway Error**
```bash
# Check PHP-FPM status
sudo systemctl status php8.2-fpm
sudo systemctl restart php8.2-fpm
```

**🗄️ Database Connection Error**
```bash
# Check MySQL status
sudo systemctl status mysql

# Verify credentials in /var/www/minecraft-hosting-billing/.env
```

**📚 For detailed MySQL troubleshooting**: See [MYSQL-TROUBLESHOOTING.md](MYSQL-TROUBLESHOOTING.md)

**🔒 SSL Certificate Issues**
```bash
# Renew SSL certificate
sudo certbot renew
sudo systemctl reload nginx
```

**📁 Permission Errors**
```bash
cd /var/www/minecraft-hosting-billing
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 storage bootstrap/cache
```

### Getting Help

1. **📖 Check Logs**: Always check application and system logs first
2. **🔍 Verify Services**: Ensure all services (nginx, php, mysql, redis) are running
3. **🌐 Check Documentation**: Review the [deployment documentation](DEPLOYMENT.md)
4. **💬 Community Support**: Open an issue on GitHub for community help

## 🔄 **Backup & Recovery**

### Automated Backups
The platform automatically creates daily backups including:
- **Database**: Complete MySQL database dump
- **Files**: Application files, uploads, configurations
- **Logs**: System and application logs

### Manual Backup
```bash
# Create manual backup
sudo /usr/local/bin/minecraft-hosting-backup

# View backups
ls -la /var/backups/minecraft-hosting/

# Restore from backup (if needed)
sudo minecraft-hosting-update rollback
```

## 🔄 **Update Process**

The smart update system ensures:
- ✅ **Zero Downtime**: Maintenance mode during updates
- ✅ **Automatic Backups**: Created before each update
- ✅ **Safe Migrations**: Database updates without data loss
- ✅ **Rollback Capability**: Automatic revert if issues occur
- ✅ **Version Tracking**: Keep track of installed versions

## 📈 **Scaling Your Business**

### Growing Your Hosting Business
1. **Marketing Integration**: Connect with social media and advertising platforms
2. **Affiliate System**: Set up referral programs for customers
3. **Multi-server Management**: Scale across multiple physical servers
4. **Custom Integrations**: Use the API to integrate with external tools
5. **Analytics Dashboard**: Monitor business metrics and growth

### Technical Scaling
- **Load Balancing**: Deploy multiple web servers behind a load balancer
- **Database Clustering**: Set up MySQL replication for high availability
- **CDN Integration**: Use CloudFlare or AWS CloudFront for global performance
- **Monitoring**: Implement comprehensive monitoring with Nagios or Zabbix

## 🤝 **Contributing**

We welcome contributions! Please:

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Commit your changes: `git commit -m 'Add amazing feature'`
4. Push to the branch: `git push origin feature/amazing-feature`
5. Open a Pull Request

## 📄 **License**

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 💬 **Support & Community**

- **🐛 Bug Reports**: [GitHub Issues](https://github.com/anshpatel2/minecraft-hosting-billing/issues)
- **💡 Feature Requests**: [GitHub Discussions](https://github.com/anshpatel2/minecraft-hosting-billing/discussions)
- **📖 Documentation**: [Full Documentation](DEPLOYMENT.md)
- **💬 Community**: Join our Discord server for real-time support

## ⭐ **Show Your Support**

If this project helps you build your Minecraft hosting business, please give it a star! ⭐

It helps others discover the project and motivates us to keep improving it.

## 🙏 **Acknowledgments**

- Laravel Framework for the robust foundation
- The Minecraft community for inspiration
- All contributors who help improve this project
- Open source projects that make this possible

---

**🎮 Built with ❤️ for the Minecraft hosting community**

### Quick Links
- [📚 Full Documentation](DEPLOYMENT.md)
- [🚀 One-Command Install](install-one-command.sh)
- [🔄 Update System](update-system.sh)
- [🐳 Docker Setup](docker-compose.yml)
- [⚙️ Configuration Options](deploy.conf.example)
