<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Concerns\EnsuresUniqueSlug;
use App\Concerns\HasMediaAssets;

class BlogPost extends Model
{
    use EnsuresUniqueSlug;
    use HasFactory;
    use HasMediaAssets;

    protected $fillable = [
        'legacy_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image_url',
        'featured_image_alt',
        'category',
        'tags',
        'author_name',
        'author_avatar_url',
        'author_bio',
        'published_at',
        'reading_time_minutes',
        'is_featured',
        'meta_title',
        'meta_description',
        'featured_image_url_asset_id',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (BlogPost $post) {
            if ($post->is_featured) {
                static::where('is_featured', true)
                    ->where('id', '!=', $post->id ?? 0)
                    ->update(['is_featured' => false]);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->hasPublicSlug();
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function hasImage(): bool
    {
        return !empty($this->featured_image_url);
    }
}
