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
        \App\Settings\PathwayProgramsSettings::class,
        \App\Settings\GlobalOpportunitiesPageSettings::class,
        \App\Settings\HomepageSeoSettings::class,
        \App\Settings\OurStoryHeroSettings::class,
        \App\Settings\OurStoryBeginningSettings::class,
        \App\Settings\OurStoryTodaySettings::class,
        \App\Settings\OurStoryImpactSettings::class,
        \App\Settings\OurStoryCeoQuoteSettings::class,
        \App\Settings\OurStoryVisionSettings::class,
        \App\Settings\OurStorySeoSettings::class,
        \App\Settings\AccreditationCinematicSettings::class,
        \App\Settings\GlobalPartnersHeroSettings::class,
        \App\Settings\GlobalPartnersOverviewSettings::class,
        \App\Settings\GlobalPartnersCardsSettings::class,
        \App\Settings\GlobalPartnersWhySettings::class,
        \App\Settings\GlobalPartnersBenefitsSettings::class,
        \App\Settings\GlobalPartnersJourneySettings::class,
        \App\Settings\GlobalPartnersSeoSettings::class,
        \App\Settings\CsrHeroSettings::class,
        \App\Settings\CsrCommitmentSettings::class,
        \App\Settings\CsrFocusSettings::class,
        \App\Settings\CsrGallerySettings::class,
        \App\Settings\CsrImpactSettings::class,
        \App\Settings\CsrScholarshipSettings::class,
        \App\Settings\CsrSeoSettings::class,
        \App\Settings\EdutainmentHeroSettings::class,
        \App\Settings\EdutainmentIntroSettings::class,
        \App\Settings\EdutainmentWhatIsSettings::class,
        \App\Settings\EdutainmentLearningBeyondSettings::class,
        \App\Settings\EdutainmentWhoForSettings::class,
        \App\Settings\EdutainmentProgrammesSettings::class,
        \App\Settings\EdutainmentThemesSettings::class,
        \App\Settings\EdutainmentExperiencesSettings::class,
        \App\Settings\EdutainmentWhyChooseSettings::class,
        \App\Settings\EdutainmentPackagesSettings::class,
        \App\Settings\EdutainmentInstitutionsSettings::class,
        \App\Settings\EdutainmentFaqSettings::class,
        \App\Settings\EdutainmentFinalCtaSettings::class,
        \App\Settings\EdutainmentSeoSettings::class,
        \App\Settings\DualMbaHeroSettings::class,
        \App\Settings\DualMbaOverviewSettings::class,
        \App\Settings\DualMbaTwiceSettings::class,
        \App\Settings\DualMbaWhySettings::class,
        \App\Settings\DualMbaSpecsSettings::class,
        \App\Settings\DualMbaEmployersSettings::class,
        \App\Settings\DualMbaTestimonialsSettings::class,
        \App\Settings\DualMbaProcessSettings::class,
        \App\Settings\DualMbaFaqSettings::class,
        \App\Settings\DualMbaFinalCtaSettings::class,
        \App\Settings\DualMbaSeoSettings::class,
        \App\Settings\GbpHeroSettings::class,
        \App\Settings\GbpSnapshotSettings::class,
        \App\Settings\GbpIntroSettings::class,
        \App\Settings\GbpOverviewSettings::class,
        \App\Settings\GbpWhySettings::class,
        \App\Settings\GbpExploreSettings::class,
        \App\Settings\GbpDestinationsSettings::class,
        \App\Settings\GbpCostSettings::class,
        \App\Settings\GbpComparisonSettings::class,
        \App\Settings\GbpAreasSettings::class,
        \App\Settings\GbpPartnersSettings::class,
        \App\Settings\GbpAdmissionSettings::class,
        \App\Settings\GbpDocumentsSettings::class,
        \App\Settings\GbpFinalCtaSettings::class,
        \App\Settings\GbpSeoSettings::class,
        \App\Settings\MpHeroSettings::class,
        \App\Settings\MpOverviewSettings::class,
        \App\Settings\MpHowSettings::class,
        \App\Settings\MpDestinationsSettings::class,
        \App\Settings\MpWhySettings::class,
        \App\Settings\MpAudienceSettings::class,
        \App\Settings\MpRequirementsSettings::class,
        \App\Settings\MpProcessSettings::class,
        \App\Settings\MpNoticeSettings::class,
        \App\Settings\MpFinalCtaSettings::class,
        \App\Settings\MpSeoSettings::class,
        \App\Settings\PathwayProgramsSeoSettings::class,
        \App\Settings\GlobalOpportunitiesSeoSettings::class,
        \App\Settings\AccreditationsSeoSettings::class,
        \App\Settings\MediaGallerySeoSettings::class,
        \App\Settings\ContactSeoSettings::class,
        \App\Settings\EventsSeoSettings::class,
        \App\Settings\StudentSuccessSeoSettings::class,
        \App\Settings\ProgramsListingSeoSettings::class,
        \App\Settings\FacultyVoiceSeoSettings::class,
        \App\Settings\BlogHeroSettings::class,
        \App\Settings\NewsHeroSettings::class,
        \App\Settings\LeadershipHeroSettings::class,
        \App\Settings\LeadershipLeadersSettings::class,
        \App\Settings\LeadershipSeoSettings::class,
        \App\Settings\EventsPageSettings::class,
        \App\Settings\StudentSuccessPageSettings::class,
        \App\Settings\ContactPageSettings::class,
        \App\Settings\ProgramsListingPageSettings::class,
        \App\Settings\FacultyVoicePageSettings::class,
        \App\Settings\AccreditationsPageSettings::class,
        \App\Settings\MediaGalleryPageSettings::class,
        \App\Settings\ProgramsDetailChromeSettings::class,
        \App\Settings\ZohoSettings::class,
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
        // Cross-request settings cache. Use file/redis — database cache
        // adds a query per settings group and doesn't reduce SQL.
        'enabled' => env('SETTINGS_CACHE_ENABLED', true),
        'store' => env('SETTINGS_CACHE_STORE', 'file'),
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