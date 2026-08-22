<?php

namespace App\Filament\Resources\StudentSuccessStoryResource\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\StudentSuccessStoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudentSuccessStory extends EditRecord
{
    use HandlesCloudinaryImageFields;

    protected static string $resource = StudentSuccessStoryResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = MediaPicker::syncFieldFromAsset($data, 'photo');

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
