<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Concerns\HasMediaAssets;

class Testimonial extends Model
{
    use HasMediaAssets;

    protected $fillable = [
        'name',
        'designation',
        'company',
        'thumbnail_url',
        'video_url',
        'video_type',
        'sort_order',
        'is_active',
        'thumbnail_url_asset_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get properly formatted embed URL for any YouTube/Vimeo URL
     */
    public function getEmbedUrlAttribute(): string
    {
        return youtube_embed_url($this->video_url) ?? '';
    }

    /**
     * Auto-generate thumbnail from YouTube if not provided
     */
    public function getAutoThumbnailAttribute(): string
    {
        return youtube_thumbnail_url($this->video_url, $this->thumbnail_url)
            ?: asset('assets/images/homepage/mba.jpg');
    }
}