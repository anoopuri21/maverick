<?php

namespace App\Filament\Pages;

use App\Filament\Support\ManagesPageSeo;
use App\Settings\MediaGallerySeoSettings;

class ManageMediaGallerySeo extends ManagesPageSeo
{
    protected static ?string $navigationGroup = 'About Section';
    protected static ?string $navigationLabel = 'Media Gallery SEO';
    protected static string $settings = MediaGallerySeoSettings::class;
    protected static string $mediaFolder = 'media-gallery-seo';
    protected static string $pageLabel = 'the Media Gallery page';
}
