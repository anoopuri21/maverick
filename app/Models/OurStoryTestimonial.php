<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\PublicContentCache;
use App\Support\TestimonialPages;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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
            $model->name = trim((string) ($model->name ?? ''));
            $model->organisation = self::nullableTrim($model->organisation);
            $model->position = self::nullableTrim($model->position);
            $model->country = self::nullableTrim($model->country);
            $model->photo = self::nullableTrim($model->photo);
            $model->testimonial = self::sanitizeQuote($model->testimonial);
            $model->rating = max(1, min(5, (int) ($model->rating ?? 5)));

            if ($model->sort_order === null) {
                $model->sort_order = ((int) static::withTrashed()->max('sort_order')) + 1;
            }

            if (array_key_exists('pages', $model->getAttributes()) || is_array($model->pages)) {
                $model->pages = TestimonialPages::normalize($model->pages);
            }
        });

        static::saved(static fn () => PublicContentCache::flush());
        static::deleted(static fn () => PublicContentCache::flush());
        static::restored(static fn () => PublicContentCache::flush());
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
     * Filter active testimonials assigned to a specific display page key.
     *
     * Intentionally named `assignedToPage` instead of `forPage` so it never
     * collides with Laravel's `Query\Builder::forPage($page, $perPage)` inside `paginate()`.
     */
    public function scopeAssignedToPage(Builder $query, string $pageKey): Builder
    {
        $cleanKey = TestimonialPages::sanitizeKey($pageKey);

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
        $cleanKey = TestimonialPages::sanitizeKey($pageKey);
        if ($cleanKey === '') {
            return false;
        }

        return in_array($cleanKey, TestimonialPages::normalize($this->pages), true);
    }

    /**
     * Clean author subtitle resolved from DB columns (`position · organisation, country`).
     */
    public function getDisplayRoleAttribute(): string
    {
        $primary = collect([$this->position, $this->organisation])
            ->map(static fn ($value): string => trim((string) $value))
            ->filter()
            ->implode(' · ');

        $country = trim((string) ($this->country ?? ''));

        if ($primary !== '' && $country !== '' && ! str_contains(mb_strtolower($primary), mb_strtolower($country))) {
            return $primary . ', ' . $country;
        }

        return $primary !== '' ? $primary : $country;
    }

    /**
     * Sanitized plain-text quote preserving paragraph breaks without HTML tags.
     */
    public function getPlainQuoteAttribute(): string
    {
        return self::sanitizeQuote($this->testimonial);
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

        return media_url($photo);
    }

    /**
     * Strip HTML tags/scripts safely, decode HTML entities, normalize line breaks,
     * and remove wrapping quotation marks so quotes are 100% XSS-safe plain text.
     */
    public static function sanitizeQuote(?string $raw): string
    {
        $text = (string) ($raw ?? '');
        if ($text === '') {
            return '';
        }

        $withoutScripts = preg_replace('#<(script|style)\b[^>]*>.*?</\1>#is', '', $text) ?? $text;
        $withBreaks = preg_replace('/<\s*br\s*\/?\s*>/i', "\n", $withoutScripts) ?? $withoutScripts;
        $withParagraphs = preg_replace('/<\/\s*p\s*>\s*<\s*p[^>]*>/i', "\n\n", $withBreaks) ?? $withBreaks;
        $decoded = html_entity_decode(strip_tags($withParagraphs), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $normalizedLines = preg_replace('/[ \t]+/u', ' ', str_replace(["\r\n", "\r"], "\n", $decoded)) ?? $decoded;
        $collapsedParagraphs = preg_replace("/\n{3,}/u", "\n\n", $normalizedLines) ?? $normalizedLines;

        return trim($collapsedParagraphs, " \t\n\r\0\x0B\"'“”");
    }

    private static function nullableTrim(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed !== '' ? $trimmed : null;
    }
}
