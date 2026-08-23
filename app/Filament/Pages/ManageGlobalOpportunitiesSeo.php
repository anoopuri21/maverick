<?php

namespace App\Filament\Pages;

use App\Filament\Support\ManagesPageSeo;
use App\Settings\GlobalOpportunitiesSeoSettings;

class ManageGlobalOpportunitiesSeo extends ManagesPageSeo
{
    protected static ?string $navigationGroup = 'Global Pathways';
    protected static ?string $navigationLabel = 'Global Opportunities SEO';
    protected static string $settings = GlobalOpportunitiesSeoSettings::class;
    protected static string $mediaFolder = 'global-opportunities-seo';
    protected static string $pageLabel = 'the Global Opportunities page';
}
