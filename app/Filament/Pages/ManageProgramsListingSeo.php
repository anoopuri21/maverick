<?php

namespace App\Filament\Pages;

use App\Filament\Support\ManagesPageSeo;
use App\Settings\ProgramsListingSeoSettings;

class ManageProgramsListingSeo extends ManagesPageSeo
{
    protected static ?string $navigationGroup = 'Programs';
    protected static ?string $navigationLabel = 'Programmes Listing SEO';
    protected static string $settings = ProgramsListingSeoSettings::class;
    protected static string $mediaFolder = 'programs-listing-seo';
    protected static string $pageLabel = 'the Programmes listing page';
}
