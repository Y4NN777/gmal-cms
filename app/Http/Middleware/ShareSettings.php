<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;
use Inertia\Inertia;

class ShareSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Share settings with all Inertia pages
        Inertia::share([
            'settings' => function () {
                return Setting::getAllGrouped();
            },
        ]);

        return $next($request);
    }
}
