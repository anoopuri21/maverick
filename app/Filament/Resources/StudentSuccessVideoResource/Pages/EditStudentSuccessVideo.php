<?php

namespace App\Filament\Resources\StudentSuccessVideoResource\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\StudentSuccessVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudentSuccessVideo extends EditRecord
{
    use HandlesCloudinaryImageFields;

    protected static string $resource = StudentSuccessVideoResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = MediaPicker::syncFieldFromAsset($data, 'thumbnail_url');

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
