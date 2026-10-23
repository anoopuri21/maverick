<?php

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
     * Guaranteed behavior:
     * - Only active, non-deleted testimonials whose `pages` array contains `$pageKey`
     *   are returned.
     * - Unselected pages NEVER receive the testimonial.
     * - Results are cached in a single array-safe cache entry across all pages
     *   and automatically invalidated when any testimonial is created/updated/deleted.
     *
     * @return Collection<int, array{
     *     id: int,
     *     name: string,
     *     role: string,
     *     quote: string,
     *     photo: ?string,
     *     initial: string,
     *     pages: list<string>
     * }>
     */
    public function itemsForPage(string $pageKey): Collection
    {
        $cleanPage = trim($pageKey);
        if ($cleanPage === '') {
            return collect();
        }

        return $this->allActiveItems()
            ->filter(fn (array $item): bool => in_array($cleanPage, $item['pages'], true))
            ->values();
    }

    /**
     * @return Collection<int, array{
     *     id: int,
     *     name: string,
     *     role: string,
     *     quote: string,
     *     photo: ?string,
     *     initial: string,
     *     pages: list<string>
     * }>
     */
    public function allActiveItems(): Collection
    {
        try {
            if (! Schema::hasTable('our_story_testimonials')) {
                return collect();
            }

            /** @var array<int, array<string, mixed>> $rows */
            $rows = PublicContentCache::remember(PublicContentCache::VOICES_TESTIMONIALS, function (): array {
                return OurStoryTestimonial::query()
                    ->active()
                    ->with('mediaAsset')
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get()
                    ->map(fn (OurStoryTestimonial $item): array => self::normalizeModel($item))
                    ->filter(fn (array $item): bool => $item['quote'] !== '')
                    ->values()
                    ->all();
            });

            return collect($rows)->values();
        } catch (Throwable) {
            return collect();
        }
    }

    /**
     * Normalize an Eloquent model or raw array item for rendering in
     * `<x-voices-testimonials />`.
     *
     * @return array{
     *     id: int,
     *     name: string,
     *     role: string,
     *     quote: string,
     *     photo: ?string,
     *     initial: string,
     *     pages: list<string>
     * }
     */
    public static function normalizeModel(OurStoryTestimonial $item): array
    {
        $name = trim((string) $item->name);
        $initial = mb_strtoupper(mb_substr($name !== '' ? $name : 'S', 0, 1));

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
