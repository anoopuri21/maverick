<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Concerns\EnsuresUniqueSlug;
use App\Concerns\HasMediaAssets;

class Insight extends Model
{
    use EnsuresUniqueSlug;
    use HasFactory;
    use HasMediaAssets;

    protected $fillable = [
        'legacy_id', 'title', 'slug', 'excerpt', 'content',
        'featured_image_url', 'featured_image_alt', 'categories',
        'tags', 'author_name', 'author_avatar_url', 'author_bio',
        'published_at', 'reading_time_minutes', 'is_featured',
        'meta_title', 'meta_description', 'extra',
        'featured_image_url_asset_id',
    ];

    protected $casts = [
        'categories'    => 'array',
        'tags'          => 'array',
        'extra'         => 'array',
        'is_featured'   => 'boolean',
        'published_at'  => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return static::published()->where($field ?? $this->getRouteKeyName(), $value)->firstOrFail();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->hasPublicSlug();
    }

    public function scopeCategory(Builder $query, string $category): Builder
    {
        if (config('database.default') === 'sqlite') {
            return $query->where('categories', 'like', '%"' . $category . '"%');
        }
        return $query->whereJsonContains('categories', $category);
    }

    public function scopeFeaturedIn(Builder $query, string $category): Builder
    {
        return $query->where('is_featured', true)->category($category);
    }

    public function hasImage(): bool
    {
        return !empty($this->featured_image_url);
    }

    public function hasCategory(string $category): bool
    {
        return in_array($category, $this->categories ?? []);
    }

    protected static function boot()
    {
        parent::boot();

        // Enforce: only ONE item per category can be featured at a time.
        // (An item tagged both "blogs" and "news" that is featured will
        // un-feature the current featured item in EACH of its categories.)
        static::saving(function (Insight $insight) {
            if ($insight->is_featured) {
                foreach (($insight->categories ?? []) as $cat) {
                    $query = static::where('is_featured', true)
                        ->where('id', '!=', $insight->id ?? 0);

                    if (config('database.default') === 'sqlite') {
                        $query->where('categories', 'like', '%"' . $cat . '"%');
                    } else {
                        $query->whereJsonContains('categories', $cat);
                    }

                    $query->update(['is_featured' => false]);
                }
            }
        });

        static::saved(function () {
            \Illuminate\Support\Facades\Cache::store('file')->forget('blogs.top_tags');
            \Illuminate\Support\Facades\Cache::store('file')->forget('news.top_tags');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::store('file')->forget('blogs.top_tags');
            \Illuminate\Support\Facades\Cache::store('file')->forget('news.top_tags');
        });
    }
}
