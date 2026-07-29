<?php

namespace App\Filament\Resources\OurStoryGalleryImageResource\Pages;

use App\Filament\Resources\OurStoryGalleryImageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOurStoryGalleryImages extends ListRecords
{
    protected static string $resource = OurStoryGalleryImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
