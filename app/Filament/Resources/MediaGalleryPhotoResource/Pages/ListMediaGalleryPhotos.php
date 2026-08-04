<?php

namespace App\Filament\Resources\MediaGalleryPhotoResource\Pages;

use App\Filament\Resources\MediaGalleryPhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMediaGalleryPhotos extends ListRecords
{
    protected static string $resource = MediaGalleryPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
