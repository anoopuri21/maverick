<?php

return [

    /*
     * Each settings class used in your application must be registered here.
     */
    'settings' => [
        \App\Settings\HeroSettings::class,
        \App\Settings\NumbersSettings::class,
        \App\Settings\WhoWeAreSettings::class,
        \App\Settings\CeoSettings::class,
        \App\Settings\WhatIsMaverickSettings::class,
        \App\Settings\FinalCtaSettings::class,
        \App\Settings\SiteSettings::class,
        \App\Settings\WhatWeDoSettings::class,
        \App\Settings\HowWeDoItSettings::class,
        \App\Settings\WhyMaverickSettings::class,
        \App\Settings\GlobalOpportunitiesSettings::class,
    ],

    /*
     * The path where the settings migration files will be stored.
     */
    'migrations_path' => database_path('settings'),

    /*
     * Caching settings
     * Set enabled to false during development for instant updates
     */
    'cache' => [
        'enabled' => env('SETTINGS_CACHE_ENABLED', false),
        'store' => null,
        'prefix' => 'settings.',
        'ttl' => null,
    ],

    /*
     * The default repository used to store settings.
     */
    'default_repository' => 'database',

    'repositories' => [
        'database' => [
            'type' => \Spatie\LaravelSettings\SettingsRepositories\DatabaseSettingsRepository::class,
            'model' => null,
            'table' => null,
            'connection' => null,
        ],
    ],

    /*
     * Automatic discovery of setting classes
     */
    'auto_discover_settings' => [
        app()->path(),
    ],

    'discoverer' => \Spatie\LaravelSettings\Support\Composer::class,

];