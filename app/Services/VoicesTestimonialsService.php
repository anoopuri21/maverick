<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\OurStoryTestimonial;
use App\Support\PublicContentCache;
use App\Support\TestimonialPages;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Throwable;

class VoicesTestimonialsService
{
    /**
     * Retrieve normalized testimonial items strictly assigned to `$pageKey`.
     *
     * - Only active, non-deleted records whose `pages` array contains `$pageKey` are returned.
     * - Unselected pages NEVER receive the testimonial.
     * - Results are cached in a single array-safe cache entry across all pages
     *   and automatically invalidated whenever any record or setting changes.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function itemsForPage(string $pageKey): Collection
    {
        $cleanPage = TestimonialPages::sanitizeKey($pageKey);
        if ($cleanPage === '') {
            return collect();
        }

        return $this->allActiveItems()
            ->filter(static fn (array $item): bool => in_array($cleanPage, $item['pages'], true))
            ->values();
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function allActiveItems(): Collection
    {
        try {
            if (! Schema::hasTable('our_story_testimonials')) {
                return collect();
            }

            /** @var array<int, array<string, mixed>> $rows */
            $rows = PublicContentCache::remember(PublicContentCache::VOICES_TESTIMONIALS, static function (): array {
                return OurStoryTestimonial::query()
                    ->active()
                    ->with('mediaAsset:id,url,alt')
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get()
                    ->map(static fn (OurStoryTestimonial $item): array => self::normalizeModel($item))
                    ->filter(static fn (array $item): bool => $item['quote'] !== '')
                    ->values()
                    ->all();
            });

            return collect($rows)->values();
        } catch (Throwable) {
            return collect();
        }
    }

    /**
     * Normalize an `OurStoryTestimonial` model into a pure scalar array safe for caching.
     *
     * @return array<string, mixed>
     */
    public static function normalizeModel(OurStoryTestimonial $item): array
    {
        $name = trim((string) $item->name);
        $initial = $name !== '' ? mb_strtoupper(mb_substr($name, 0, 1)) : '';

        return [
            'id' => (int) $item->id,
            'name' => $name,
            'role' => $item->display_role,
            'quote' => $item->plain_quote,
            'photo' => $item->resolved_photo_url,
            'initial' => $initial,
            'pages' => TestimonialPages::normalize($item->pages),
        ];
    }
}
