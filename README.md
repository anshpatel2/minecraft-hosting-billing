# 🎮 Minecraft Hosting Billing System

A comprehensive Laravel-based billing and management system designed specifically for Minecraft hosting providers. This system provides a complete solution for managing users, roles, billing, and server infrastructure with a modern, responsive interface.

## 🚀 Features

### 👥 User Management
- **Multi-Role System**: Admin, Reseller, and Customer roles with specific permissions
- **User Registration & Authentication**: Secure login with email verification
- **Profile Management**: Complete user profile editing and management
- **Role-based Access Control**: Powered by Spatie Laravel Permission package

### 🎨 Modern Interface
- **Dark/Light Mode**: Fully responsive theme switching
- **DataTables Integration**: Advanced table features with search, pagination, and export
- **Tailwind CSS**: Modern, responsive design
- **Mobile-Friendly**: Optimized for all device sizes

### 🔧 Admin Panel
- **Dashboard**: Overview of system statistics and metrics
- **User Management**: Create, edit, delete, and manage users
- **Role Management**: Assign and manage user permissions
- **System Settings**: Configure application settings

### 📊 Advanced Features
- **Sequential ID Display**: User-friendly incremental numbering
- **Real-time Theme Sync**: Instant dark/light mode switching
- **Export Functions**: Copy, Excel, PDF, and Print capabilities
- **Search & Filtering**: Advanced DataTable search and filter options

## 🛠️ Technology Stack

- **Backend**: Laravel 11.x (PHP 8.3+)
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Database**: SQLite (easily configurable to MySQL/PostgreSQL)
- **Authentication**: Laravel Breeze
- **Permissions**: Spatie Laravel Permission
- **UI Components**: DataTables, jQuery
- **Icons**: Heroicons

## 📋 Requirements

- PHP 8.3 or higher
- Composer
- Node.js & NPM
- SQLite/MySQL/PostgreSQL
- Web server (Apache/Nginx)

## ⚡ Quick Start

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
