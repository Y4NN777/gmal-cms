<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class EventController extends Controller
{
    /**
     * Display a listing of events with filters.
     */
    public function index(Request $request)
    {
        $query = Event::with(['category', 'featuredImage']);

        // Apply filters
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'ilike', '%' . $request->search . '%')
                  ->orWhere('description', 'ilike', '%' . $request->search . '%');
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $events = $query->paginate(15)->through(fn($event) => [
            'id' => $event->id,
            'title' => $event->title,
            'slug' => $event->slug,
            'excerpt' => $event->excerpt,
            'status' => $event->status,
            'event_date' => $event->event_date,
            'location' => $event->location,
            'is_featured' => $event->is_featured,
            'category' => $event->category ? [
                'id' => $event->category->id,
                'name' => $event->category->name,
                'slug' => $event->category->slug,
                'color' => $event->category->color,
            ] : null,
            'featured_image' => $event->featuredImage?->url,
            'created_at' => $event->created_at,
        ]);

        $categories = Category::all(['id', 'name', 'color']);

        return Inertia::render('Admin/Events/Index', [
            'user' => $request->user(),
            'events' => $events,
            'categories' => $categories,
            'filters' => $request->only(['status', 'category', 'search', 'sort_by', 'sort_order']),
        ]);
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        $categories = Category::all(['id', 'name', 'color']);

        return Inertia::render('Admin/Events/Create', [
            'categories' => $categories,
            'user' => auth()->user(),
        ]);
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'excerpt' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'event_date' => 'required|date',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,archived,cancelled',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        $validated['created_by'] = auth()->id() ?? 1;
        $validated['updated_by'] = auth()->id() ?? 1;

        $event = Event::create($validated);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        $event->load(['category', 'featuredImage', 'creator', 'updater', 'donations']);

        return Inertia::render('Admin/Events/Show', [
            'user' => request()->user(),
            'event' => [
                'id' => $event->id,
                'title' => $event->title,
                'slug' => $event->slug,
                'description' => $event->description,
                'excerpt' => $event->excerpt,
                'short_description' => $event->short_description,
                'status' => $event->status,
                'event_date' => $event->event_date,
                'start_date' => $event->start_date,
                'end_date' => $event->end_date,
                'location' => $event->location,
                'is_featured' => $event->is_featured,
                'meta_title' => $event->meta_title,
                'meta_description' => $event->meta_description,
                'category' => $event->category,
                'featured_image_url' => $event->featuredImage?->url,
                'creator' => $event->creator,
                'updater' => $event->updater,
                'donations_count' => $event->donations->count(),
                'donations_total' => $event->donations->sum('amount'),
                'created_at' => $event->created_at,
                'updated_at' => $event->updated_at,
            ],
            'user' => request()->user(),
        ]);
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        $categories = Category::all(['id', 'name', 'color']);
        $event->load('featuredImage');

        return Inertia::render('Admin/Events/Edit', [
            'event' => [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'excerpt' => $event->excerpt,
                'short_description' => $event->short_description,
                'category_id' => $event->category_id,
                'event_date' => $event->event_date,
                'start_date' => $event->start_date,
                'end_date' => $event->end_date,
                'location' => $event->location,
                'status' => $event->status,
                'is_featured' => $event->is_featured,
                'featured_image_id' => $event->featured_image_id,
                'meta_title' => $event->meta_title,
                'meta_description' => $event->meta_description,
                'featured_image' => $event->featuredImage ? [
                    'id' => $event->featuredImage->id,
                    'url' => $event->featuredImage->url,
                    'filename' => $event->featuredImage->filename,
                ] : null,
            ],
            'categories' => $categories,
            'user' => auth()->user(),
        ]);
    }

    /**
     * Update the specified event.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'excerpt' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'event_date' => 'required|date',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,archived,cancelled',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
        ]);

        $validated['updated_by'] = auth()->id() ?? 1;

        $event->update($validated);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified event.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event deleted successfully!');
    }

    /**
     * Bulk update event status.
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'event_ids' => 'required|array',
            'event_ids.*' => 'exists:events,id',
            'status' => 'required|in:draft,published,archived,cancelled',
        ]);

        Event::whereIn('id', $validated['event_ids'])
            ->update([
                'status' => $validated['status'],
                'updated_by' => auth()->id() ?? 1,
            ]);

        return back()->with('success', 'Events updated successfully!');
    }
}
