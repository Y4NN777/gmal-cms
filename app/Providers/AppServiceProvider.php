<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Laravel\Passport\Passport;
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
        // Configure Passport token lifetimes
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
        
        // Load encryption keys from storage
        Passport::loadKeysFrom(storage_path());
        
        // Share unread contact messages count with all Inertia views
        Inertia::share([
            'unreadContactsCount' => function () {
                return ContactMessage::whereNull('read_at')->count();
            },
        ]);
    }
}
