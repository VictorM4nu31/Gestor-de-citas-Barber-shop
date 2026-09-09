<?php

namespace App\Models;

use App\Helpers\TranslationHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'filename',
        'original_name',
        'path',
        'thumbnail_path',
        'size',
        'mime_type',
        'alt_text',
        'display_order',
        'is_active',
        'alt_text_en',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'size' => 'integer',
        'display_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope a query to only include active images.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order images by display order and creation date.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('display_order', 'asc')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Scope a query to only include recent images.
     */
    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Get the full URL for the image.
     */
    public function getImageUrlAttribute(): string
    {
        return asset('storage/'.$this->path);
    }

    /**
     * Get the full URL for the thumbnail.
     */
    public function getThumbnailUrlAttribute(): string
    {
        return asset('storage/'.$this->thumbnail_path);
    }

    /**
     * Get the formatted file size.
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }

    /**
     * Get the file extension from the filename.
     */
    public function getFileExtensionAttribute(): string
    {
        return pathinfo($this->filename, PATHINFO_EXTENSION);
    }

    /**
     * Check if the image is a specific type.
     */
    public function isType(string $type): bool
    {
        return str_starts_with($this->mime_type, "image/{$type}");
    }

    /**
     * Get the next display order for new images.
     */
    public static function getNextDisplayOrder(): int
    {
        return static::max('display_order') + 1;
    }

    /**
     * Get translated alt text for the image
     */
    public function getTranslatedAltText(?string $locale = null): string
    {
        return TranslationHelper::getTranslatedAttribute($this, 'alt_text', $locale);
    }

    /**
     * Get all translated attributes for the gallery image
     */
    public function getTranslatedAttributes(?string $locale = null): array
    {
        return [
            'filename' => $this->filename,
            'original_name' => $this->original_name,
            'path' => $this->path,
            'thumbnail_path' => $this->thumbnail_path,
            'size' => $this->size,
            'mime_type' => $this->mime_type,
            'alt_text' => $this->getTranslatedAltText($locale),
            'display_order' => $this->display_order,
            'is_active' => $this->is_active,
        ];
    }
}
