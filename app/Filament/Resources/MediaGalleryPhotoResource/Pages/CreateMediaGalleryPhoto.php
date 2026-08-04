<?php

namespace App\Filament\Resources\MediaGalleryPhotoResource\Pages;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\MediaGalleryPhotoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMediaGalleryPhoto extends CreateRecord
{
    protected static string $resource = MediaGalleryPhotoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = MediaPicker::syncFieldFromAsset($data, 'image_url');

        return $data;
    }
}
