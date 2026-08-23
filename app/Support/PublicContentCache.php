<?php

namespace App\Support;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PublicContentCache
{
    public const TTL = 86400;

    public const HOMEPAGE = 'homepage';

    public const OUR_STORY = 'our-story';

    public const PROGRAMS_LISTING = 'programs.listing';

    public const ACCREDITATIONS = 'accreditations';

    public const MEDIA_GALLERY = 'media-gallery';

    public const GLOBAL_PARTNERS = 'global-partners';

    public const EVENTS = 'events';

    public const NAVMENU_PROGRAMS = 'navmenu.programs';

    public const BLOGS_TOP_TAGS = 'blogs.top_tags';

    public const NEWS_TOP_TAGS = 'news.top_tags';

    public const FEATURED_PROGRAMS = 'featured-programs';

    public const FACULTY_INSIGHTS_PREVIEW = 'faculty-insights.preview';

    public const UNIVERSITY_PARTNERS = 'university-partners';

    public const ALUMNI_LOGOS = 'alumni-logos';

    public const MLP_UNIVERSITY_LOGOS = 'mlp-university-logos';

    public const MLP_STORY_TESTIMONIALS = 'mlp-story-testimonials';

    public const ADMIN_OVERVIEW = 'admin.site_overview_counts';

    /**
     * @return list<string>
     */
    public static function keys(): array
    {
        return [
            self::HOMEPAGE,
            self::OUR_STORY,
            self::PROGRAMS_LISTING,
            self::ACCREDITATIONS,
            self::MEDIA_GALLERY,
            self::GLOBAL_PARTNERS,
            self::EVENTS,
            self::NAVMENU_PROGRAMS,
            self::BLOGS_TOP_TAGS,
            self::NEWS_TOP_TAGS,
            self::FEATURED_PROGRAMS,
            self::FACULTY_INSIGHTS_PREVIEW,
            self::UNIVERSITY_PARTNERS,
            self::ALUMNI_LOGOS,
            self::MLP_UNIVERSITY_LOGOS,
            self::MLP_STORY_TESTIMONIALS,
            self::ADMIN_OVERVIEW,
            self::HOMEPAGE.'-accreditation-logos',
            self::HOMEPAGE.'-faqs',
            'homepage_data_v1',
        ];
    }

    public static function remember(string $key, callable $callback, ?int $ttl = null): mixed
    {
        if (Cache::has($key)) {
            $cached = Cache::get($key);

            if (self::containsIncomplete($cached)) {
                Cache::forget($key);
            } else {
                return $cached;
            }
        }

        $value = $callback();
        Cache::put($key, $value, $ttl ?? self::TTL);

        return $value;
    }

    public static function forget(string ...$keys): void
    {
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    public static function flush(): void
    {
        self::forget(...self::keys());
    }

    /**
     * Store plain arrays (never Eloquent models) so database/file cache
     * cannot revive __PHP_Incomplete_Class collections.
     *
     * @param  iterable<mixed>  $items
     * @return list<array<string, mixed>>
     */
    public static function serializeRows(iterable $items, ?callable $map = null): array
    {
        return collect($items)
            ->map(function ($item) use ($map) {
                if ($map) {
                    return $map($item);
                }

                if (is_object($item) && method_exists($item, 'toArray')) {
                    return $item->toArray();
                }

                return (array) $item;
            })
            ->values()
            ->all();
    }

    /**
     * @param  mixed  $rows
     */
    public static function hydrateRows(mixed $rows, ?callable $map = null): Collection
    {
        if (self::containsIncomplete($rows) || is_string($rows) || ! is_iterable($rows)) {
            return collect();
        }

        return collect($rows)
            ->map(function ($row) use ($map) {
                if ($map) {
                    return $map($row);
                }

                if ($row instanceof \__PHP_Incomplete_Class) {
                    return null;
                }

                if (is_object($row)) {
                    return $row;
                }

                if (is_array($row)) {
                    return (object) $row;
                }

                return null;
            })
            ->filter()
            ->values();
    }

    public static function hydrateDate(mixed $value): ?Carbon
    {
        if ($value instanceof Carbon) {
            return $value;
        }

        if (blank($value)) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }

    private static function containsIncomplete(mixed $value): bool
    {
        if ($value instanceof \__PHP_Incomplete_Class) {
            return true;
        }

        if (is_array($value) || $value instanceof \Traversable) {
            foreach ($value as $item) {
                if (self::containsIncomplete($item)) {
                    return true;
                }
            }

            return false;
        }

        if (is_object($value)) {
            foreach (get_object_vars($value) as $item) {
                if (self::containsIncomplete($item)) {
                    return true;
                }
            }
        }

        return false;
    }
}
