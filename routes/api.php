<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\ContactController;

/*
|--------------------------------------------------------------------------
| API Routes (Version 1)
|--------------------------------------------------------------------------
|
| Public API endpoints for the GiveMeALift CMS.
| Base URL: /api/v1/
|
*/

Route::prefix('v1')->group(function () {
    
    // Authentication Endpoints (Admin/Editor Login Only - No Public Registration)
    Route::post('/auth/login', [AuthController::class, 'login']);
    
    // Protected Authentication Endpoints
    Route::middleware('auth:api')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::post('/auth/refresh', [AuthController::class, 'refresh']);
        Route::post('/auth/change-password', [AuthController::class, 'changePassword']);
    });
    
    // Events Endpoints
    Route::prefix('events')->group(function () {
        Route::get('/', [EventController::class, 'index']); // List events with pagination
        Route::get('/categories', function () {
            return response()->json([
                'success' => true,
                'data' => \App\Models\Category::all(),
            ]);
        }); // Get all categories
        Route::get('/upcoming', [EventController::class, 'upcoming']); // Get upcoming events
        Route::get('/featured', [EventController::class, 'featured']); // Get featured events
        Route::get('/category/{slug}', [EventController::class, 'byCategory']); // Events by category
        Route::get('/{id}', [EventController::class, 'show']); // Get specific event
    });

    // Testimonials Endpoints
    Route::prefix('testimonials')->group(function () {
        Route::get('/', [TestimonialController::class, 'index']); // List approved testimonials
        Route::get('/featured', [TestimonialController::class, 'featured']); // Get featured testimonials
        Route::post('/', [TestimonialController::class, 'store']); // Submit new testimonial
        Route::get('/{id}', [TestimonialController::class, 'show']); // Get specific testimonial
    });

    // Analytics Endpoints - REMOVED FROM PUBLIC API
    // These are sensitive and should only be accessed by authenticated admin users
    // See admin routes for analytics access

    // Contact Endpoints
    Route::post('/contact', [ContactController::class, 'store']); // Submit contact message
    
});
