<?php

namespace App\Filament\Pages;

/**
 * Single "Global University Partners Page" with real tabs:
 * Page Content (inherited settings form) + Partner Universities + Partnership Gallery.
 *
 * Extends the existing settings page so its mount()/form()/save() are reused
 * (single source of truth — no duplicated form schema).
 */
class ManageGlobalPartnersPage extends ManageGlobalUniversityPartners
{
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationGroup = 'About Section';
    protected static ?string $navigationLabel = 'Global University Partners Page';
    protected static ?int $navigationSort = 7;
    protected static string $view = 'filament.pages.manage-global-partners-page';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
