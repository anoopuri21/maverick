<?php

namespace App\Filament\Resources\PartnerLogoResource\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Resources\PartnerLogoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPartnerLogo extends EditRecord
{
    use HandlesCloudinaryImageFields;

    protected static string $resource = PartnerLogoResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Sync asset from MediaPicker
        $data = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($data, 'logo_url');

        // If logo_url is null but we have an existing value, preserve it
        if (empty($data['logo_url']) && !empty($this->record->logo_url)) {
            $data['logo_url'] = $this->record->logo_url;
        }

        // If logo_url_asset_id is null but we have an existing value, preserve it
        if (empty($data['logo_url_asset_id']) && !empty($this->record->logo_url_asset_id)) {
            $data['logo_url_asset_id'] = $this->record->logo_url_asset_id;
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
