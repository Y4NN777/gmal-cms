<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Media extends Model
{
    protected $fillable = [
        'filename',
        'original_name',
        'mime_type',
        'size',
        'dimensions',
        'alt_text',
        'caption',
        'folder_id',
        'storage_path',
        'cdn_url',
    ];

    protected $casts = [
        'dimensions' => 'array',
    ];

    // Relationships
    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'featured_image_id');
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class, 'avatar_id');
    }

    public function galleryAlbumCovers(): HasMany
    {
        return $this->hasMany(GalleryAlbum::class, 'cover_image_id');
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(GalleryImage::class, 'media_id');
    }

    // Accessors
    public function getUrlAttribute(): string
    {
        return $this->cdn_url ?? asset('storage/' . $this->storage_path);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->is_image) {
            return null;
        }

        $pathInfo = pathinfo($this->storage_path);
        $thumbnailPath = $pathInfo['dirname'] . '/thumb_' . $pathInfo['basename'];
        
        if (file_exists(storage_path('app/public/' . $thumbnailPath))) {
            return asset('storage/' . $thumbnailPath);
        }

        return $this->url;
    }

    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }
}
