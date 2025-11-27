# 🚧 GiveMeALift CMS

A modern, secure Content Management System for NGOs built with Laravel 12 and Vue 3.

![Build Status](https://img.shields.io/badge/build-in_progress-yellow)
![Laravel](https://img.shields.io/badge/Laravel-12.x-red)
![Vue](https://img.shields.io/badge/Vue-3.x-green)
![License](https://img.shields.io/badge/license-MIT-blue)

---

## ⚠️ System Status: **IN DEVELOPMENT**

**This project is NOT production ready.** Core backend is complete but critical frontend pages and features are missing.

---

## 🚀 Quick Start

```bash
# 1. Install dependencies
composer install
npm install

# 2. Configure environment
cp .env.example .env
php artisan key:generate

# 3. Setup database (PostgreSQL)
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

## 📦 What's Built vs What's Missing

### ✅ COMPLETED - Backend (Laravel)
- ✅ **Database Schema** - All 13+ tables with migrations
- ✅ **Eloquent Models** - 9 models with relationships, factories, seeders
- ✅ **Laravel Passport** - OAuth2 authentication configured
- ✅ **API Controllers** - Auth, Events, Testimonials, Contact (all functional)
- ✅ **Admin Controllers** - Dashboard, Events, Testimonials, Gallery (CRUD logic complete)
- ✅ **Middleware** - Authentication, admin/editor role guards
- ✅ **Form Requests** - Validation classes for all resources
- ✅ **API Routes** - Public & protected endpoints working
- ✅ **Web Routes** - All admin routes defined

### ✅ COMPLETED - Frontend (Vue/Inertia)
- ✅ **Authentication Pages** - Login page functional
- ✅ **Admin Dashboard** - Stats overview with data
- ✅ **Admin Events List** - Table with filters, search, pagination
- ✅ **Admin Testimonials List** - Approval workflow interface
- ✅ **Public Events Page** - Grid with category filters
- ✅ **Public About Page** - Mission, vision, values, stats
- ✅ **Public Contact Page** - Form with validation

### ❌ MISSING - Critical Admin Pages
- ❌ **Admin Event Create Form** - Controller exists, UI missing
- ❌ **Admin Event Edit Form** - Controller exists, UI missing
- ❌ **Admin Event Detail View** - Controller exists, UI missing
- ❌ **Admin Gallery Pages** - Controller exists, NO UI at all
  - Missing: Album list page
  - Missing: Album create/edit forms
  - Missing: Image upload interface
  - Missing: Image management interface
- ❌ **Admin Contact Messages** - No controller, no UI
- ❌ **Admin Analytics Pages** - Routes exist, controllers/UI missing
- ❌ **Admin User Management** - No CRUD for users
- ❌ **Admin Media Library** - No upload system, no browser

### ❌ MISSING - Public Pages
- ❌ **Event Detail Page** (`/events/{slug}`) - No UI
- ❌ **Gallery Public Pages** - No album list, no album detail
- ❌ **Home Page** - Currently shows default Laravel welcome page

### ❌ MISSING - Core Features
- ❌ **Media Upload System** - No file upload functionality
- ❌ **Image Processing** - No resize/optimize functionality
- ❌ **Email Notifications** - Not configured
- ❌ **Search Functionality** - No global search
- ❌ **SEO Management** - Meta fields exist but not managed
- ❌ **Cache System** - No caching implemented

---

## 🛠 Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | Laravel 12, PHP 8.2 |
| **Frontend** | Vue 3.5, Inertia.js 2.2 |
| **Database** | PostgreSQL 14+ |
| **Auth** | Laravel Passport (OAuth2) |
| **Styling** | Tailwind CSS 4.0 |
| **Build** | Vite 7.2 |

---

## 🔌 API Endpoints

### Public (No Auth)
```
GET  /api/v1/events              - List events
GET  /api/v1/events/categories   - Get categories
GET  /api/v1/events/{id}         - Single event
POST /api/v1/contact             - Submit message
```

### Authentication
```
POST /api/v1/auth/login          - Login (get token)
POST /api/v1/auth/logout         - Logout
GET  /api/v1/auth/me             - Get user info
```

### Protected Admin
```
All /admin/* routes require Bearer token
```

Full API documentation: See `routes/api.php`

---

## � Development Priorities

### Phase 1: Complete Admin CRUD (URGENT)
1. **Event Management Forms**
   - Create: `/admin/events/create` page
   - Edit: `/admin/events/{id}/edit` page
   - Detail: `/admin/events/{id}` page

2. **Gallery System** (CRITICAL - completely missing)
   - Admin album list: `/admin/gallery`
   - Admin album create/edit forms
   - Admin image upload interface
   - Image management (crop, resize, delete)

3. **Media Upload System**
   - Media library browser
   - File upload with validation
   - Image optimization/resizing
   - CDN integration setup

### Phase 2: Complete Public Pages
4. **Event Detail Page**
   - `/events/{slug}` with full event info
   - Registration/donation buttons
   - Related events section

5. **Gallery Public Pages**
   - `/gallery` - album grid
   - `/gallery/{slug}` - album detail with lightbox

6. **Home Page**
   - Replace default Laravel welcome
   - Hero section
   - Featured events/testimonials
   - Call-to-actions

### Phase 3: Missing Features
7. **Contact Messages Management**
   - Admin inbox for messages
   - Mark as read/unread
   - Reply functionality

8. **Analytics Dashboard**
   - Visitor stats visualization
   - Donation reports
   - Event analytics

9. **User Management**
   - Admin user CRUD
   - Role assignment
   - Password reset flow

---

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/      # API & Web controllers
│   ├── Middleware/       # Auth & admin guards
│   └── Requests/         # Form validation
├── Models/               # Eloquent models
└── Providers/            # Service providers

resources/
├── js/
│   ├── Pages/            # Vue components (Inertia)
│   │   ├── Admin/        # Admin panel pages
│   │   └── Events/       # Public pages
│   └── Layouts/          # AppLayout, Sidebar
└── views/                # Blade templates

routes/
├── api.php               # API routes
└── web.php               # Web routes

database/
├── migrations/           # Database schema
├── factories/            # Model factories
└── seeders/              # Demo data
```

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=EventTest

# Run with coverage
php artisan test --coverage
```

---

## 🔒 Security

- All admin routes protected with middleware
- API uses Bearer token authentication
- Environment variables never exposed
- Regular security updates via Composer

**⚠️ Production Checklist:**
- [ ] Change default admin password
- [ ] Set `APP_ENV=production`
- [ ] Enable HTTPS
- [ ] Configure proper CORS
- [ ] Set up database backups

See `SECURITY.md` for full security documentation.

---

## 📚 Documentation

- **Security**: See `SECURITY.md`
- **Authentication**: See `AUTHENTICATION.md`
- **API**: See `routes/api.php` comments
- **Database**: See `database/migrations/`

---

## ⚠️ Known Issues & Limitations

### Critical Issues
1. **Gallery is unusable** - Links exist in sidebar but lead to 404 (no pages created)
2. **Cannot create/edit events via UI** - Must use Tinker/database directly
3. **Home page is Laravel default** - Not the NGO homepage
4. **No media upload** - Cannot add images to events/gallery
5. **Event detail 404** - Clicking events leads nowhere

### Functional Limitations
- Admin can view events/testimonials lists but cannot create/edit via UI
- No way to manage uploaded images
- Contact messages are saved but cannot be viewed by admin
- Analytics data collected but no visualization
- No email notifications for any actions

### Testing Current Features
```bash
# Test what DOES work:
- Login: http://127.0.0.1:8000/admin/login ✅
- Dashboard stats: http://127.0.0.1:8000/admin/dashboard ✅
- Events list: http://127.0.0.1:8000/admin/events ✅
- Testimonials approval: http://127.0.0.1:8000/admin/testimonials ✅
- Public events: http://127.0.0.1:8000/events ✅
- About page: http://127.0.0.1:8000/about ✅
- Contact form: http://127.0.0.1:8000/contact ✅

# Test what DOESN'T work:
- Gallery: http://127.0.0.1:8000/admin/gallery ❌ 404
- Event create: http://127.0.0.1:8000/admin/events/create ❌ 404
- Event edit: http://127.0.0.1:8000/admin/events/1/edit ❌ 404
- Event detail: http://127.0.0.1:8000/events/some-slug ❌ 404
- Home: http://127.0.0.1:8000/ ❌ Laravel welcome page
```

---

## 📊 Completion Status

| Feature Category | Status | Details |
|-----------------|--------|---------|
| **Database** | ✅ 100% | All tables, migrations, relationships |
| **Models** | ✅ 100% | 9 models with factories/seeders |
| **API Backend** | ✅ 95% | Auth, events, testimonials working |
| **Admin Backend** | ✅ 90% | Controllers complete, validation done |
| **Admin Frontend** | ⚠️ 40% | Dashboard, lists done. Forms missing |
| **Public Frontend** | ⚠️ 30% | About/Contact done. Home/Gallery missing |
| **Media System** | ❌ 0% | No upload, no processing |
| **Gallery** | ❌ 5% | Models/DB exist, no UI at all |
| **Analytics** | ❌ 20% | DB exists, routes exist, no UI |
| **Emails** | ❌ 0% | Not configured |

**Overall Progress: ~45%** - Backend-heavy, frontend needs significant work.

---

## 🚀 Deployment

### Option 1: Laravel Forge
```bash
# Push to Git, Forge handles deployment
git push origin main
```

### Option 2: Manual
```bash
composer install --optimize-autoloader --no-dev
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing`)
3. Commit changes (`git commit -m 'Add feature'`)
4. Push to branch (`git push origin feature/amazing`)
5. Open Pull Request

---

## 📝 License

This project is licensed under the MIT License.

Built with ❤️ using Laravel & Vue.
