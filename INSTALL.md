# Minecraft Hosting Billing - One-Command Installation

Deploy a complete Minecraft hosting platform in under 5 minutes with a single command!

## 🚀 Quick Installation

### Option 1: Interactive Installation
```bash
curl -fsSL https://raw.githubusercontent.com/anshpatel2/minecraft-hosting-billing/main/install.sh | sudo bash
```

### Option 2: Automated Installation
```bash
curl -fsSL https://raw.githubusercontent.com/anshpatel2/minecraft-hosting-billing/main/install.sh | sudo bash -s -- yourdomain.com your@email.com
```

## 📋 Requirements

- **VPS/Server**: Ubuntu 18.04, 20.04, 22.04, or 24.04
- **RAM**: Minimum 2GB, Recommended 4GB+
- **Storage**: Minimum 20GB SSD
- **Domain**: A domain name pointing to your server's IP
- **Root Access**: Required for installation

## 🔧 What Gets Installed

✅ **Web Server**: Nginx with SSL (Let's Encrypt)  
✅ **Database**: MySQL 8.0+  
✅ **PHP**: Latest available version (8.1, 8.2, or 8.3)  
✅ **Queue System**: Redis + Supervisor  
✅ **Security**: UFW Firewall + Fail2Ban  
✅ **SSL Certificate**: Automatic Let's Encrypt setup  
✅ **Admin Panel**: Ready-to-use admin interface  

## 📝 After Installation

The script will provide you with:
- 🌐 **Website URL**: `https://yourdomain.com`
- 📧 **Admin Email**: Your provided email
- 🔑 **Admin Password**: Auto-generated secure password
- 🗄️ **Database Password**: Auto-generated secure password

**⚠️ IMPORTANT**: Save the passwords provided at the end of installation!

## 🔄 Management Commands

### Update the system:
```bash
sudo minecraft-hosting-update
```

### View logs:
```bash
tail -f /var/www/minecraft-hosting-billing/storage/logs/laravel.log
```

### Restart services:
```bash
sudo systemctl restart nginx php-fpm mysql redis-server
```

## 🛠️ Manual Configuration (Optional)

After installation, you can customize:
- Payment gateways in admin panel
- Server templates and pricing
- Email settings
- Domain/subdomain settings

## 🆘 Troubleshooting

### If you encounter database errors:
```bash
cd /var/www/minecraft-hosting-billing
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan migrate --force
```

### If SSL certificate fails:
```bash
sudo certbot --nginx -d yourdomain.com --email your@email.com
```

### If website shows 502/504 errors:
```bash
sudo systemctl restart nginx php-fpm
```

## 📞 Support

- **Documentation**: [Full documentation](https://github.com/anshpatel2/minecraft-hosting-billing/wiki)
- **Issues**: [GitHub Issues](https://github.com/anshpatel2/minecraft-hosting-billing/issues)
- **Community**: [Discord Server](https://discord.gg/minecraft-hosting)

## 🔒 Security Features

- Automatic security updates
- Firewall configuration (UFW)
- Fail2Ban protection
- SSL/TLS encryption
- Secure password generation
- Database user isolation

---

**Ready to start hosting Minecraft servers? Run the installation command above! 🚀**
