<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $albums = GalleryAlbum::withCount('images')
            ->latest()
            ->get();

        return Inertia::render('Admin/Gallery/Index', [
            'albums' => $albums,
            'user' => auth()->user(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Gallery/Create', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);

        $album = GalleryAlbum::create($validated);

        return redirect()->route('gallery.edit', $album->id)
            ->with('success', 'Album created successfully. You can now add images.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $album = GalleryAlbum::with('images')->findOrFail($id);

        return Inertia::render('Admin/Gallery/Show', [
            'album' => $album,
            'user' => auth()->user(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $album = GalleryAlbum::findOrFail($id);
        $images = $album->images()->with('media')->latest()->get()->map(function ($image) {
            return [
                'id' => $image->id,
                'caption' => $image->caption,
                'image_url' => $image->image_url,
                'display_order' => $image->display_order,
            ];
        });

        return Inertia::render('Admin/Gallery/Edit', [
            'album' => $album,
            'images' => $images,
            'user' => auth()->user(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $album = GalleryAlbum::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_featured' => 'boolean',
        ]);

        $album->update($validated);

        return redirect()->route('gallery.edit', $album->id)
            ->with('success', 'Album updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $album = GalleryAlbum::findOrFail($id);
        
        // Delete all gallery_images links (cascade will handle this)
        // Media files remain in the media library for potential reuse
        $album->delete();

        return redirect()->route('gallery.index')
            ->with('success', 'Album deleted successfully.');
    }
}
