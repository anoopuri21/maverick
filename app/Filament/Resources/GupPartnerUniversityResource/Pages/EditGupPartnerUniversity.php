<?php

namespace App\Filament\Resources\GupPartnerUniversityResource\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\GupPartnerUniversityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGupPartnerUniversity extends EditRecord
{
    use HandlesCloudinaryImageFields;

    protected static string $resource = GupPartnerUniversityResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = MediaPicker::syncFieldFromAsset($data, 'logo_url');

        return $this->preserveExistingImageFields($data, $this->record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
