<?php

namespace App\Filament\Pages;

/**
 * Single "Accreditation" link with real tabs:
 * Cinematic Section (inherited settings form) + Awards & Recognition.
 *
 * Extends the existing settings page so its form()/save() are reused
 * (single source of truth — no duplicated form schema).
 */
class ManageAccreditationPage extends ManageAccreditationCinematic
{
    protected static ?string $navigationIcon = 'heroicon-o-check-badge';
    protected static ?string $navigationGroup = 'About Section';
    protected static ?string $navigationLabel = 'Accreditation';
    protected static ?int $navigationSort = 10;
    protected static string $view = 'filament.pages.manage-accreditation-page';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
