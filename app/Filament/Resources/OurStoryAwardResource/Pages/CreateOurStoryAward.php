<?php

namespace App\Filament\Resources\OurStoryAwardResource\Pages;

use App\Filament\Resources\OurStoryAwardResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOurStoryAward extends CreateRecord
{
    protected static string $resource = OurStoryAwardResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($data, 'image_url');

        return $data;
    }
}
