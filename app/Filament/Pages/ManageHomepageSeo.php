<?php

namespace App\Filament\Pages;

use App\Filament\Support\ManagesPageSeo;
use App\Settings\HomepageSeoSettings;

class ManageHomepageSeo extends ManagesPageSeo
{
    protected static ?string $navigationGroup = 'Homepage';
    protected static ?string $navigationLabel = 'Homepage SEO';
    protected static string $settings = HomepageSeoSettings::class;
    protected static string $mediaFolder = 'homepage-seo';
    protected static string $pageLabel = 'the homepage';
}
