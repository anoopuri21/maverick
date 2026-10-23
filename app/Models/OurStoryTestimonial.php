<?php

namespace App\Models;

use App\Support\PublicContentCache;
use App\Support\TestimonialPages;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class OurStoryTestimonial extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'organisation',
        'position',
        'country',
        'rating',
        'testimonial',
        'photo',
        'media_asset_id',
        'pages',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'rating' => 'integer',
        'pages' => 'array',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $model): void {
            if (array_key_exists('pages', $model->getAttributes()) || is_array($model->pages)) {
                $model->pages = TestimonialPages::normalize($model->pages);
            }
        });

        static::saved(fn () => PublicContentCache::flush());
        static::deleted(fn () => PublicContentCache::flush());
        static::restored(fn () => PublicContentCache::flush());
    }

    public function mediaAsset(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Filter active testimonials assigned to a specific page key.
     */
    public function scopeForPage(Builder $query, string $pageKey): Builder
    {
        $cleanKey = trim($pageKey);

        if ($cleanKey === '') {
            return $query->whereRaw('1 = 0');
        }

        return $query
            ->active()
            ->whereNotNull('pages')
            ->whereJsonContains('pages', $cleanKey)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    /**
     * Check whether this testimonial is assigned to the given page key.
     */
    public function hasPage(string $pageKey): bool
    {
        $cleanKey = trim($pageKey);
        if ($cleanKey === '') {
            return false;
        }

        return in_array($cleanKey, TestimonialPages::normalize($this->pages), true);
    }

    /**
     * Resolve clean author subtitle from DB columns (`position · organisation, country`).
     */
    public function getDisplayRoleAttribute(): string
    {
        $primary = collect([$this->position, $this->organisation])
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->implode(' · ');

        $country = trim((string) $this->country);

        if ($primary !== '' && $country !== '' && ! str_contains(strtolower($primary), strtolower($country))) {
            return $primary . ', ' . $country;
        }

        return $primary !== '' ? $primary : $country;
    }

    /**
     * Clean quote text preserving paragraph breaks without raw HTML tags.
     */
    public function getPlainQuoteAttribute(): string
    {
        $raw = (string) ($this->testimonial ?? '');
        $withBreaks = preg_replace('/<\s*br\s*\/?\s*>/i', "\n", $raw) ?? $raw;
        $withParagraphs = preg_replace('/<\/\s*p\s*>\s*<\s*p[^>]*>/i', "\n\n", $withBreaks) ?? $withBreaks;
        $decoded = html_entity_decode(strip_tags($withParagraphs), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $normalizedLines = preg_replace("/[ \t]+/u", ' ', str_replace(["\r\n", "\r"], "\n", $decoded)) ?? $decoded;
        $collapsedParagraphs = preg_replace("/\n{3,}/u", "\n\n", $normalizedLines) ?? $normalizedLines;

        return trim($collapsedParagraphs, " \t\n\r\0\x0B\"“”");
    }

    /**
     * Resolve portrait image URL strictly from attached MediaAsset or `photo` DB column.
     */
    public function getResolvedPhotoUrlAttribute(): ?string
    {
        $photo = trim((string) ($this->mediaAsset?->url ?: $this->photo ?: ''));

        if ($photo === '') {
            return null;
        }

        return Str::startsWith($photo, ['http://', 'https://', '/', 'data:'])
            ? $photo
            : '/' . ltrim($photo, '/');
    }
}
