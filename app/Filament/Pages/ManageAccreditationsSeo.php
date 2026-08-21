<?php

namespace App\Filament\Pages;

use App\Filament\Support\ManagesPageSeo;
use App\Settings\AccreditationsSeoSettings;

class ManageAccreditationsSeo extends ManagesPageSeo
{
    protected static ?string $navigationGroup = 'About Section';
    protected static ?string $navigationLabel = 'Accreditations SEO';
    protected static string $settings = AccreditationsSeoSettings::class;
    protected static string $mediaFolder = 'accreditations-seo';
    protected static string $pageLabel = 'the Accreditations page';
}
