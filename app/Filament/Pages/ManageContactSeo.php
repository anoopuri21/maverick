<?php

namespace App\Filament\Pages;

use App\Filament\Support\ManagesPageSeo;
use App\Settings\ContactSeoSettings;

class ManageContactSeo extends ManagesPageSeo
{
    protected static ?string $navigationGroup = 'Site Settings';
    protected static ?string $navigationLabel = 'Contact Page SEO';
    protected static string $settings = ContactSeoSettings::class;
    protected static string $mediaFolder = 'contact-seo';
    protected static string $pageLabel = 'the Contact page';
}
