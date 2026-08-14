<?php

namespace App\Filament\Resources\AccreditationAwardResource\Pages;

use App\Filament\Resources\AccreditationAwardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccreditationAward extends EditRecord
{
    protected static string $resource = AccreditationAwardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($data, 'logo_url');
        // Keep type locked to award.
        $data['type'] = 'award';
        return $data;
    }
}
