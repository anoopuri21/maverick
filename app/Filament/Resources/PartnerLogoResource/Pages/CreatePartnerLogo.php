<?php

namespace App\Filament\Resources\PartnerLogoResource\Pages;

use App\Filament\Resources\PartnerLogoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePartnerLogo extends CreateRecord
{
    protected static string $resource = PartnerLogoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($data, 'logo_url');

        return $data;
    }
}
