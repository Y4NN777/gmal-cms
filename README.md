# GiveMeALift CMS

A Content Management System for the GiveMeALift NGO, built with Laravel 12 and Vue 3.

![Build Status](https://img.shields.io/badge/build-in_progress-yellow)
![Laravel](https://img.shields.io/badge/Laravel-12.x-red)
![Vue](https://img.shields.io/badge/Vue-3.x-green)
![License](https://img.shields.io/badge/license-MIT-blue)

---

## Overview

GiveMeALift CMS is a content management system for the GiveMeALift non-governmental organization. It manages events, testimonials, gallery content, donations, contact messages, and analytics with a Vue 3 frontend and Laravel 12 backend using Inertia.js.

**Status:** This project is in active development.

---

## Quick Start

```bash
# 1. Install dependencies
composer install
npm install

# 2. Configure environment
cp .env.example .env
php artisan key:generate

# 3. Setup database
php artisan migrate
php artisan db:seed

# 4. Build frontend
npm run build

# 5. Start server
php artisan serve
```

**Default Admin Account:**
- Email: `admin@givemealift.org`
- Password: `password`

---

## Features

- **Event Management** - Create, edit, and manage events with categories and featured images
- **Testimonials** - User submissions with admin approval workflow
- **Gallery** - Album-based photo organization with captions
- **Donations** - PayPal integration with analytics
- **Contact Forms** - Message categorization and admin interface
- **Analytics** - Visitor tracking and donation statistics
- **User Management** - Role-based access control (Admin, Editor, Viewer)
- **Media Library** - Centralized file management
- **Settings** - Multi-language support (English/French) and site configuration

---

## Tech Stack

**Backend:** Laravel 12, PHP 8.2+, PostgreSQL 14+  
**Frontend:** Vue 3.5, Inertia.js 2.2, Tailwind CSS 4.0, Vite 7.0  
**Tools:** Composer, NPM, PHPUnit, Docker

---

## Development

### Running Development Server

```bash
# All services
composer dev

# Or individually
php artisan serve          # Laravel (port 8000)
npm run dev               # Vite with HMR
php artisan queue:listen  # Queue worker
php artisan pail          # Logs
```

### Database

```bash
# Migrations
php artisan migrate
php artisan migrate:rollback
php artisan migrate:fresh --seed

# Create migration
php artisan make:migration create_table_name
```

### Testing

```bash
# Run tests
php artisan test

# Run specific test
php artisan test tests/Feature/EventTest.php

# With coverage
php artisan test --coverage
```

### Code Style

```bash
# Fix code style
./vendor/bin/pint
```

---

## Deployment

```bash
# Install dependencies
composer install --optimize-autoloader --no-dev
npm install --production

# Build assets
npm run build

# Setup database
php artisan migrate --force

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Permissions
chmod -R 755 storage bootstrap/cache
```

### Docker

```bash
docker build -t gmal-cms .
docker run -d -p 80:80 gmal-cms
```

### Production Checklist

- [ ] Change default admin password
- [ ] Set `APP_ENV=production` and `APP_DEBUG=false`
- [ ] Configure database credentials
- [ ] Enable HTTPS
- [ ] Setup automated backups
- [ ] Configure firewall
- [ ] Update PayPal credentials
- [ ] Configure email service

---

## Configuration

Key environment variables in `.env`:

```env
APP_NAME="GiveMeALift"
APP_ENV=local
APP_URL=http://localhost

DB_CONNECTION=sqlite
# Or PostgreSQL:
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_DATABASE=gmal_cms

PAYPAL_MODE=sandbox
PAYPAL_CLIENT_ID=your_client_id
PAYPAL_SECRET=your_secret
```

---

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -m 'Add feature'`)
4. Push to branch (`git push origin feature/new-feature`)
5. Open a Pull Request

---

## License

MIT License

---

## Support

- **Issues**: [GitHub Issues](https://github.com/Y4NN777/gmal-cms/issues)
- **Laravel Docs**: [https://laravel.com/docs](https://laravel.com/docs)
- **Vue.js Docs**: [https://vuejs.org/guide](https://vuejs.org/guide)
- **Inertia.js Docs**: [https://inertiajs.com](https://inertiajs.com)
