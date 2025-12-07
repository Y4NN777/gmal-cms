<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TestimonialController extends Controller
{
    /**
     * Display a listing of testimonials with filtering.
     */
    public function index(Request $request)
    {
        $query = Testimonial::with('avatar');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by featured
        if ($request->has('featured') && $request->featured === 'true') {
            $query->where('is_featured', true);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'ilike', '%' . $request->search . '%')
                  ->orWhere('content', 'ilike', '%' . $request->search . '%')
                  ->orWhere('organization', 'ilike', '%' . $request->search . '%');
            });
        }

        $testimonials = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->through(fn($testimonial) => [
                'id' => $testimonial->id,
                'name' => $testimonial->name,
                'email' => $testimonial->email,
                'position' => $testimonial->position,
                'organization' => $testimonial->organization,
                'content' => $testimonial->content,
                'rating' => $testimonial->rating,
                'status' => $testimonial->status,
                'is_featured' => $testimonial->is_featured,
                'avatar' => $testimonial->avatar?->url,
                'approved_at' => $testimonial->status === 'approved' ? $testimonial->updated_at : null,
                'created_at' => $testimonial->created_at,
            ]);

        $stats = [
            'pending' => Testimonial::where('status', 'pending')->count(),
            'approved' => Testimonial::approved()->count(),
            'rejected' => Testimonial::where('status', 'rejected')->count(),
        ];

        return Inertia::render('Admin/Testimonials/Index', [
            'user' => $request->user(),
            'testimonials' => $testimonials,
            'stats' => $stats,
            'filters' => $request->only(['status', 'featured', 'search']),
        ]);
    }

    /**
     * Show the form for creating a new testimonial.
     */
    public function create()
    {
        return Inertia::render('Admin/Testimonials/Create');
    }

    /**
     * Store a newly created testimonial.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:255',
            'position' => 'nullable|string|max:100',
            'organization' => 'nullable|string|max:100',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'status' => 'required|in:pending,approved,rejected',
            'is_featured' => 'boolean',
        ]);

        $testimonial = Testimonial::create($validated);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Testimonial created successfully!');
    }

    /**
     * Display the specified testimonial.
     */
    public function show(Testimonial $testimonial)
    {
        $testimonial->load(['avatar', 'approver']);

        return Inertia::render('Admin/Testimonials/Show', [
            'testimonial' => [
                'id' => $testimonial->id,
                'name' => $testimonial->name,
                'email' => $testimonial->email,
                'position' => $testimonial->position,
                'organization' => $testimonial->organization,
                'content' => $testimonial->content,
                'rating' => $testimonial->rating,
                'status' => $testimonial->status,
                'is_featured' => $testimonial->is_featured,
                'display_order' => $testimonial->display_order,
                'avatar' => $testimonial->avatar ? [
                    'url' => $testimonial->avatar->url,
                    'alt' => $testimonial->avatar->alt_text
                ] : null,
                'approver' => $testimonial->approver ? [
                    'name' => $testimonial->approver->name
                ] : null,
                'created_at' => $testimonial->created_at,
                'updated_at' => $testimonial->updated_at,
            ]
        ]);
    }

    /**
     * Show the form for editing the specified testimonial.
     */
    public function edit(Testimonial $testimonial)
    {
        return Inertia::render('Admin/Testimonials/Edit', [
            'testimonial' => $testimonial,
        ]);
    }

    /**
     * Update the specified testimonial.
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:255',
            'position' => 'nullable|string|max:100',
            'organization' => 'nullable|string|max:100',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'status' => 'required|in:pending,approved,rejected',
            'is_featured' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        $testimonial->update($validated);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Testimonial updated successfully!');
    }

    /**
     * Remove the specified testimonial.
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Testimonial deleted successfully!');
    }

    /**
     * Approve a testimonial.
     */
    public function approve(Testimonial $testimonial)
    {
        $testimonial->update([
            'status' => 'approved',
            'approved_by' => auth()->id() ?? 1,
        ]);

        return back()->with('success', 'Testimonial approved successfully!');
    }

    /**
     * Reject a testimonial.
     */
    public function reject(Testimonial $testimonial)
    {
        $testimonial->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Testimonial rejected!');
    }

    /**
     * Bulk approve testimonials.
     */
    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'testimonial_ids' => 'required|array',
            'testimonial_ids.*' => 'exists:testimonials,id',
        ]);

        Testimonial::whereIn('id', $validated['testimonial_ids'])
            ->update([
                'status' => 'approved',
                'approved_by' => auth()->id() ?? 1,
            ]);

        return back()->with('success', 'Testimonials approved successfully!');
    }

    /**
     * Toggle the featured status of a testimonial.
     */
    public function toggleFeature(Testimonial $testimonial)
    {
        $testimonial->update([
            'is_featured' => !$testimonial->is_featured,
        ]);

        $message = $testimonial->is_featured 
            ? 'Testimonial featured successfully!' 
            : 'Testimonial unfeatured successfully!';

        return back()->with('success', $message);
    }
}
