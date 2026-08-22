<?php

namespace App\Filament\Pages;

use App\Filament\Support\ManagesPageSeo;
use App\Settings\StudentSuccessSeoSettings;

class ManageStudentSuccessSeo extends ManagesPageSeo
{
    protected static ?string $navigationGroup = 'Insights';
    protected static ?string $navigationLabel = 'Student Success SEO';
    protected static string $settings = StudentSuccessSeoSettings::class;
    protected static string $mediaFolder = 'student-success-seo';
    protected static string $pageLabel = 'the Student Success page';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
