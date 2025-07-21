# 🎮 Minecraft Hosting Billing System

![Laravel](https://img.shields.io/badge/Laravel-11.x-red?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.3+-blue?style=for-the-badge&logo=php)
![TailwindCSS](https://img.shields.io/badge/Tailwind-CSS-38B2AC?style=for-the-badge&logo=tailwind-css)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

A comprehensive Laravel-based billing and management system designed specifically for Minecraft hosting providers. This modern platform provides a complete solution for managing users, server plans, billing, and administrative tasks with an intuitive, responsive interface.

## ✨ Key Features

### 🎯 Core Functionality
- **Complete Billing System**: Handle orders, plans, and payments
- **Multi-Role Management**: Admin, Reseller, and Customer roles with granular permissions
- **Server Plan Management**: Create and manage hosting plans with custom configurations
- **Real-time Dashboard**: Live statistics and system overview
- **Order Processing**: Complete order lifecycle management

### 👥 User Management
- **Advanced User System**: Registration, authentication, and profile management
- **Role-based Access Control**: Powered by Spatie Laravel Permission
- **Email Verification**: Secure user verification system
- **User Activity Tracking**: Monitor user actions and system usage

### 🎨 Modern Interface
- **Responsive Design**: Fully optimized for desktop, tablet, and mobile
- **Dark/Light Theme**: Seamless theme switching with persistent preferences
- **DataTables Integration**: Advanced tables with search, sorting, and export capabilities
- **Interactive Dashboard**: Real-time statistics and quick action buttons
- **Professional UI**: Clean, modern design with smooth animations

### 📊 Administrative Features
- **Comprehensive Dashboard**: System overview with key metrics
- **User Management Interface**: Create, edit, and manage all users
- **Plan Administration**: Configure hosting plans and pricing
- **Order Management**: Process and track customer orders
- **System Analytics**: User distribution, order status, and revenue tracking

### 🔧 Technical Features
- **RESTful API Ready**: Built with API-first approach
- **Database Flexibility**: SQLite (default), MySQL, PostgreSQL support
- **Export Capabilities**: Excel, PDF, CSV export functionality
- **Advanced Search**: Full-text search across all data tables
- **Notification System**: Real-time notifications for admin actions

## �️ Technology Stack

| Component | Technology |
|-----------|------------|
| **Backend Framework** | Laravel 11.x |
| **PHP Version** | 8.3+ |
| **Frontend** | Blade Templates + Tailwind CSS |
| **JavaScript** | Alpine.js + Vanilla JS |
| **Database** | SQLite (configurable) |
| **Authentication** | Laravel Breeze |
| **Permissions** | Spatie Laravel Permission |
| **UI Components** | DataTables, Font Awesome |
| **Styling** | Tailwind CSS + Custom Components |

## 📋 System Requirements

- **PHP**: 8.3 or higher
- **Composer**: Latest version
- **Node.js**: 16.x or higher
- **NPM/Yarn**: Latest version
- **Database**: SQLite/MySQL/PostgreSQL
- **Web Server**: Apache/Nginx
- **Memory**: 512MB minimum (1GB recommended)

## 🚀 Installation Guide

### 1. Clone the Repository
```bash
git clone https://github.com/anshpatel2/minecraft-hosting-billing.git
cd minecraft-hosting-billing
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration
```bash
# Run migrations
php artisan migrate

# Seed the database (optional)
php artisan db:seed
```

### 5. Build Assets
```bash
# Compile assets for development
npm run dev

# Or for production
npm run build
```

### 6. Set Permissions
```bash
# Set proper permissions (Linux/Mac)
chmod -R 755 storage bootstrap/cache

# Create symbolic link for storage
php artisan storage:link
```

### 7. Start Development Server
```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## 🔧 Configuration

### Database Configuration
Edit your `.env` file to configure your database:

```env
# For SQLite (default)
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite

# For MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=minecraft_billing
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Email Configuration
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

### Application Settings
```env
APP_NAME="Minecraft Hosting"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

## � Default Users

After running the seeder, you can log in with these default accounts:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@admin.com | password |
| Reseller | reseller@test.com | password |
| Customer | customer@test.com | password |

**⚠️ Important**: Change these default passwords immediately in production!

## 📱 Features Overview

### Admin Dashboard
- **Real-time Statistics**: Users, orders, revenue, and plan metrics
- **Quick Actions**: Direct access to management functions
- **Recent Activity**: Latest users and orders
- **System Overview**: Comprehensive platform analytics

### User Management
- **CRUD Operations**: Complete user lifecycle management
- **Role Assignment**: Flexible role-based permissions
- **Bulk Actions**: Efficient mass user operations
- **Export Options**: Multiple format export capabilities

### Plan Management
- **Hosting Plans**: Configure server specifications
- **Pricing Control**: Flexible pricing and billing cycles
- **Feature Management**: Custom plan features and limitations
- **Status Control**: Enable/disable plans instantly

### Order System
- **Order Processing**: Complete order lifecycle
- **Status Tracking**: Real-time order status updates
- **Payment Integration**: Ready for payment gateway integration
- **Customer Communications**: Automated notifications

## 🎨 UI Components

The system includes custom UI components:
- **Modern Cards**: Clean, responsive card layouts
- **Statistics Widgets**: Interactive metric displays
- **Data Tables**: Advanced table functionality
- **Modal Windows**: Elegant popup interfaces
- **Form Components**: Consistent form styling
- **Navigation**: Intuitive menu systems

## 🔒 Security Features

- **CSRF Protection**: Built-in Laravel CSRF protection
- **SQL Injection Prevention**: Eloquent ORM protection
- **XSS Protection**: Input sanitization and output escaping
- **Authentication**: Secure user authentication system
- **Authorization**: Role-based access control
- **Password Hashing**: Bcrypt password hashing

## 📊 Database Schema

### Core Tables
- `users` - User accounts and profiles
- `roles` - User roles and permissions
- `plans` - Server hosting plans
- `orders` - Customer orders and billing
- `notifications` - System notifications

### Permission Tables (Spatie)
- `model_has_roles` - User role assignments
- `role_has_permissions` - Role permission mappings
- `model_has_permissions` - Direct permission assignments

## � Deployment

### Production Deployment
1. **Server Setup**: Configure Apache/Nginx
2. **SSL Certificate**: Install SSL certificate
3. **Environment**: Set `APP_ENV=production`
4. **Optimization**: Run optimization commands
5. **Monitoring**: Set up error monitoring

### Optimization Commands
```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. **Fork** the repository
2. **Create** a feature branch (`git checkout -b feature/amazing-feature`)
3. **Commit** your changes (`git commit -m 'Add amazing feature'`)
4. **Push** to the branch (`git push origin feature/amazing-feature`)
5. **Open** a Pull Request

### Contribution Guidelines
- Follow PSR-12 coding standards
- Write comprehensive tests
- Update documentation
- Ensure backwards compatibility

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

- **Documentation**: Check the [Wiki](../../wiki) for detailed guides
- **Issues**: Report bugs on [GitHub Issues](../../issues)
- **Discussions**: Join [GitHub Discussions](../../discussions)
- **Email**: Contact us at support@yourcompany.com

## 🎯 Roadmap

### Upcoming Features
- [ ] **Payment Gateway Integration** (Stripe, PayPal)
- [ ] **API Documentation** (Swagger/OpenAPI)
- [ ] **Multi-language Support** (i18n)
- [ ] **Advanced Analytics** (Charts and graphs)
- [ ] **Email Templates** (Customizable notifications)
- [ ] **Server Integration** (Pterodactyl, Multicraft)
- [ ] **Invoice Generation** (PDF invoices)
- [ ] **Support Ticket System**

### Version History
- **v1.0.0** - Initial release with core functionality
- **v1.1.0** - Enhanced UI and dashboard improvements
- **v1.2.0** - Advanced user management features

## 🏗️ Architecture

The application follows Laravel's MVC architecture with additional layers:

```
app/
├── Http/Controllers/     # Request handling
├── Models/              # Data models
├── Providers/           # Service providers
├── Traits/              # Reusable traits
└── Notifications/       # Email notifications

resources/
├── views/               # Blade templates
├── css/                 # Stylesheets
└── js/                  # JavaScript files

database/
├── migrations/          # Database migrations
├── seeders/            # Database seeders
└── factories/          # Model factories
```

## 📈 Performance

The system is optimized for performance:
- **Eager Loading**: Prevents N+1 query problems
- **Database Indexing**: Optimized database queries
- **Caching**: View and configuration caching
- **Asset Optimization**: Minified CSS and JavaScript
- **Image Optimization**: Compressed images and icons

---

<div align="center">

**Made with ❤️ for the Minecraft hosting community**

[⭐ Star this repository](../../stargazers) | [🐛 Report Bug](../../issues) | [💡 Request Feature](../../issues)

</div>

### 1. Clone the Repository
```bash
git clone https://github.com/anshpatel2/minecraft-hosting-billing.git
cd minecraft-hosting-billing
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup
```bash
# Run migrations
php artisan migrate

# Seed the database with roles and permissions
php artisan db:seed --class=RoleSeeder
```

### 5. Build Assets
```bash
# Build frontend assets
npm run build

# For development with hot reload
npm run dev
```

### 6. Start the Server
```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## 🔐 Default Access

After seeding, you can create an admin user:

```bash
php artisan tinker
```

```php
$user = App\Models\User::where('email', 'admin@example.com')->first();
$user->assignRole('Admin');
```

## 📁 Project Structure

```
minecraft-hosting-billing/
├── app/
│   ├── Http/Controllers/
│   │   └── Admin/UserController.php
│   ├── Models/
│   │   └── User.php
│   └── Traits/
│       └── HasRoleHelpers.php
├── database/
│   ├── migrations/
│   └── seeders/
│       └── RoleSeeder.php
├── resources/
│   ├── views/
│   │   ├── admin/
│   │   ├── customer/
│   │   ├── reseller/
│   │   └── layouts/
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php
│   ├── admin.php
│   └── auth.php
└── public/
    └── assets/
```

## 🎯 Key Features Implementation

### Role-Based Access Control
The system implements a comprehensive role-based access control system:

- **Admin**: Full system access and user management
- **Reseller**: Customer management and server creation
- **Customer**: Server management and billing access

### DataTable Integration
Advanced DataTable features including:
- Search functionality across all columns
- Export options (Copy, Excel, PDF, Print)
- Responsive design for mobile devices
- Custom pagination and sorting
- Real-time dark/light mode synchronization

### Theme System
Intelligent theme switching that:
- Automatically detects system preferences
- Syncs across all components including DataTables
- Persists user preferences
- Provides smooth transitions

## 🔧 Configuration

### Database Configuration
Update `.env` file with your database credentials:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

### Mail Configuration
Configure email settings for user verification:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
```

## 🚦 Development Workflow

### Running in Development
```bash
# Start Laravel development server
php artisan serve

# Watch for file changes (in another terminal)
npm run dev
```

### Code Quality
```bash
# Run tests
php artisan test

# Code formatting (if PHPStan is installed)
./vendor/bin/phpstan analyse
```

## 📝 API Documentation

The system provides RESTful API endpoints for:
- User management
- Role assignment
- Server management
- Billing operations

Detailed API documentation will be available at `/api/documentation` when configured.

## 🔒 Security Features

- **CSRF Protection**: All forms protected against CSRF attacks
- **SQL Injection Prevention**: Eloquent ORM prevents SQL injection
- **XSS Protection**: Blade template engine provides XSS protection
- **Authentication**: Secure user authentication with Laravel Breeze
- **Authorization**: Role-based permissions with Spatie Laravel Permission

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support and questions:
- Create an issue on GitHub
- Check the documentation
- Contact the development team

## 🗺️ Roadmap

### Upcoming Features
- [ ] Server management interface
- [ ] Billing and payment integration
- [ ] Customer portal enhancements
- [ ] API rate limiting
- [ ] Advanced reporting
- [ ] Multi-language support
- [ ] Docker containerization

### Version History
- **v1.0.0** - Initial release with user management and admin panel
- **v1.1.0** - DataTable integration and theme system
- **v1.2.0** - Role-based access control implementation

## 📊 System Status

- ✅ User Authentication & Registration
- ✅ Admin Panel
- ✅ Role Management
- ✅ Dark/Light Theme
- ✅ DataTable Integration
- ✅ Responsive Design
- 🚧 Server Management (In Progress)
- 🚧 Billing System (Planned)
- 🚧 Payment Integration (Planned)

---

**Built with ❤️ for the Minecraft hosting community**

## About Laravel Framework

This project is built on Laravel - a web application framework with expressive, elegant syntax. Laravel provides:

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
