<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

/**
 * Single "Media Gallery" link with real tabs: Gallery Photos + Featured Videos.
 */
class ManageMediaGalleryPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'About Section';
    protected static ?string $navigationLabel = 'Media Gallery';
    protected static ?int $navigationSort = 12;
    protected static string $view = 'filament.pages.manage-media-gallery-page';
}
