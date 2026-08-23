<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PublicContentCache
{
    public const TTL = 86400;

    public const HOMEPAGE = 'homepage.v2';

    public const OUR_STORY = 'our-story.v2';

    public const PROGRAMS_LISTING = 'programs.listing.v2';

    public const ACCREDITATIONS = 'accreditations.v2';

    public const MEDIA_GALLERY = 'media-gallery.v2';

    public const GLOBAL_PARTNERS = 'global-partners.v2';

    public const EVENTS = 'events.v2';

    public const NAVMENU_PROGRAMS = 'navmenu.programs';

    public const BLOGS_TOP_TAGS = 'blogs.top_tags';

    public const NEWS_TOP_TAGS = 'news.top_tags';

    public const FEATURED_PROGRAMS = 'featured-programs.v2';

    public const FACULTY_INSIGHTS_PREVIEW = 'faculty-insights.preview.v2';

    public const UNIVERSITY_PARTNERS = 'university-partners.v2';

    public const ALUMNI_LOGOS = 'alumni-logos.v2';

    public const FOOTER_PROGRAM_CATEGORIES = 'footer.program_categories.v1';

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
            self::FOOTER_PROGRAM_CATEGORIES,
            self::ADMIN_OVERVIEW,
            // Legacy keys (pre array-safe cache)
            'homepage',
            'homepage_data_v1',
            'our-story',
            'programs.listing',
            'accreditations',
            'media-gallery',
            'global-partners',
            'events',
            'featured-programs',
            'faculty-insights.preview',
            'university-partners',
            'alumni-logos',
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

    /**
     * Cache Eloquent rows as plain arrays (DB cache corrupts serialized models), then hydrate.
     *
     * @param  class-string<Model>  $modelClass
     * @param  array<string, class-string<Model>>  $relations  snake_case array key => related model class
     * @return EloquentCollection<int, Model>
     */
    public static function rememberHydrated(string $key, string $modelClass, callable $callback, array $relations = [], ?int $ttl = null): EloquentCollection
    {
        $rows = self::remember($key, function () use ($callback) {
            $result = $callback();

            if ($result instanceof Collection) {
                return $result->toArray();
            }

            return is_array($result) ? $result : [];
        }, $ttl);

        return self::hydrateRows($modelClass, is_array($rows) ? $rows : [], $relations);
    }

    /**
     * @param  class-string<Model>  $modelClass
     * @param  array<int, array<string, mixed>>  $rows
     * @param  array<string, class-string<Model>>  $relations
     * @return EloquentCollection<int, Model>
     */
    public static function hydrateRows(string $modelClass, array $rows, array $relations = []): EloquentCollection
    {
        $items = [];

        foreach (array_values($rows) as $row) {
            if (! is_array($row)) {
                continue;
            }

            $loaded = [];

            foreach ($relations as $snakeKey => $relatedClass) {
                if (! array_key_exists($snakeKey, $row)) {
                    continue;
                }

                $related = $row[$snakeKey];
                unset($row[$snakeKey]);

                $relationName = Str::camel($snakeKey);

                if ($related === null) {
                    $loaded[$relationName] = null;
                } elseif (is_array($related) && array_is_list($related)) {
                    $loaded[$relationName] = self::hydrateRows($relatedClass, $related);
                } elseif (is_array($related)) {
                    $loaded[$relationName] = (new $relatedClass)->newFromBuilder($related);
                } else {
                    $loaded[$relationName] = null;
                }
            }

            /** @var Model $model */
            $model = (new $modelClass)->newFromBuilder($row);

            foreach ($loaded as $name => $value) {
                $model->setRelation($name, $value);
            }

            $items[] = $model;
        }

        return (new $modelClass)->newCollection($items);
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
