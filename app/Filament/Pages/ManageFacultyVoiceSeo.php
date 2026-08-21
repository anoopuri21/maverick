<?php

namespace App\Filament\Pages;

use App\Filament\Support\ManagesPageSeo;
use App\Settings\FacultyVoiceSeoSettings;

class ManageFacultyVoiceSeo extends ManagesPageSeo
{
    protected static ?string $navigationGroup = 'Insights';

    protected static ?string $navigationLabel = 'Faculty Voice SEO';

    protected static string $settings = FacultyVoiceSeoSettings::class;

    protected static string $mediaFolder = 'faculty-voice-seo';

    protected static string $pageLabel = 'the Faculty Voice listing page';
}
