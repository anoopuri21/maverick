<?php

namespace App\Filament\Resources\StudentSuccessStoryResource\Pages;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\StudentSuccessStoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStudentSuccessStory extends CreateRecord
{
    protected static string $resource = StudentSuccessStoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return MediaPicker::syncFieldFromAsset($data, 'photo');
    }
}
