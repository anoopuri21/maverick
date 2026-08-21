<?php

namespace App\Filament\Resources\FacultyInsightResource\Pages;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\FacultyInsightResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFacultyInsight extends CreateRecord
{
    protected static string $resource = FacultyInsightResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = MediaPicker::syncFieldFromAsset($data, 'hero_image_url');
        $data = MediaPicker::syncFieldFromAsset($data, 'image_url');
        $data = MediaPicker::syncFieldFromAsset($data, 'faculty_avatar_url');
        $data = MediaPicker::syncFieldFromAsset($data, 'og_image_url');

        return $data;
    }
}
