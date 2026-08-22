<?php

namespace App\Filament\Resources\StudentSuccessVideoResource\Pages;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\StudentSuccessVideoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStudentSuccessVideo extends CreateRecord
{
    protected static string $resource = StudentSuccessVideoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return MediaPicker::syncFieldFromAsset($data, 'thumbnail_url');
    }
}
