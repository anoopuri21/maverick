<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\FacultyInsight;
use App\Models\Faq;
use App\Models\GupPartnerUniversity;
use App\Models\Insight;
use App\Models\MediaGalleryPhoto;
use App\Models\MediaGalleryVideo;
use App\Models\OurStoryAward;
use App\Models\OurStoryGalleryImage;
use App\Models\OurStoryTestimonial;
use App\Models\OurStoryTimeline;
use App\Models\PartnerLogo;
use App\Models\PartnershipGalleryItem;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\Testimonial;
use App\Models\UniversityPartner;
use App\Settings\FinalCtaSettings;
use App\Settings\WhatWeDoSettings;
use App\Support\PublicContentCache;
use Illuminate\Support\Facades\Event as EventFacade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\LaravelSettings\Events\SettingsSaved;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Load global helper functions. This guarantees media_url() etc. are
        // available even when composer autoload has not been regenerated
        // (e.g. right after a git pull on a server).
        $helpers = app_path('helpers.php');
        if (is_file($helpers)) {
            require_once $helpers;
        }

        // Make settings migrations idempotent: add() skips existing settings
        // instead of throwing SettingAlreadyExists, so `php artisan migrate`
        // is safe to run repeatedly. Applies to all settings migrations.
        $this->app->bind(
            \Spatie\LaravelSettings\Migrations\SettingsMigrator::class,
            \App\Settings\SafeSettingsMigrator::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Preview / reverse-proxy: generate asset URLs from the incoming host
        // so CSS/JS load on the sandbox preview domain (local only).
        if ($this->app->environment('local') && ! $this->app->runningInConsole()) {
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? null;
            $proto = $_SERVER['HTTP_X_FORWARDED_PROTO']
                ?? ((! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http');
            if ($host) {
                URL::forceRootUrl($proto.'://'.$host);
                if ($proto === 'https') {
                    URL::forceScheme('https');
                }
            }
        }

        // Share once per request — avoids re-binding on every nested @include.
        View::composer('*', function ($view) {
            if ($view->offsetExists('site')) {
                return;
            }

            $view->with('site', safe_settings(\App\Settings\SiteSettings::class));
        });

        EventFacade::listen(SettingsSaved::class, fn () => PublicContentCache::flush());

        View::composer('sections.featured-programs', function ($view) {
            if ($view->offsetExists('featuredPrograms')) {
                return;
            }

            try {
                $featuredPrograms = PublicContentCache::rememberHydrated(
                    PublicContentCache::FEATURED_PROGRAMS,
                    Program::class,
                    function () {
                        return Program::select('id', 'title', 'slug', 'university_partner_id', 'short_description', 'image_url', 'sort_order')
                            ->with('universityPartner:id,name')
                            ->where('is_featured', true)
                            ->where('is_active', true)
                            ->hasPublicSlug()
                            ->orderBy('sort_order')
                            ->limit(10)
                            ->get();
                    },
                    ['university_partner' => UniversityPartner::class]
                );
            } catch (\Throwable $e) {
                report($e);
                $featuredPrograms = collect();
            }

            $view->with('featuredPrograms', $featuredPrograms);
        });

        View::composer('sections.faculty-insights', function ($view) {
            if ($view->offsetExists('facultyInsights')) {
                return;
            }

            try {
                $facultyInsights = PublicContentCache::rememberHydrated(
                    PublicContentCache::FACULTY_INSIGHTS_PREVIEW,
                    FacultyInsight::class,
                    function () {
                        return FacultyInsight::select('id', 'title', 'slug', 'badge', 'image_url', 'link_url', 'excerpt', 'faculty_name', 'faculty_role', 'sort_order')
                            ->where('is_active', true)
                            ->hasPublicSlug()
                            ->orderBy('sort_order')
                            ->limit(6)
                            ->get();
                    }
                );
            } catch (\Throwable $e) {
                report($e);
                $facultyInsights = collect();
            }

            $view->with('facultyInsights', $facultyInsights);
        });

        View::composer('sections.university-partners', function ($view) {
            if ($view->offsetExists('universityPartners') && $view->offsetExists('universityPartnersJson')) {
                return;
            }

            try {
                $cached = PublicContentCache::remember(PublicContentCache::UNIVERSITY_PARTNERS, function () {
                    $universityPartners = UniversityPartner::select('id', 'name', 'country', 'city', 'latitude', 'longitude', 'is_hub', 'recognition', 'logo_url')
                        ->with(['programs' => fn ($q) => $q->select('id', 'university_partner_id', 'title', 'slug', 'sort_order')
                            ->where('is_active', true)
                            ->hasPublicSlug()
                            ->orderBy('sort_order')])
                        ->where('is_active', true)
                        ->orderBy('country')
                        ->get();

                    $universityPartnersJson = $universityPartners
                        ->groupBy('country')
                        ->map(function ($partners, $country) {
                            $first = $partners->first();

                            return [
                                'id' => strtolower(str_replace(' ', '-', (string) $country)),
                                'name' => $country,
                                'city' => $first->city ?? '',
                                'lat' => (float) ($first->latitude ?? 0),
                                'lng' => (float) ($first->longitude ?? 0),
                                'isHub' => (bool) ($first->is_hub ?? false),
                                'universities' => $partners->map(function ($p) {
                                    return [
                                        'name' => $p->name,
                                        'country' => $p->country,
                                        'recognition' => $p->recognition ?? '',
                                        'programs' => $p->programs
                                            ->filter(fn ($pr) => filled($pr->slug))
                                            ->map(fn ($pr) => [
                                                'name' => $pr->title,
                                                'url' => route('programs.show', $pr->slug),
                                            ])->values()->all(),
                                    ];
                                })->values()->all(),
                            ];
                        })
                        ->values()
                        ->all();

                    return [
                        'universityPartners' => $universityPartners->toArray(),
                        'universityPartnersJson' => $universityPartnersJson,
                    ];
                });

                $universityPartners = PublicContentCache::hydrateRows(
                    UniversityPartner::class,
                    $cached['universityPartners'] ?? [],
                    ['programs' => Program::class]
                );
                $universityPartnersJson = collect($cached['universityPartnersJson'] ?? []);
            } catch (\Throwable $e) {
                report($e);
                $universityPartners = collect();
                $universityPartnersJson = collect();
            }

            $view->with([
                'universityPartners' => $universityPartners,
                'universityPartnersJson' => $universityPartnersJson,
            ]);
        });

        View::composer('sections.final-cta', function ($view) {
            if ($view->offsetExists('finalCta')) {
                return;
            }

            $view->with('finalCta', safe_settings(FinalCtaSettings::class));
        });

        View::composer('sections.alumni-network', function ($view) {
            // #region agent log
            $existing = $view->offsetExists('alumniLogos') ? $view->offsetGet('alumniLogos') : null;
            $existingFirst = is_iterable($existing) ? collect($existing)->first() : null;
            file_put_contents(base_path('debug-8d936b.log'), json_encode(['sessionId' => '8d936b', 'runId' => 'home-request', 'hypothesisId' => 'B', 'location' => 'AppServiceProvider.php:alumni-network-composer', 'message' => 'alumni-network composer entry', 'data' => ['offsetExists' => $view->offsetExists('alumniLogos'), 'existingType' => get_debug_type($existing), 'existingFirstType' => get_debug_type($existingFirst), 'existingFirstIsArray' => is_array($existingFirst), 'willEarlyReturn' => $view->offsetExists('alumniLogos')], 'timestamp' => (int) (microtime(true) * 1000)]).PHP_EOL, FILE_APPEND);
            // #endregion

            if ($view->offsetExists('alumniLogos')) {
                return;
            }

            try {
                $alumniLogos = PublicContentCache::rememberHydrated(
                    PublicContentCache::ALUMNI_LOGOS,
                    PartnerLogo::class,
                    function () {
                        return PartnerLogo::select('id', 'name', 'logo_url', 'sort_order')
                            ->where('type', 'alumni')
                            ->where('is_active', true)
                            ->orderBy('sort_order')
                            ->get();
                    }
                );
            } catch (\Throwable $e) {
                report($e);
                $alumniLogos = collect();
            }

            $view->with('alumniLogos', $alumniLogos);
        });

        View::composer('partials.footer', function ($view) {
            if ($view->offsetExists('footerProgramCategories')) {
                return;
            }

            try {
                $footerProgramCategories = PublicContentCache::rememberHydrated(
                    PublicContentCache::FOOTER_PROGRAM_CATEGORIES,
                    ProgramCategory::class,
                    function () {
                        return ProgramCategory::select('id', 'name', 'slug', 'sort_order')
                            ->where('is_active', true)
                            ->orderBy('sort_order')
                            ->orderBy('name')
                            ->get();
                    }
                );
            } catch (\Throwable $e) {
                report($e);
                $footerProgramCategories = collect();
            }

            $view->with('footerProgramCategories', $footerProgramCategories);
        });

        View::composer([
            'sections.what-we-do',
            'pages.csr-community-impact',
        ], function ($view) {
            if ($view->offsetExists('whatWeDo')) {
                return;
            }

            $view->with('whatWeDo', safe_settings(WhatWeDoSettings::class));
        });

        $collectionModels = [
            Program::class,
            ProgramCategory::class,
            PartnerLogo::class,
            UniversityPartner::class,
            FacultyInsight::class,
            Event::class,
            Testimonial::class,
            Faq::class,
            Insight::class,
            MediaGalleryPhoto::class,
            MediaGalleryVideo::class,
            OurStoryTimeline::class,
            OurStoryAward::class,
            OurStoryGalleryImage::class,
            OurStoryTestimonial::class,
            GupPartnerUniversity::class,
            PartnershipGalleryItem::class,
        ];

        foreach ($collectionModels as $model) {
            $model::saved(fn () => PublicContentCache::flush());
            $model::deleted(fn () => PublicContentCache::flush());
        }
    }
}
