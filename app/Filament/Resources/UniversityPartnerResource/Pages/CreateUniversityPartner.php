<?php

namespace App\Filament\Resources\UniversityPartnerResource\Pages;

use App\Filament\Resources\UniversityPartnerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUniversityPartner extends CreateRecord
{
    protected static string $resource = UniversityPartnerResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($data, 'logo_url');

        return $data;
    }
}
