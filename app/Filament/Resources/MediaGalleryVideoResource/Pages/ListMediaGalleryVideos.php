<?php

namespace App\Filament\Resources\MediaGalleryVideoResource\Pages;

use App\Filament\Resources\MediaGalleryVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMediaGalleryVideos extends ListRecords
{
    protected static string $resource = MediaGalleryVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
