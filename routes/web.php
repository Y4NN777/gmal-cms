<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\Event;
use App\Models\Category;
use Inertia\Inertia;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Locale Switcher
Route::post('/locale', function (\Illuminate\Http\Request $request) {
    $locale = $request->input('locale', 'en');
    
    if (in_array($locale, ['en', 'fr'])) {
        session(['locale' => $locale]);
    }
    
    return back();
});

// Public Pages
Route::get('/events', function () {
    return Inertia::render('Events/Index', [
        'events' => Event::with(['category', 'featuredImage'])
            ->where('status', 'published')
            ->orderBy('event_date', 'asc')
            ->get()
            ->map(fn($event) => [
                'id' => $event->id,
                'title' => $event->title,
                'slug' => $event->slug,
                'description' => $event->description,
                'event_date' => $event->event_date,
                'event_time' => $event->event_time,
                'location' => $event->location,
                'is_featured' => $event->is_featured,
                'featured_image' => $event->featuredImage?->url,
                'category' => $event->category ? [
                    'id' => $event->category->id,
                    'name' => $event->category->name,
                    'slug' => $event->category->slug,
                ] : null,
            ]),
        'categories' => Category::orderBy('name')
            ->get()
            ->map(fn($cat) => [
                'id' => $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug,
            ]),
    ]);
})->name('events.index');
Route::get('/events/{slug}', function (string $slug) {
    $event = Event::with(['category', 'featuredImage', 'creator'])
        ->where('slug', $slug)
        ->where('status', 'published')
        ->firstOrFail();

    return Inertia::render('Events/Show', [
        'event' => [
            'id' => $event->id,
            'title' => $event->title,
            'slug' => $event->slug,
            'description' => $event->description,
            'event_date' => $event->event_date,
            'event_time' => $event->event_time,
            'location' => $event->location,
            'donation_goal' => $event->donation_goal,
            'is_featured' => $event->is_featured,
            'featured_image' => $event->featuredImage?->url,
            'category' => $event->category ? [
                'id' => $event->category->id,
                'name' => $event->category->name,
                'slug' => $event->category->slug,
            ] : null,
            'creator' => $event->creator ? [
                'name' => $event->creator->name,
            ] : null,
        ],
    ]);
})->name('events.show');

Route::get('/about', fn() => Inertia::render('About/Index'))->name('about');
Route::get('/contact', fn() => Inertia::render('Contact/Index'))->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store']);

// Donation Pages
Route::get('/donate', fn() => Inertia::render('Donate/Index'))->name('donate');
Route::get('/donate/thank-you', fn() => Inertia::render('Donate/ThankYou'))->name('donate.thank-you');

// PayPal IPN Webhook (must be publicly accessible, no auth middleware)
Route::post('/webhooks/paypal-ipn', [App\Http\Controllers\DonationController::class, 'handlePayPalIPN'])->name('webhooks.paypal-ipn');

// Public Gallery Routes
Route::get('/gallery', [App\Http\Controllers\GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/albums', [App\Http\Controllers\GalleryController::class, 'albums'])->name('gallery.albums');
Route::get('/gallery/{slug}', [App\Http\Controllers\GalleryController::class, 'show'])->name('gallery.show');

// Admin Authentication Routes
Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/admin/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Admin Routes - Protected by authentication middleware
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Donations
    Route::get('/donations', [App\Http\Controllers\Admin\DonationController::class, 'index'])->name('donations.index');

    // Analytics - Protected Admin Only
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/visitors', [App\Http\Controllers\Api\AnalyticsController::class, 'visitors'])->name('visitors');
        Route::get('/donors', [App\Http\Controllers\Api\AnalyticsController::class, 'donors'])->name('donors');
        Route::get('/dashboard', [App\Http\Controllers\Api\AnalyticsController::class, 'dashboard'])->name('dashboard');
        Route::get('/reports', [App\Http\Controllers\Api\AnalyticsController::class, 'reports'])->name('reports');
    });
    
    // Events Management
    Route::resource('events', App\Http\Controllers\Admin\EventController::class);
    Route::post('events/bulk-status', [App\Http\Controllers\Admin\EventController::class, 'bulkUpdateStatus'])->name('events.bulk-status');
    
    // Testimonials Management
    Route::resource('testimonials', App\Http\Controllers\Admin\TestimonialController::class);
    Route::post('testimonials/{testimonial}/approve', [App\Http\Controllers\Admin\TestimonialController::class, 'approve'])->name('testimonials.approve');
    Route::post('testimonials/{testimonial}/reject', [App\Http\Controllers\Admin\TestimonialController::class, 'reject'])->name('testimonials.reject');
    Route::post('testimonials/bulk-approve', [App\Http\Controllers\Admin\TestimonialController::class, 'bulkApprove'])->name('testimonials.bulk-approve');
    
    // Media Library Management
    Route::get('media/list', [App\Http\Controllers\Admin\MediaController::class, 'list'])->name('media.list'); // For ImagePicker component
    Route::resource('media', App\Http\Controllers\Admin\MediaController::class);
    Route::post('media/bulk-destroy', [App\Http\Controllers\Admin\MediaController::class, 'bulkDestroy'])->name('media.bulk-destroy');
    
    // Gallery Management
    Route::resource('gallery', App\Http\Controllers\Admin\GalleryController::class);
    Route::post('gallery/images/upload', [App\Http\Controllers\Admin\GalleryImageController::class, 'upload'])->name('gallery.images.upload');
    Route::delete('gallery/images/{id}', [App\Http\Controllers\Admin\GalleryImageController::class, 'destroy'])->name('gallery.images.destroy');
    Route::put('gallery/images/{id}', [App\Http\Controllers\Admin\GalleryImageController::class, 'update'])->name('gallery.images.update');
    
    // Contact Messages Management
    Route::get('contact-messages', [App\Http\Controllers\Admin\ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::post('contact-messages/{id}/mark-read', [App\Http\Controllers\Admin\ContactMessageController::class, 'markAsRead'])->name('contact-messages.mark-read');
    Route::post('contact-messages/{id}/mark-unread', [App\Http\Controllers\Admin\ContactMessageController::class, 'markAsUnread'])->name('contact-messages.mark-unread');
    Route::delete('contact-messages/{id}', [App\Http\Controllers\Admin\ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');
    Route::post('contact-messages/bulk-destroy', [App\Http\Controllers\Admin\ContactMessageController::class, 'bulkDestroy'])->name('contact-messages.bulk-destroy');
    
    // Categories Management
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class)->except(['show', 'create', 'edit']);
    
    // User Management
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['show', 'create', 'edit']);
});
