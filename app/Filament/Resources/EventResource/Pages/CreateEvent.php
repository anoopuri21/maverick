<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\EventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    use HandlesCloudinaryImageFields;

    protected static string $resource = EventResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = MediaPicker::syncFieldFromAsset($data, 'display_image');

        return $data;
    }
}
