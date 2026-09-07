<?php

namespace App\Models;

use App\Concerns\EnsuresUniqueSlug;
use App\Concerns\HasMediaAssets;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FacultyInsight extends Model
{
    use EnsuresUniqueSlug;
    use HasMediaAssets;

    protected $fillable = [
        'title',
        'slug',
        'badge',
        'excerpt',
        'content',
        'pull_quote',
        'faculty_name',
        'faculty_role',
        'country',
        'faculty_bio',
        'faculty_avatar_url',
        'faculty_avatar_url_asset_id',
        'image_url',
        'image_url_asset_id',
        'hero_image_url',
        'hero_image_url_asset_id',
        'link_url',
        'sort_order',
        'is_active',
        'published_at',
        'reading_time_minutes',
        'meta_title',
        'meta_description',
        'og_image_url',
        'og_image_url_asset_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'published_at' => 'datetime',
        'reading_time_minutes' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->active()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public const CARD_LIMIT = 12;

    /**
     * Single source for the shared faculty-insights cards
     * (home page, masters page, program detail).
     */
    public function scopeCard(Builder $query): Builder
    {
        return $query->select('id', 'title', 'faculty_role', 'country', 'content', 'image_url', 'image_url_asset_id', 'sort_order')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    public function featuredImageUrl(): ?string
    {
        return $this->getMediaUrl('image_url');
    }

    public function avatarUrl(): ?string
    {
        return $this->getMediaUrl('faculty_avatar_url');
    }
}
