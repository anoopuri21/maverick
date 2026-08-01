<?php

namespace App\Filament\Resources\FacultyInsightResource\Pages;

use App\Filament\Resources\FacultyInsightResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFacultyInsight extends CreateRecord
{
    protected static string $resource = FacultyInsightResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($data, 'image_url');

        return $data;
    }
}
