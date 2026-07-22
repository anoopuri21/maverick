<?php

namespace App\Filament\Resources\FacultyInsightResource\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Resources\FacultyInsightResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFacultyInsight extends EditRecord
{
    use HandlesCloudinaryImageFields;

    protected static string $resource = FacultyInsightResource::class;

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