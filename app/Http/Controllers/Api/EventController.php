<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    /**
     * Display a listing of published events with pagination and filters.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Event::with(['category', 'featuredImage'])
            ->published();

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->where('event_date', '>=', $request->from_date);
        }

        if ($request->has('to_date')) {
            $query->where('event_date', '<=', $request->to_date);
        }

        // Filter upcoming/past events
        if ($request->has('filter')) {
            if ($request->filter === 'upcoming') {
                $query->upcoming();
            } elseif ($request->filter === 'past') {
                $query->past();
            }
        }

        // Search
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'ilike', '%' . $request->search . '%')
                  ->orWhere('description', 'ilike', '%' . $request->search . '%')
                  ->orWhere('location', 'ilike', '%' . $request->search . '%');
            });
        }

        // Featured only
        if ($request->has('featured') && $request->featured === 'true') {
            $query->where('is_featured', true);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'event_date');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 15);
        $events = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $events->items(),
            'meta' => [
                'pagination' => [
                    'current_page' => $events->currentPage(),
                    'total_pages' => $events->lastPage(),
                    'per_page' => $events->perPage(),
                    'total' => $events->total(),
                    'from' => $events->firstItem(),
                    'to' => $events->lastItem(),
                ]
            ],
            'message' => 'Events retrieved successfully'
        ]);
    }

    /**
     * Display the specified event by ID or slug.
     */
    public function show(string $id): JsonResponse
    {
        $event = Event::with(['category', 'featuredImage', 'creator'])
            ->where('id', $id)
            ->orWhere('slug', $id)
            ->published()
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $event,
            'message' => 'Event retrieved successfully'
        ]);
    }

    /**
     * Get upcoming events.
     */
    public function upcoming(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);
        
        $events = Event::with(['category', 'featuredImage'])
            ->published()
            ->upcoming()
            ->orderBy('event_date', 'asc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $events,
            'message' => 'Upcoming events retrieved successfully'
        ]);
    }

    /**
     * Get featured events.
     */
    public function featured(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 6);
        
        $events = Event::with(['category', 'featuredImage'])
            ->published()
            ->where('is_featured', true)
            ->orderBy('event_date', 'asc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $events,
            'message' => 'Featured events retrieved successfully'
        ]);
    }

    /**
     * Get events by category.
     */
    public function byCategory(string $categorySlug): JsonResponse
    {
        $events = Event::with(['category', 'featuredImage'])
            ->published()
            ->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            })
            ->orderBy('event_date', 'asc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $events->items(),
            'meta' => [
                'pagination' => [
                    'current_page' => $events->currentPage(),
                    'total_pages' => $events->lastPage(),
                    'per_page' => $events->perPage(),
                    'total' => $events->total(),
                ]
            ],
            'message' => 'Events retrieved successfully'
        ]);
    }
}
