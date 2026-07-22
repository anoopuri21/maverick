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
        return $this->preserveExistingImageFields($data, $this->record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
