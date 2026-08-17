<?php

namespace App\Filament\Resources\AccreditationAwardResource\Pages;

use App\Filament\Resources\AccreditationAwardResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAccreditationAward extends CreateRecord
{
    protected static string $resource = AccreditationAwardResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($data, 'logo_url');
        // Force this resource to always create award-type logos.
        $data['type'] = 'award';
        return $data;
    }
}
