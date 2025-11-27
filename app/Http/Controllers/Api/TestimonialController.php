<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TestimonialController extends Controller
{
    /**
     * Display a listing of approved testimonials.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Testimonial::with('avatar')
            ->approved()
            ->ordered();

        // Filter by rating
        if ($request->has('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }

        // Featured only
        if ($request->has('featured') && $request->featured === 'true') {
            $query->featured();
        }

        $perPage = $request->get('per_page', 10);
        $testimonials = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $testimonials->items(),
            'meta' => [
                'pagination' => [
                    'current_page' => $testimonials->currentPage(),
                    'total_pages' => $testimonials->lastPage(),
                    'per_page' => $testimonials->perPage(),
                    'total' => $testimonials->total(),
                ]
            ],
            'message' => 'Testimonials retrieved successfully'
        ]);
    }

    /**
     * Store a new testimonial submission (pending approval).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'position' => 'nullable|string|max:100',
            'organization' => 'nullable|string|max:100',
            'content' => 'required|string|min:50',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $validated['status'] = 'pending'; // All public submissions start as pending

        $testimonial = Testimonial::create($validated);

        return response()->json([
            'success' => true,
            'data' => $testimonial,
            'message' => 'Thank you! Your testimonial has been submitted for review.'
        ], 201);
    }

    /**
     * Display the specified approved testimonial.
     */
    public function show(string $id): JsonResponse
    {
        $testimonial = Testimonial::with('avatar')
            ->approved()
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $testimonial,
            'message' => 'Testimonial retrieved successfully'
        ]);
    }

    /**
     * Get featured testimonials.
     */
    public function featured(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 6);
        
        $testimonials = Testimonial::with('avatar')
            ->approved()
            ->featured()
            ->ordered()
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $testimonials,
            'message' => 'Featured testimonials retrieved successfully'
        ]);
    }
}
