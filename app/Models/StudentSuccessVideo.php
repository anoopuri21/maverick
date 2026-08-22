<?php

namespace App\Models;

use App\Concerns\HasMediaAssets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentSuccessVideo extends Model
{
    use HasMediaAssets;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'role',
        'youtube_url',
        'thumbnail_url',
        'thumbnail_url_asset_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getEmbedUrlAttribute(): string
    {
        return youtube_embed_url($this->youtube_url, true) ?? '';
    }

    public function getAutoThumbnailAttribute(): string
    {
        return youtube_thumbnail_url($this->youtube_url, $this->getMediaUrl('thumbnail_url'))
            ?: asset('assets/images/placeholder.jpg');
    }

    public function cardPayload(): array
    {
        $url = $this->youtube_url ?? '';
        $thumb = youtube_thumbnail_url($url, $this->getMediaUrl('thumbnail_url'));
        $fallback = youtube_thumbnail_fallback($url);

        return [
            'name' => $this->name ?? '',
            'role' => $this->role ?? '',
            'youtube_url' => $url,
            'embed' => youtube_embed_url($url, true),
            'thumb' => $thumb,
            'thumb_fallback' => ($fallback && $fallback !== $thumb) ? $fallback : null,
        ];
    }
}
