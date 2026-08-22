<?php

namespace App\Support;

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
            self::ADMIN_OVERVIEW,
            'homepage_data_v1',
        ];
    }

    public static function remember(string $key, callable $callback, ?int $ttl = null): mixed
    {
        return Cache::remember($key, $ttl ?? self::TTL, $callback);
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
}
