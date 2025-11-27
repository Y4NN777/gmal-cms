<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMediaRequest;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Inertia\Inertia;

class MediaController extends Controller
{
    /**
     * Display a listing of media files.
     */
    public function index(Request $request)
    {
        $query = Media::query();

        // Search by filename or alt text
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('filename', 'ilike', '%' . $request->search . '%')
                  ->orWhere('original_name', 'ilike', '%' . $request->search . '%')
                  ->orWhere('alt_text', 'ilike', '%' . $request->search . '%');
            });
        }

        // Filter by mime type (images and videos only)
        if ($request->has('type') && $request->type) {
            switch ($request->type) {
                case 'images':
                    $query->where('mime_type', 'like', 'image/%');
                    break;
                case 'videos':
                    $query->where('mime_type', 'like', 'video/%');
                    break;
            }
        } else {
            // By default, only show images and videos
            $query->where(function ($q) {
                $q->where('mime_type', 'like', 'image/%')
                  ->orWhere('mime_type', 'like', 'video/%');
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $media = $query->paginate(24)->through(fn($item) => [
            'id' => $item->id,
            'filename' => $item->filename,
            'original_name' => $item->original_name,
            'mime_type' => $item->mime_type,
            'size' => $item->size,
            'size_human' => $this->formatBytes($item->size),
            'dimensions' => $item->dimensions,
            'alt_text' => $item->alt_text,
            'caption' => $item->caption,
            'url' => $item->url,
            'thumbnail_url' => $item->thumbnail_url,
            'is_image' => str_starts_with($item->mime_type, 'image/'),
            'created_at' => $item->created_at,
        ]);

        return Inertia::render('Admin/Media/Index', [
            'media' => $media,
            'filters' => $request->only(['search', 'type', 'sort_by', 'sort_order']),
            'user' => auth()->user(),
        ]);
    }

    /**
     * Store a newly uploaded media file.
     */
    public function store(StoreMediaRequest $request)
    {
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();

        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        // Determine storage path
        $year = date('Y');
        $month = date('m');
        $storagePath = "media/{$year}/{$month}";

        // Store original file
        $path = $file->storeAs("public/{$storagePath}", $filename);
        $publicPath = str_replace('public/', '', $path);

        // Process image if it's an image
        $dimensions = null;
        $thumbnailPath = null;

        if (str_starts_with($mimeType, 'image/')) {
            try {
                $image = Image::read($file);
                $dimensions = [
                    'width' => $image->width(),
                    'height' => $image->height(),
                ];

                // Create thumbnail (300x300)
                $thumbnailFilename = 'thumb_' . $filename;
                $thumbnailFullPath = storage_path("app/public/{$storagePath}/{$thumbnailFilename}");
                
                $thumbnail = Image::read($file);
                $thumbnail->scale(width: 300, height: 300);
                $thumbnail->save($thumbnailFullPath);
                
                $thumbnailPath = "{$storagePath}/{$thumbnailFilename}";

                // Optimize original if too large
                if ($dimensions['width'] > 2000 || $dimensions['height'] > 2000) {
                    $optimized = Image::read(storage_path("app/public/{$publicPath}"));
                    $optimized->scale(width: 2000);
                    $optimized->save(storage_path("app/public/{$publicPath}"));
                }
            } catch (\Exception $e) {
                // If image processing fails, continue without thumbnail
                \Log::warning("Image processing failed: " . $e->getMessage());
            }
        }

        // Create media record
        $media = Media::create([
            'filename' => $filename,
            'original_name' => $originalName,
            'mime_type' => $mimeType,
            'size' => $size,
            'dimensions' => $dimensions,
            'alt_text' => $request->alt_text,
            'caption' => $request->caption,
            'folder_id' => $request->folder_id,
            'storage_path' => $publicPath,
            'cdn_url' => null, // Set if using CDN
        ]);

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully!',
            'media' => [
                'id' => $media->id,
                'filename' => $media->filename,
                'original_name' => $media->original_name,
                'mime_type' => $media->mime_type,
                'size' => $media->size,
                'url' => $media->url,
                'thumbnail_url' => $thumbnailPath ? Storage::url($thumbnailPath) : $media->url,
                'is_image' => str_starts_with($media->mime_type, 'image/'),
            ],
        ], 201);
    }

    /**
     * Display the specified media.
     */
    public function show(Media $media)
    {
        return response()->json([
            'success' => true,
            'media' => [
                'id' => $media->id,
                'filename' => $media->filename,
                'original_name' => $media->original_name,
                'mime_type' => $media->mime_type,
                'size' => $media->size,
                'size_human' => $this->formatBytes($media->size),
                'dimensions' => $media->dimensions,
                'alt_text' => $media->alt_text,
                'caption' => $media->caption,
                'url' => $media->url,
                'thumbnail_url' => $media->thumbnail_url,
                'is_image' => str_starts_with($media->mime_type, 'image/'),
                'created_at' => $media->created_at,
            ],
        ]);
    }

    /**
     * Update the specified media metadata.
     */
    public function update(Request $request, Media $media)
    {
        $validated = $request->validate([
            'alt_text' => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:500',
        ]);

        $media->update($validated);

        return back()->with('message', 'Media updated successfully!');
    }

    /**
     * Remove the specified media file.
     */
    public function destroy(Media $media)
    {
        // Delete physical file
        if ($media->storage_path) {
            Storage::delete('public/' . $media->storage_path);
            
            // Delete thumbnail if exists
            $pathInfo = pathinfo($media->storage_path);
            $thumbnailPath = $pathInfo['dirname'] . '/thumb_' . $pathInfo['basename'];
            if (Storage::exists('public/' . $thumbnailPath)) {
                Storage::delete('public/' . $thumbnailPath);
            }
        }

        // Delete database record
        $media->delete();

        return back()->with('message', 'Media deleted successfully!');
    }

    /**
     * Bulk delete media files.
     */
    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'media_ids' => 'required|array',
            'media_ids.*' => 'exists:media,id',
        ]);

        $mediaItems = Media::whereIn('id', $validated['media_ids'])->get();

        foreach ($mediaItems as $media) {
            // Delete physical files
            if ($media->storage_path) {
                Storage::delete('public/' . $media->storage_path);
                
                $pathInfo = pathinfo($media->storage_path);
                $thumbnailPath = $pathInfo['dirname'] . '/thumb_' . $pathInfo['basename'];
                if (Storage::exists('public/' . $thumbnailPath)) {
                    Storage::delete('public/' . $thumbnailPath);
                }
            }

            $media->delete();
        }

        return back()->with('message', count($mediaItems) . ' media files deleted successfully!');
    }

    /**
     * Format bytes to human-readable size.
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
