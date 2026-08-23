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
        $data = MediaPicker::syncFieldFromAsset($data, 'image_url');

        $data['is_active'] = $data['is_active'] ?? true;
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['published_at'] = $data['published_at'] ?? now();

        return $data;
    }
}
