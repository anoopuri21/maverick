<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Models\Program;
use App\Settings\FinalCtaSettings;
use App\Settings\WhatWeDoSettings;
use App\Models\UniversityPartner;

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

        // Share site settings globally
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $view->with('site', app(\App\Settings\SiteSettings::class));
        });

        // Auto-invalidate homepage cache when ANY setting is saved
        \Illuminate\Support\Facades\Event::listen(
            \Spatie\LaravelSettings\Events\SettingsSaved::class,
            function () {
                \Illuminate\Support\Facades\Cache::forget('homepage_data_v1');
            }
        );

        View::composer('sections.featured-programs', function ($view) {
            $view->with('featuredPrograms', 
                Program::select('id', 'title', 'slug', 'university_partner_id', 'short_description', 'image_url', 'sort_order')
                    ->with('universityPartner')
                    ->where('is_featured', true)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->limit(10)
                    ->get()
            );
        });

        View::composer('sections.university-partners', function ($view) {
            $universityPartners = UniversityPartner::select('id', 'name', 'country', 'city', 'latitude', 'longitude', 'is_hub', 'recognition', 'logo_url')
                ->with(['programs' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])
                ->where('is_active', true)
                ->orderBy('country')
                ->get();

            $universityPartnersJson = $universityPartners
                ->groupBy('country')
                ->map(function ($partners, $country) {
                    $first = $partners->first();
                    return [
                        'id' => strtolower(str_replace(' ', '-', $country)),
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
                                // Each linked program becomes {name, url} for the map's Program Offered cards.
                                'programs' => $p->programs->map(fn ($pr) => [
                                    'name' => $pr->title,
                                    'url' => route('programs.show', $pr->slug),
                                ])->values()->all(),
                            ];
                        })->values(),
                    ];
                })
                ->values();

            $view->with([
                'universityPartners' => $universityPartners,
                'universityPartnersJson' => $universityPartnersJson,
            ]);
        });


        
        View::composer('sections.final-cta', function ($view) {
            $view->with('finalCta', app(FinalCtaSettings::class));
        });

        View::composer([
            'sections.what-we-do',
            'pages.csr-community-impact',
        ], function ($view) {
            $view->with('whatWeDo', app(WhatWeDoSettings::class));
        });

        // Auto-invalidate when any homepage collection changes
        $collectionModels = [
            \App\Models\Program::class,
            \App\Models\PartnerLogo::class,
            \App\Models\UniversityPartner::class,
            \App\Models\FacultyInsight::class,
            \App\Models\Event::class,
            \App\Models\Testimonial::class,
            \App\Models\Faq::class,
        ];

        foreach ($collectionModels as $model) {
            $model::saved(fn() => \Illuminate\Support\Facades\Cache::forget('homepage_data_v1'));
            $model::deleted(fn() => \Illuminate\Support\Facades\Cache::forget('homepage_data_v1'));
        }
    }
}
