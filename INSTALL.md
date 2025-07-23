# 🚀 Minecraft Hosting Billing - Installation Guide

Deploy your complete Minecraft hosting platform in just **ONE COMMAND**!

## ⚡ Quick Installation

### **Option 1: Automated (Recommended)**
```bash
curl -fsSL https://raw.githubusercontent.com/anshpatel2/minecraft-hosting-billing/main/install.sh | sudo bash -s -- yourdomain.com your@email.com
```
*Replace `yourdomain.com` with your actual domain and `your@email.com` with your email*

### **Option 2: Interactive**
```bash
curl -fsSL https://raw.githubusercontent.com/anshpatel2/minecraft-hosting-billing/main/install.sh | sudo bash
```
*The script will ask for your domain and email*

## �️ **Server Requirements**

| Requirement | Minimum | Recommended |
|-------------|---------|-------------|
| **OS** | Ubuntu 18.04+ | Ubuntu 22.04 LTS |
| **RAM** | 2GB | 4GB+ |
| **Storage** | 20GB | 50GB SSD |
| **CPU** | 1 vCPU | 2+ vCPU |
| **Network** | 100 Mbps | 1 Gbps |

## 🔧 **What Gets Installed**

✅ **Web Server**: Nginx with optimized configuration  
✅ **Database**: MySQL 8.0+ with secure setup  
✅ **PHP**: Latest available (8.1, 8.2, or 8.3) with FPM  
✅ **Caching**: Redis for sessions and application cache  
✅ **Queue System**: Supervisor for background job processing  
✅ **Security**: UFW Firewall + Fail2Ban protection  
✅ **SSL Certificate**: Let's Encrypt with auto-renewal  
✅ **Monitoring**: Log rotation and system monitoring  

## � **Installation Process**

The installation script will:

1. **System Setup** (2-3 mins)
   - Update Ubuntu packages
   - Add PHP repository
   - Install all required packages
   - Configure firewall

2. **Database Setup** (1 min)
   - Install and secure MySQL
   - Create application database
   - Set up database user with proper permissions

3. **Application Setup** (1-2 mins)
   - Clone the latest code from GitHub
   - Install PHP dependencies with Composer
   - Configure Laravel environment
   - Run database migrations and seeders

4. **Web Server Setup** (1 min)
   - Configure Nginx virtual host
   - Set up SSL certificate with Let's Encrypt
   - Configure queue worker with Supervisor

5. **Final Setup** (30 seconds)
   - Create admin user
   - Optimize application caches
   - Start all services
   - Display login credentials

## 📋 **Pre-Installation Checklist**

Before running the installation:

- [ ] **VPS Ready**: Fresh Ubuntu server with root access
- [ ] **Domain Configured**: DNS A record pointing to server IP
- [ ] **Email Available**: Valid email for SSL certificates
- [ ] **Ports Open**: Ensure ports 22, 80, and 443 are accessible

## � **Post-Installation Security**

The installation automatically configures:
- Strong firewall rules (only SSH, HTTP, HTTPS allowed)
- Fail2Ban protection against brute force attacks
- Secure MySQL configuration with random passwords
- SSL/TLS encryption for all web traffic
- File permissions following Laravel best practices

## 🆘 **Troubleshooting**

### **If installation fails:**

1. **Check system compatibility:**
   ```bash
   lsb_release -a  # Should show Ubuntu 18.04+
   free -h         # Should show 2GB+ RAM
   df -h           # Should show 20GB+ free space
   ```

2. **Check domain DNS:**
   ```bash
   nslookup yourdomain.com
   ping yourdomain.com
   ```

3. **Re-run installation:**
   ```bash
   # The script is idempotent - safe to run multiple times
   curl -fsSL https://raw.githubusercontent.com/anshpatel2/minecraft-hosting-billing/main/install.sh | sudo bash -s -- yourdomain.com your@email.com
   ```

### **Common Issues:**

**Domain not resolving?**
- Wait for DNS propagation (up to 24 hours)
- Verify A record points to correct IP
- SSL will be configured automatically once DNS resolves

**MySQL connection errors?**
- Script handles this automatically with multiple fallback methods
- If issues persist, check `/var/log/mysql/error.log`

**Permission errors?**
- Ensure you're running as root: `sudo su -`
- Script sets all permissions automatically

## 🎯 **Success Indicators**

Installation is successful when you see:
```
╔══════════════════════════════════════════════════════════════╗
║                    🎉 INSTALLATION COMPLETE! 🎉             ║
╚══════════════════════════════════════════════════════════════╝

✅ Website: https://yourdomain.com
✅ Admin Email: your@email.com
✅ Admin Password: [generated-password]
✅ Database Password: [generated-password]
✅ MySQL Root Password: [generated-password]
```

## 🔄 **Next Steps**

After successful installation:

1. **Save Credentials**: Copy all passwords to a secure location
2. **Login**: Visit `https://yourdomain.com` and login with admin credentials
3. **Configure**: Set up your hosting plans and payment methods
4. **Test**: Create a test order to verify everything works
5. **Customize**: Brand your platform with logo and colors

## 📞 **Support**

- **Documentation**: [GitHub Wiki](https://github.com/anshpatel2/minecraft-hosting-billing/wiki)
- **Issues**: [GitHub Issues](https://github.com/anshpatel2/minecraft-hosting-billing/issues)
- **Updates**: Run `sudo minecraft-hosting-update` anytime

---

**Ready to deploy? Copy and paste the installation command above! 🚀**
