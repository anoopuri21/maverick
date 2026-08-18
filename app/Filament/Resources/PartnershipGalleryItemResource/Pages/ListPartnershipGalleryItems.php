<?php

namespace App\Filament\Resources\PartnershipGalleryItemResource\Pages;

use App\Filament\Resources\PartnershipGalleryItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPartnershipGalleryItems extends ListRecords
{
    protected static string $resource = PartnershipGalleryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
