<?php

namespace App\Filament\Resources\MediaGalleryEventResource\Pages;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\MediaGalleryEventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMediaGalleryEvent extends CreateRecord
{
    protected static string $resource = MediaGalleryEventResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = MediaPicker::syncFieldFromAsset($data, 'image_url');

        return $data;
    }
}
