<?php

namespace App\Filament\Resources\GupPartnerUniversityResource\Pages;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\GupPartnerUniversityResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGupPartnerUniversity extends CreateRecord
{
    protected static string $resource = GupPartnerUniversityResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return MediaPicker::syncFieldFromAsset($data, 'logo_url');
    }
}
