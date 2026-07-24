<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
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
                Program::select('id', 'title', 'slug', 'partner_university', 'short_description', 'image_url', 'sort_order')
                    ->where('is_featured', true)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->limit(10)
                    ->get()
            );
        });

        View::composer('sections.university-partners', function ($view) {
            $universityPartners = UniversityPartner::select('id', 'name', 'country', 'city', 'latitude', 'longitude', 'is_hub', 'recognition', 'programs', 'logo_url')
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
                        'universities' => $partners->map(fn($p) => [
                            'name' => $p->name,
                            'country' => $p->country,
                            'recognition' => $p->recognition ?? '',
                            'programs' => $p->programs ?? [],
                        ])->values(),
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
