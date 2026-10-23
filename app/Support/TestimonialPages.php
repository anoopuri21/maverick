<?php

namespace App\Support;

use App\Settings\VoicesTestimonialsSettings;
use Illuminate\Support\Str;
use Throwable;

class TestimonialPages
{
    public const MBA_MASTERS_LANDING = 'mba-masters-landing';

    public const OUR_STORY = 'our-story';

    /**
     * All registered page options (`page-key => Human Label`) resolved from
     * Admin DB settings (`VoicesTestimonialsSettings::$pages`) merged with any
     * code-registered pages in `config/testimonials.php`.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];

        // 1. Admin-managed pages stored in DB (`voices_testimonials.pages`)
        try {
            $settings = safe_settings(VoicesTestimonialsSettings::class);
            $dbPages = settings_array($settings->pages ?? []);
            foreach ($dbPages as $row) {
                if (! is_array($row)) {
                    continue;
                }
                $key = Str::slug(trim((string) ($row['key'] ?? '')));
                if ($key === '') {
                    continue;
                }
                $label = trim((string) ($row['label'] ?? ''));
                $options[$key] = $label !== '' ? $label : Str::headline($key);
            }
        } catch (Throwable) {
            // Ignore before migrations run
        }

        // 2. Merge baseline / developer-configured keys from config/testimonials.php
        $configured = config('testimonials.pages', []);
        if (is_array($configured)) {
            foreach ($configured as $key => $label) {
                $cleanKey = trim((string) $key);
                if ($cleanKey === '' || isset($options[$cleanKey])) {
                    continue;
                }
                $cleanLabel = trim((string) $label);
                $options[$cleanKey] = $cleanLabel !== '' ? $cleanLabel : Str::headline($cleanKey);
            }
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
