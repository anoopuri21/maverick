<?php

namespace App\Filament\Resources\FacultyInsightResource\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\FacultyInsightResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFacultyInsight extends EditRecord
{
    use HandlesCloudinaryImageFields;

    protected static string $resource = FacultyInsightResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = MediaPicker::syncFieldFromAsset($data, 'hero_image_url');
        $data = MediaPicker::syncFieldFromAsset($data, 'image_url');
        $data = MediaPicker::syncFieldFromAsset($data, 'faculty_avatar_url');
        $data = MediaPicker::syncFieldFromAsset($data, 'og_image_url');

        return $this->preserveExistingImageFields($data, $this->record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
