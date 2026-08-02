<?php

namespace App\Filament\Resources\OurStoryGalleryImageResource\Pages;

use App\Filament\Resources\OurStoryGalleryImageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOurStoryGalleryImage extends CreateRecord
{
    protected static string $resource = OurStoryGalleryImageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($data, 'image_url');

        return $data;
    }
}
