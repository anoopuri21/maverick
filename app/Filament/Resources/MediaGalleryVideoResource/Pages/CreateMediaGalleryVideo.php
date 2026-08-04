<?php

namespace App\Filament\Resources\MediaGalleryVideoResource\Pages;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\MediaGalleryVideoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMediaGalleryVideo extends CreateRecord
{
    protected static string $resource = MediaGalleryVideoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = MediaPicker::syncFieldFromAsset($data, 'thumbnail_url');

        return $data;
    }
}
