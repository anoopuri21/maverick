<?php

namespace App\Filament\Resources\UniversityPartnerResource\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Resources\UniversityPartnerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUniversityPartner extends EditRecord
{
    use HandlesCloudinaryImageFields;

    protected static string $resource = UniversityPartnerResource::class;

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
