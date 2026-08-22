<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Concerns\HasMediaAssets;

class MediaGalleryVideo extends Model
{
    use HasMediaAssets;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'video_url',
        'thumbnail_url',
        'thumbnail_url_asset_id',
        'duration',
        'category',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Auto-generate a thumbnail from the video URL (YouTube) when an
     * explicit thumbnail hasn't been uploaded. Mirrors Testimonial::auto_thumbnail.
     */
    public function getAutoThumbnailAttribute(): string
    {
        return youtube_thumbnail_url($this->video_url, $this->thumbnail_url)
            ?: asset('assets/images/placeholder.jpg');
    }
}
