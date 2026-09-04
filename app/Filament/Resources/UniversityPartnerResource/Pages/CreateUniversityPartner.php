<?php

namespace App\Filament\Resources\UniversityPartnerResource\Pages;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\UniversityPartnerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUniversityPartner extends CreateRecord
{
    protected static string $resource = UniversityPartnerResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = MediaPicker::syncFieldFromAsset($data, 'logo_url');
        $data = MediaPicker::syncFieldFromAsset($data, 'campus_image_url');

        return $data;
    }
}
