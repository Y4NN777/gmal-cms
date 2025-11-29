<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use App\Models\ContactMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set application locale from session
        if (session()->has('locale')) {
            $locale = session('locale');
            if (in_array($locale, ['en', 'fr'])) {
                app()->setLocale($locale);
            }
        }

        // Share unread contact messages count with all Inertia views
        Inertia::share([
            'unreadContactsCount' => function () {
                return ContactMessage::whereNull('read_at')->count();
            },
        ]);
    }
}
