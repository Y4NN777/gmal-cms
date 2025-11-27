<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use App\Models\GalleryAlbum;
use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;

class GalleryImageController extends Controller
{
    /**
     * Upload images to an album
     */
    public function upload(Request $request)
    {
        $request->validate([
            'album_id' => 'required|exists:gallery_albums,id',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,jpg,png,gif,webp|max:10240',
        ]);

        $album = GalleryAlbum::findOrFail($request->album_id);
        $uploadedImages = [];

        $imageFiles = $request->file('images');
        foreach ($imageFiles as $file) {
            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $year = date('Y');
            $month = date('m');
            $path = "media/{$year}/{$month}/{$filename}";

            // Process and optimize image
            $image = Image::read($file);
            
            // Get original dimensions
            $width = $image->width();
            $height = $image->height();
            
            // Resize if too large (max 2000px on longest side)
            if ($width > 2000 || $height > 2000) {
                $image->scale(width: $width > $height ? 2000 : null, height: $height > $width ? 2000 : null);
            }
            
            // Save to storage
            Storage::put($path, $image->encode());
            
            // Create thumbnail
            $thumbImage = Image::read($file);
            $thumbImage->cover(300, 300);
            $thumbPath = "media/{$year}/{$month}/thumb_{$filename}";
            Storage::put($thumbPath, $thumbImage->encode());

            // Create media record
            $media = \App\Models\Media::create([
                'filename' => $filename,
                'original_filename' => $file->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'uploaded_by' => auth()->id(),
                'is_image' => true,
                'width' => $image->width(),
                'height' => $image->height(),
            ]);

            // Link media to gallery album
            $galleryImage = GalleryImage::create([
                'album_id' => $album->id,
                'media_id' => $media->id,
                'display_order' => GalleryImage::where('album_id', $album->id)->max('display_order') + 1,
            ]);

            $uploadedImages[] = $galleryImage->load('media');
        }

        return back()->with('success', count($uploadedImages) . ' image(s) uploaded successfully');
    }

    /**
     * Delete a gallery image
     */
    public function destroy(string $id)
    {
        $image = GalleryImage::findOrFail($id);
        
        // Just remove the link from gallery, don't delete the media file
        // (it might be used elsewhere)
        $image->delete();

        return redirect()->back()->with('success', 'Image removed from album.');
    }

    /**
     * Update image caption and order
     */
    public function update(Request $request, string $id)
    {
        $image = GalleryImage::findOrFail($id);

        $validated = $request->validate([
            'caption' => 'nullable|string|max:500',
            'display_order' => 'nullable|integer|min:0',
        ]);

        $image->update($validated);

        return back()->with('success', 'Image updated successfully');
    }
}
