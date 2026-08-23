<?php

namespace App\Filament\Pages;

use App\Filament\Support\ManagesPageSeo;
use App\Settings\EventsSeoSettings;

class ManageEventsSeo extends ManagesPageSeo
{
    protected static ?string $navigationGroup = 'Insights';
    protected static ?string $navigationLabel = 'Events Page SEO';
    protected static string $settings = EventsSeoSettings::class;
    protected static string $mediaFolder = 'events-seo';
    protected static string $pageLabel = 'the Events page';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
