<?php

namespace App\Filament\Resources\InsightResource\Pages;

use App\Filament\Resources\InsightResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInsight extends CreateRecord
{
    protected static string $resource = InsightResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($data, 'featured_image_url');

        return $data;
    }
}
