<?php

namespace App\Filament\Pages;

use App\Filament\Support\ManagesPageSeo;
use App\Settings\PathwayProgramsSeoSettings;

class ManagePathwayProgramsSeo extends ManagesPageSeo
{
    protected static ?string $navigationGroup = 'Global Pathways';
    protected static ?string $navigationLabel = 'Pathway Programs SEO';
    protected static string $settings = PathwayProgramsSeoSettings::class;
    protected static string $mediaFolder = 'pathway-programs-seo';
    protected static string $pageLabel = 'the Pathway Programs page';
}
