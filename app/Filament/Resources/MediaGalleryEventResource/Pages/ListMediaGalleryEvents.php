<?php

namespace App\Filament\Resources\MediaGalleryEventResource\Pages;

use App\Filament\Resources\MediaGalleryEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMediaGalleryEvents extends ListRecords
{
    protected static string $resource = MediaGalleryEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
