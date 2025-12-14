# GiveMeALift CMS

A comprehensive Content Management System designed for NGOs and non-profit organizations, built with Laravel 12 and Vue 3 using modern web development practices.

![Build Status](https://img.shields.io/badge/build-in_progress-yellow)
![Laravel](https://img.shields.io/badge/Laravel-12.x-red)
![Vue](https://img.shields.io/badge/Vue-3.x-green)
![License](https://img.shields.io/badge/license-MIT-blue)

---

## Overview

GiveMeALift CMS is a feature-rich content management system specifically designed for non-governmental organizations. It provides a complete platform for managing events, testimonials, gallery content, donations, contact messages, and analytics. The system features a modern Vue 3 frontend integrated with Laravel 12 backend using Inertia.js for seamless single-page application functionality.

**Current Status:** This project is actively under development. The backend architecture and core database models are complete, with frontend pages being progressively implemented.

---

## Features

### Core Functionality

**Event Management**
- Create, edit, and manage events with full CRUD operations
- Category-based organization with customizable classifications
- Featured event highlighting for homepage display
- Event scheduling with date and time tracking
- Location management and descriptions
- Image attachments through the media system
- Published/draft status control

**Testimonials System**
- User-submitted testimonial collection
- Admin approval workflow
- Featured testimonial selection
- Rating system
- Avatar image support
- Display order customization

**Gallery Management**
- Album-based photo organization
- Image metadata including captions and alt text
- Multiple images per album
- Public gallery viewing pages

**Donation Tracking**
- PayPal integration for donation processing
- Donation analytics and reporting
- Campaign-specific donation tracking
- Transaction history

**Contact Management**
- Public contact form
- Message categorization (general inquiry, support, partnership, volunteer, donation)
- Admin message viewing interface

**Analytics Dashboard**
- Visitor analytics tracking
- Donation statistics
- Event performance metrics
- Dashboard overview with key statistics

**User Management**
- Role-based access control (Admin, Editor, Viewer)
- User authentication and authorization
- Secure password management

**Media Library**
- Centralized media management
- File type categorization (image, video, document, audio)
- Alt text and description support
- Media relationship tracking

**Settings System**
- Site-wide configuration management
- Multi-language support (English and French)
- Contact information settings
- Social media integration
- About section content

### Internationalization

- Multi-language support (English and French)
- Language switcher interface
- Translated content for categories and settings

---

## Technology Stack

### Backend
- **Framework:** Laravel 12
- **PHP Version:** 8.2+
- **Database:** PostgreSQL 14+ (or SQLite for development)
- **Image Processing:** Intervention Image 3.0
- **Asset Management:** Laravel Vite Plugin 2.0

### Frontend
- **Framework:** Vue 3.5
- **SPA Integration:** Inertia.js 2.2
- **Styling:** Tailwind CSS 4.0
- **Build Tool:** Vite 7.0
- **Icons:** Lucide Vue Next
- **Image Gallery:** Vue Easy Lightbox
- **Carousel:** Swiper 12
- **Routing:** Ziggy (Laravel routes in JavaScript)

### Development Tools
- **Package Manager:** Composer (PHP), NPM (JavaScript)
- **Code Quality:** Laravel Pint (code style)
- **Testing:** PHPUnit 11.5
- **Container:** Docker support included
- **Process Management:** Supervisor (production)

---

## Installation

### Prerequisites

- PHP 8.2 or higher
- Composer 2.x
- Node.js 18+ and NPM
- PostgreSQL 14+ or SQLite (for development)
- Git

### Quick Start

```bash
# Clone the repository
git clone https://github.com/Y4NN777/gmal-cms.git
cd gmal-cms

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate
php artisan db:seed

# Build frontend assets
npm run build

# Start development server
php artisan serve
```

The application will be available at `http://127.0.0.1:8000`

### Development Mode

For active development with hot module replacement:

```bash
# Run Laravel development server with queue, logs, and Vite
composer dev

# Or run individually:
php artisan serve          # Laravel server (port 8000)
npm run dev               # Vite dev server with HMR
php artisan queue:listen  # Queue worker
php artisan pail          # Log viewer
```

### Default Credentials

After running the database seeder:
- **Email:** `admin@givemealift.org`
- **Password:** `password`

**Important:** Change these credentials immediately in production environments.

---

## Project Structure

```
gmal-cms/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/              # Admin panel controllers
│   │   │   │   ├── AnalyticsController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── ContactMessageController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── DonationController.php
│   │   │   │   ├── EventController.php
│   │   │   │   ├── GalleryController.php
│   │   │   │   ├── GalleryImageController.php
│   │   │   │   ├── ImpactMetricController.php
│   │   │   │   ├── MediaController.php
│   │   │   │   ├── SettingController.php
│   │   │   │   ├── TestimonialController.php
│   │   │   │   └── UserController.php
│   │   │   ├── Auth/                # Authentication controllers
│   │   │   │   └── LoginController.php
│   │   │   ├── AboutController.php
│   │   │   ├── ContactController.php
│   │   │   ├── DonationController.php
│   │   │   ├── GalleryController.php
│   │   │   └── HomeController.php
│   │   ├── Middleware/
│   │   │   ├── EnsureUserIsAdmin.php
│   │   │   ├── EnsureUserIsEditor.php
│   │   │   ├── HandleInertiaRequests.php
│   │   │   ├── SetLocale.php
│   │   │   └── ShareSettings.php
│   │   └── Requests/               # Form validation requests
│   ├── Models/
│   │   ├── Category.php
│   │   ├── ContactMessage.php
│   │   ├── DonationAnalytic.php
│   │   ├── Event.php
│   │   ├── GalleryAlbum.php
│   │   ├── GalleryImage.php
│   │   ├── ImpactMetric.php
│   │   ├── Media.php
│   │   ├── Setting.php
│   │   ├── Testimonial.php
│   │   ├── User.php
│   │   └── VisitorAnalytic.php
│   └── Providers/                  # Service providers
├── database/
│   ├── factories/                  # Model factories for testing
│   ├── migrations/                 # Database migrations
│   └── seeders/                    # Database seeders
├── resources/
│   ├── css/                        # Stylesheets
│   ├── js/
│   │   ├── Components/             # Reusable Vue components
│   │   ├── Layouts/                # Page layouts
│   │   └── Pages/
│   │       ├── Admin/              # Admin panel pages
│   │       │   ├── Categories/
│   │       │   ├── Contact/
│   │       │   ├── Dashboard/
│   │       │   ├── Donations/
│   │       │   ├── Events/
│   │       │   ├── Gallery/
│   │       │   ├── ImpactMetrics/
│   │       │   ├── Media/
│   │       │   ├── Settings/
│   │       │   ├── Testimonials/
│   │       │   └── Users/
│   │       ├── About/              # Public about page
│   │       ├── Auth/               # Authentication pages
│   │       ├── Contact/            # Contact page
│   │       ├── Donate/             # Donation pages
│   │       ├── Events/             # Public event pages
│   │       ├── Gallery/            # Public gallery pages
│   │       └── Home/               # Homepage
│   └── views/                      # Blade templates
├── routes/
│   ├── api.php                     # API routes (currently unused)
│   ├── console.php                 # Artisan commands
│   └── web.php                     # Web routes (Inertia)
├── tests/
│   ├── Feature/                    # Feature tests
│   └── Unit/                       # Unit tests
├── composer.json                   # PHP dependencies
├── package.json                    # JavaScript dependencies
├── phpunit.xml                     # PHPUnit configuration
├── vite.config.js                  # Vite configuration
└── Dockerfile                      # Docker configuration
```

---

## Database Architecture

The system uses a comprehensive relational database schema with the following key tables:

### Core Tables

**users**
- User authentication and profile information
- Role-based permissions (admin, editor, viewer)
- Timestamps for creation and updates

**events**
- Event title, slug, and descriptions
- Start and end date/time
- Location and status tracking
- Category and featured image relationships
- SEO metadata fields
- Creator and updater tracking

**categories**
- Category name and slug
- Color coding for visual identification
- Multi-language support (English and French translations)

**testimonials**
- Submitter name, email, and organization
- Testimonial content and rating
- Approval status and workflow
- Featured/display order management
- Avatar image support

**gallery_albums**
- Album title, slug, and description
- Cover image and publication status
- Hierarchical display ordering

**gallery_images**
- Image caption and alt text
- Album association
- Media file relationship
- Display ordering

**media**
- File metadata (type, size, dimensions)
- Storage path and URL
- Alt text and description
- MIME type tracking

**contact_messages**
- Sender information
- Message subject and content
- Category classification
- Read status tracking

**donation_analytics**
- Transaction details (amount, currency, PayPal ID)
- Campaign/event association
- Payment status tracking
- Anonymous donor support
- Transaction timestamps

**visitor_analytics**
- Page view tracking
- User agent and IP logging
- Referrer information
- Session and event tracking

**impact_metrics**
- NGO impact statistics
- Metric title, value, and icon
- Category classification
- Display ordering

**settings**
- Site-wide configuration
- Multi-language content support
- JSON data storage for flexible structure

### Relationships

- Events belong to Categories and Users (creator/updater)
- Events have one Featured Image (Media)
- Testimonials have one Avatar (Media)
- Testimonials belong to Users (approver)
- Gallery Images belong to Gallery Albums
- Gallery Images and Albums have Media relationships
- Donations belong to Events (campaigns)
- Various models track creator/updater relationships

---

## Routes and Pages

### Public Routes

```
GET  /                      # Homepage with featured content
GET  /events                # Event listing with filters
GET  /events/{slug}         # Event detail page
GET  /about                 # About organization page
GET  /contact               # Contact form
POST /contact               # Submit contact message
POST /testimonials          # Submit testimonial
GET  /gallery               # Gallery albums listing
GET  /gallery/albums        # Alternative gallery view
GET  /gallery/{slug}        # Album detail with images
GET  /donate                # Donation page
GET  /donate/thank-you      # Post-donation thank you page
POST /webhooks/paypal-ipn   # PayPal IPN webhook handler
POST /locale                # Language switcher
```

### Authentication Routes

```
GET  /admin/login           # Admin login page
POST /admin/login           # Login submission
POST /admin/logout          # Logout
```

### Admin Panel Routes (Protected)

All admin routes require authentication and appropriate role permissions.

```
GET  /admin/dashboard                    # Analytics overview

# Event Management
GET  /admin/events                       # Event list
GET  /admin/events/create                # Create event form
POST /admin/events                       # Store event
GET  /admin/events/{id}/edit             # Edit event form
PUT  /admin/events/{id}                  # Update event
DELETE /admin/events/{id}                # Delete event

# Testimonial Management
GET  /admin/testimonials                 # Testimonial list
GET  /admin/testimonials/{id}            # View testimonial
GET  /admin/testimonials/{id}/edit       # Edit testimonial
PUT  /admin/testimonials/{id}            # Update testimonial
DELETE /admin/testimonials/{id}          # Delete testimonial
POST /admin/testimonials/{id}/approve    # Approve testimonial
POST /admin/testimonials/{id}/reject     # Reject testimonial

# Gallery Management
GET  /admin/gallery                      # Album list
GET  /admin/gallery/create               # Create album form
POST /admin/gallery                      # Store album
GET  /admin/gallery/{id}/edit            # Edit album form
PUT  /admin/gallery/{id}                 # Update album
DELETE /admin/gallery/{id}               # Delete album

# Gallery Image Management
GET  /admin/gallery/{albumId}/images            # Image list
POST /admin/gallery/{albumId}/images            # Upload image
PUT  /admin/gallery/{albumId}/images/{imageId}  # Update image
DELETE /admin/gallery/{albumId}/images/{imageId} # Delete image

# Contact Messages
GET  /admin/contact                      # Message list
GET  /admin/contact/{id}                 # View message
PUT  /admin/contact/{id}/read            # Mark as read
DELETE /admin/contact/{id}               # Delete message

# Donations
GET  /admin/donations                    # Donation list
GET  /admin/donations/{id}               # View donation

# Categories
GET  /admin/categories                   # Category list
POST /admin/categories                   # Store category
PUT  /admin/categories/{id}              # Update category
DELETE /admin/categories/{id}            # Delete category

# Impact Metrics
GET  /admin/impact-metrics               # Metrics list
GET  /admin/impact-metrics/create        # Create metric form
POST /admin/impact-metrics               # Store metric
GET  /admin/impact-metrics/{id}/edit     # Edit metric form
PUT  /admin/impact-metrics/{id}          # Update metric
DELETE /admin/impact-metrics/{id}        # Delete metric

# Media Library
GET  /admin/media                        # Media list
POST /admin/media                        # Upload media
DELETE /admin/media/{id}                 # Delete media

# Users
GET  /admin/users                        # User list
POST /admin/users                        # Create user
PUT  /admin/users/{id}                   # Update user
DELETE /admin/users/{id}                 # Delete user

# Settings
GET  /admin/settings                     # Settings page
PUT  /admin/settings                     # Update settings

# Analytics
GET  /admin/analytics                    # Analytics dashboard
GET  /admin/analytics/visitors           # Visitor analytics
GET  /admin/analytics/donations          # Donation analytics
```

---

## Configuration

### Environment Variables

Key configuration options in `.env`:

```env
# Application
APP_NAME="GiveMeALift"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Localization
APP_LOCALE=en
APP_FALLBACK_LOCALE=en

# Database
DB_CONNECTION=sqlite
# For PostgreSQL:
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_PORT=5432
# DB_DATABASE=gmal_cms
# DB_USERNAME=postgres
# DB_PASSWORD=

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Queue
QUEUE_CONNECTION=database

# Cache
CACHE_STORE=database

# Mail
MAIL_MAILER=log
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# PayPal
PAYPAL_MODE=sandbox
PAYPAL_CLIENT_ID=your_client_id
PAYPAL_SECRET=your_secret
PAYPAL_BUSINESS_EMAIL=your-email@example.com
```

### Database Configuration

The application supports multiple database drivers:

- **SQLite** (default for development): Minimal setup, file-based
- **PostgreSQL** (recommended for production): Robust, full-featured
- **MySQL/MariaDB**: Alternative production option

To switch databases, update the `DB_CONNECTION` variable and corresponding credentials in `.env`.

---

## Testing

The application uses PHPUnit for testing.

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/EventTest.php

# Run with coverage report
php artisan test --coverage

# Run tests in parallel
php artisan test --parallel

# Run specific test method
php artisan test --filter=testEventCreation
```

### Test Structure

- `tests/Feature/`: Integration tests for HTTP endpoints and workflows
- `tests/Unit/`: Unit tests for individual classes and methods

---

## Deployment

### Production Requirements

- PHP 8.2+ with required extensions (pdo, pgsql, mbstring, exif, pcntl, bcmath, gd)
- PostgreSQL 14+
- Nginx or Apache web server
- SSL certificate for HTTPS
- Composer 2.x
- Node.js 18+ (for building assets)

### Deployment Steps

```bash
# 1. Clone and navigate to project
git clone https://github.com/Y4NN777/gmal-cms.git
cd gmal-cms

# 2. Install dependencies (production optimized)
composer install --optimize-autoloader --no-dev
npm install --production

# 3. Configure environment
cp .env.example .env
# Edit .env with production settings
php artisan key:generate

# 4. Build frontend assets
npm run build

# 5. Setup database
php artisan migrate --force
php artisan db:seed --force

# 6. Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Set proper permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Docker Deployment

A Dockerfile is included for containerized deployment:

```bash
# Build Docker image
docker build -t gmal-cms .

# Run container
docker run -d -p 80:80 \
  -e APP_ENV=production \
  -e DB_HOST=your-db-host \
  -e DB_DATABASE=your-db-name \
  -e DB_USERNAME=your-db-user \
  -e DB_PASSWORD=your-db-password \
  gmal-cms
```

### Security Checklist

Before deploying to production:

- [ ] Change default admin credentials
- [ ] Set `APP_ENV=production` and `APP_DEBUG=false`
- [ ] Configure proper database credentials
- [ ] Enable HTTPS with valid SSL certificate
- [ ] Configure CORS settings appropriately
- [ ] Set up automated database backups
- [ ] Review and restrict file permissions
- [ ] Configure firewall rules
- [ ] Set up monitoring and logging
- [ ] Review and update PayPal credentials
- [ ] Configure email service for notifications

---

## Development Guidelines

### Code Style

The project follows Laravel coding standards. Use Laravel Pint to automatically fix code style issues:

```bash
# Fix code style issues
./vendor/bin/pint

# Check without fixing
./vendor/bin/pint --test
```

### Database Changes

Always create migrations for database changes:

```bash
# Create a new migration
php artisan make:migration create_table_name

# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Reset and re-run all migrations
php artisan migrate:fresh

# Reset and seed
php artisan migrate:fresh --seed
```

### Creating Models

```bash
# Create model with migration, factory, and seeder
php artisan make:model ModelName -mfs

# Create model with controller
php artisan make:model ModelName -c
```

### Frontend Development

```bash
# Start Vite dev server with hot reload
npm run dev

# Build for production
npm run build

# Preview production build
npm run preview
```

---

## Troubleshooting

### Common Issues

**Permission Errors**
```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R $USER:www-data storage bootstrap/cache
```

**Clear Application Cache**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

**Database Connection Issues**
- Verify database credentials in `.env`
- Ensure database server is running
- Check PostgreSQL/MySQL service status
- Verify database exists and user has proper permissions

**Asset Build Failures**
```bash
# Clear node_modules and reinstall
rm -rf node_modules package-lock.json
npm install
npm run build
```

**Queue Processing**
```bash
# Process queued jobs
php artisan queue:work

# Process specific queue
php artisan queue:work --queue=high,default

# Process single job
php artisan queue:work --once
```

---

## API Integration

While the application primarily uses Inertia.js for a monolithic architecture, API routes can be added to `routes/api.php` for mobile applications or third-party integrations.

### Example API Usage

```bash
# Public endpoints (no authentication)
curl -X GET http://localhost:8000/api/events

# Authenticated endpoints (with Bearer token)
curl -X GET http://localhost:8000/api/admin/events \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## Contributing

Contributions are welcome! To contribute:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -m 'Add new feature'`)
4. Push to the branch (`git push origin feature/new-feature`)
5. Open a Pull Request

### Contribution Guidelines

- Follow existing code style and conventions
- Write tests for new features
- Update documentation as needed
- Ensure all tests pass before submitting PR
- Provide clear commit messages
- Keep PRs focused on a single feature or fix

---

## License

This project is open-source software licensed under the [MIT License](https://opensource.org/licenses/MIT).

---

## Support and Documentation

For additional information and support:

- **Issues**: Report bugs and feature requests on [GitHub Issues](https://github.com/Y4NN777/gmal-cms/issues)
- **Documentation**: Additional documentation can be found in the `docs/` directory (if available)
- **Laravel Documentation**: [https://laravel.com/docs](https://laravel.com/docs)
- **Vue.js Documentation**: [https://vuejs.org/guide](https://vuejs.org/guide)
- **Inertia.js Documentation**: [https://inertiajs.com](https://inertiajs.com)

---

## Acknowledgments

Built with Laravel 12 and Vue 3, leveraging modern web technologies and best practices for NGO content management.
