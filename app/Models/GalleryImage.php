<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryImage extends Model
{
    protected $fillable = [
        'album_id',
        'media_id',
        'caption',
        'alt_text',
        'display_order',
        'metadata',
    ];

    protected $casts = [
        'display_order' => 'integer',
        'metadata' => 'array',
    ];

    // Relationships
    public function album(): BelongsTo
    {
        return $this->belongsTo(GalleryAlbum::class, 'album_id');
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    // Scopes
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    // Accessors
    public function getImageUrlAttribute(): ?string
    {
        if ($this->media && $this->media->file_path) {
            return asset('storage/' . $this->media->file_path);
        }
        return null;
    }
}
