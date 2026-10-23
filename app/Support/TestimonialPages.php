<?php

namespace App\Support;

use Illuminate\Support\Str;

class TestimonialPages
{
    public const MBA_MASTERS_LANDING = 'mba-masters-landing';

    public const OUR_STORY = 'our-story';

    /**
     * All registered page options (`page-key => Human Label`) for Admin forms,
     * table filters, and badges.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        $configured = config('testimonials.pages', []);

        if (! is_array($configured)) {
            return [];
        }

        $options = [];
        foreach ($configured as $key => $label) {
            $cleanKey = trim((string) $key);
            if ($cleanKey === '') {
                continue;
            }

            $options[$cleanKey] = trim((string) $label) !== ''
                ? trim((string) $label)
                : Str::headline($cleanKey);
        }

        return $options;
    }

    /**
     * Registered page keys in display order.
     *
     * @return list<string>
     */
    public static function keys(): array
    {
        return array_keys(self::options());
    }

    /**
     * Resolve human-readable label for a page key.
     */
    public static function label(string $pageKey): string
    {
        return self::options()[$pageKey] ?? Str::headline($pageKey);
    }

    /**
     * Normalize single/multi-select input (JSON string, iterable, or scalar)
     * into a deduplicated, 0-indexed list of clean non-empty page keys.
     *
     * @return list<string>
     */
    public static function normalize(mixed $pages): array
    {
        if (is_string($pages)) {
            $decoded = json_decode($pages, true);
            $pages = is_array($decoded) ? $decoded : [$pages];
        }

        if (! is_iterable($pages)) {
            return [];
        }

        $normalized = [];
        foreach ($pages as $page) {
            if (! is_scalar($page)) {
                continue;
            }

            $clean = trim((string) $page);
            if ($clean !== '' && ! in_array($clean, $normalized, true)) {
                $normalized[] = $clean;
            }
        }

        return array_values($normalized);
    }
}
