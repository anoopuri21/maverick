<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
