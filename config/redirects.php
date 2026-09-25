<?php

/*
|--------------------------------------------------------------------------
| Legacy (WordPress) URL redirects
|--------------------------------------------------------------------------
|
| The old mbalondon.org.uk site was WordPress + WooCommerce + MasterStudy LMS.
| Those URLs do not exist in this Laravel app, so they would 404 and lose the
| SEO value they have built up. This file maps them, via 301 redirects, onto
| the closest equivalent page of the new site.
|
|   exact    : path (no leading slash) => destination. Checked first.
|   smart    : "{prefix}/{slug}" URLs. Looks the slug up in the database and
|              redirects to the real new page; otherwise falls back to the
|              section landing page. (Never guesses -> no accidental 404s.)
|   patterns : regex (matched against the path, no leading slash) => destination.
|              Captures ($1, $2...) can be used in the destination.
|
| Destinations may be a path ("programs", "/programs") or a full URL.
| Query strings (?utm_source=...) are preserved automatically.
|
| IMPORTANT
|   * Never add a path that is a REAL route of the new site (e.g. "about-us",
|     "programs", "contact") - this middleware runs before routing and would
|     hijack it.
|   * Run `php artisan config:cache` (or scripts/shared-hosting-optimize.sh)
|     after editing this file.
|
*/

return [

    'enabled' => env('LEGACY_REDIRECTS_ENABLED', true),

    /*
    |----------------------------------------------------------------------
    | 1. Hand-mapped pages (old path => new path)
    |----------------------------------------------------------------------
    | Add one line per old page as you discover them (Search Console ->
    | "Not found (404)" report is the best source).
    */
    'exact' => [
        // Old WP "Read More" about page
        'university-overview' => '/about-us',

        // WooCommerce shop / account screens -> programmes
        'shop' => '/programs',
        'cart' => '/programs',
        'checkout' => '/contact',
        'my-account' => '/contact',

        // WordPress infrastructure - never a real page
        'wp-login.php' => '/',
        'xmlrpc.php' => '/',
        'wp-sitemap.xml' => '/sitemap.xml',
        'sitemap_index.xml' => '/sitemap.xml',
        'sitemap.xml' => '/sitemap.xml',
        'feed' => '/blogs',
        'feed/rss' => '/blogs',
    ],

    /*
    |----------------------------------------------------------------------
    | 2. Slug lookups (old section => new model)
    |----------------------------------------------------------------------
    | e.g. /business-courses/mba-anglia-ruskin-university
    |      -> if a Programme with that slug exists: /programs/<slug>
    |      -> otherwise:                            /programs
    */
    'smart' => [
        'programs' => [
            'prefixes' => ['business-courses', 'product', 'stm-courses', 'course', 'courses'],
            'fallback' => '/programs',
        ],
        'blogs' => [
            'prefixes' => ['blog', 'post', 'posts', 'article'],
            'fallback' => '/blogs',
        ],
        'news' => [
            'prefixes' => ['news', 'press'],
            'fallback' => '/news',
        ],
    ],

    /*
    |----------------------------------------------------------------------
    | 3. Catch-all patterns (checked last, top to bottom)
    |----------------------------------------------------------------------
    */
    'patterns' => [
        // University directory (old "university" CPT) -> partners page
        '^university(/|$)' => '/global-university-partners',

        // WordPress internals / assets / JSON API
        '^wp-(admin|content|includes|json|cron\.php|signup\.php|trackback)(/|$)' => '/',
        '^wp-.*\.php$' => '/',
        '^xmlrpc\.php$' => '/',

        // Feeds and archive pages
        '^feed(/|$)' => '/blogs',
        '^(author|category|tag)/' => '/blogs',
        '^\d{4}/\d{2}(/|$)' => '/blogs',          // date archives /2024/06/
        '^page/\d+/?$' => '/',                    // paginated front page

        // WooCommerce leftovers
        '^(product-category|product-tag)/' => '/programs',
        '^product/[^/]+/?$' => '/programs',
        '^shop(/|$)' => '/programs',

        // MasterStudy LMS course screens (/courses, /course-category, ...)
        '^(courses|course-category|stm-courses)(/|$)' => '/programs',

        // Old static file paths
        '^(cgi-bin|wp-config)(/|$)' => '/',
    ],
];
