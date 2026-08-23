<?php

namespace App\Filament\Resources\PartnershipGalleryItemResource\Pages;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\PartnershipGalleryItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePartnershipGalleryItem extends CreateRecord
{
    protected static string $resource = PartnershipGalleryItemResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return MediaPicker::syncFieldFromAsset($data, 'image_url');
    }
}
