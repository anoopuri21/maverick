<?php

namespace App\Models;

use App\Services\MediaStorageService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaAsset extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hash',
        'original_name',
        'mime_type',
        'size_bytes',
        'width',
        'height',
        'cloudinary_public_id',
        'url',
        'source_url',
        'folder',
        'alt',
        'disk_env',
        'storage_disk',
        'storage_path',
        'used',
        'is_duplicate',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'used' => 'boolean',
        'is_duplicate' => 'boolean',
    ];

    protected static function booted(): void
    {
        // A regular delete remains a soft-delete so assets can be restored.
        // The file is removed only by an intentional Force Delete action.
        static::forceDeleted(function (self $asset): void {
            if (filled($asset->storage_path)) {
                app(MediaStorageService::class)->delete($asset->storage_disk, $asset->storage_path);
            }
        });
    }

    public function ourStoryTestimonials(): HasMany
    {
        return $this->hasMany(OurStoryTestimonial::class);
    }
}
