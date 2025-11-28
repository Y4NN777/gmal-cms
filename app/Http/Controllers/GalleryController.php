<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GalleryController extends Controller
{
    /**
     * Display all gallery images in a stream (Option 2: Instagram/Pinterest style)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 24);
        $albumFilter = $request->get('album');

        $query = GalleryImage::with(['media', 'album'])
            ->whereHas('media') // Only images with valid media
            ->ordered();

        // Filter by album if specified
        if ($albumFilter) {
            $query->whereHas('album', fn($q) => $q->where('slug', $albumFilter));
        }

        $images = $query->paginate($perPage)->through(fn($image) => [
            'id' => $image->id,
            'caption' => $image->caption,
            'alt_text' => $image->alt_text,
            'image_url' => $image->image_url,
            'album' => $image->album ? [
                'id' => $image->album->id,
                'title' => $image->album->title,
                'slug' => $image->album->slug,
            ] : null,
        ]);

        // Get all albums for filter dropdown
        $albums = GalleryAlbum::withCount('images')
            ->orderBy('title')
            ->get()
            ->map(fn($album) => [
                'id' => $album->id,
                'title' => $album->title,
                'slug' => $album->slug,
                'images_count' => $album->images_count,
            ]);

        return Inertia::render('Gallery/Index', [
            'images' => $images,
            'albums' => $albums,
            'currentAlbum' => $albumFilter,
        ]);
    }

    /**
     * Display list of all albums
     */
    public function albums()
    {
        $albums = GalleryAlbum::with('coverImage')
            ->withCount('images')
            ->ordered()
            ->get()
            ->map(fn($album) => [
                'id' => $album->id,
                'title' => $album->title,
                'slug' => $album->slug,
                'description' => $album->description,
                'is_featured' => $album->is_featured,
                'images_count' => $album->images_count,
                'cover_image_url' => $album->coverImage?->url ?? $album->images()->first()?->image_url,
            ]);

        return Inertia::render('Gallery/Albums', [
            'albums' => $albums,
        ]);
    }

    /**
     * Display a specific album with all its images
     */
    public function show(string $slug)
    {
        $album = GalleryAlbum::with(['coverImage'])
            ->where('slug', $slug)
            ->firstOrFail();

        $images = $album->images()
            ->with('media')
            ->ordered()
            ->get()
            ->map(fn($image) => [
                'id' => $image->id,
                'caption' => $image->caption,
                'alt_text' => $image->alt_text,
                'image_url' => $image->image_url,
            ]);

        return Inertia::render('Gallery/Show', [
            'album' => [
                'id' => $album->id,
                'title' => $album->title,
                'slug' => $album->slug,
                'description' => $album->description,
                'cover_image_url' => $album->coverImage?->url,
            ],
            'images' => $images,
        ]);
    }
}
