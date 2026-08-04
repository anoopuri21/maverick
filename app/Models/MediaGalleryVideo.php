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
        // If an explicit thumbnail was uploaded, use it.
        if (! empty($this->thumbnail_url)) {
            return $this->thumbnail_url;
        }

        $url = trim($this->video_url ?? '');

        // YouTube patterns
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $matches)) {
            return "https://img.youtube.com/vi/{$matches[1]}/maxresdefault.jpg";
        }

        // Just a YouTube video ID (11 chars)
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return "https://img.youtube.com/vi/{$url}/maxresdefault.jpg";
        }

        return asset('assets/images/placeholder.jpg');
    }
}
