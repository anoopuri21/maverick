<?php

declare(strict_types=1);

namespace App\Support;

use App\Settings\VoicesTestimonialsSettings;
use Illuminate\Support\Str;
use Throwable;

final class TestimonialPages
{
    public const MBA_MASTERS_LANDING = 'mba-masters-landing';

    public const OUR_STORY = 'our-story';

    public const PROGRAM_DETAIL = 'program-detail';

    /**
     * Resolve all available display page options (`page-key => Human Label`)
     * from Admin DB settings (`VoicesTestimonialsSettings::$pages`) merged with
     * baseline pages registered in `config/testimonials.php`.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];

        try {
            $settings = safe_settings(VoicesTestimonialsSettings::class);
            $dbPages = settings_array($settings->pages ?? []);

            foreach ($dbPages as $row) {
                if (! is_array($row)) {
                    continue;
                }

                $key = self::sanitizeKey($row['key'] ?? null);
                if ($key === '') {
                    continue;
                }

                $label = trim((string) ($row['label'] ?? ''));
                $options[$key] = $label !== '' ? $label : Str::headline($key);
            }
        } catch (Throwable) {
            // Safe fallback before settings table migrations have executed.
        }

        $configured = config('testimonials.pages', []);
        if (is_array($configured)) {
            foreach ($configured as $key => $label) {
                $cleanKey = self::sanitizeKey($key);
                $cleanLabel = trim((string) $label);

                if ($cleanKey === '' || isset($options[$cleanKey])) {
                    continue;
                }

                $options[$cleanKey] = $cleanLabel !== '' ? $cleanLabel : Str::headline($cleanKey);
            }
        }

        return $options;
    }

    /**
     * Registered page keys in configured display order.
     *
     * @return array<int, string>
     */
    public static function keys(): array
    {
        return array_keys(self::options());
    }

    /**
     * Human-readable label for a given page key.
     */
    public static function label(string $pageKey): string
    {
        $cleanKey = self::sanitizeKey($pageKey);

        return self::options()[$cleanKey] ?? Str::headline($cleanKey !== '' ? $cleanKey : $pageKey);
    }

    /**
     * Normalize single/multi-select input (JSON string, iterable, or scalar)
     * into a deduplicated, 0-indexed list of clean non-empty page keys.
     *
     * @return array<int, string>
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

            $clean = self::sanitizeKey($page);
            if ($clean !== '' && ! in_array($clean, $normalized, true)) {
                $normalized[] = $clean;
            }
        }

        return array_values($normalized);
    }

    /**
     * Sanitize a page key into a safe URL-style slug (`a-z0-9-`).
     */
    public static function sanitizeKey(mixed $raw): string
    {
        if (! is_scalar($raw)) {
            return '';
        }

        return Str::slug(trim((string) $raw));
    }
}
