<?php

namespace App\Filament\Resources\OurStoryTimelineResource\Pages;

use App\Filament\Resources\OurStoryTimelineResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOurStoryTimeline extends CreateRecord
{
    protected static string $resource = OurStoryTimelineResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($data, 'icon_url');

        return $data;
    }
}
