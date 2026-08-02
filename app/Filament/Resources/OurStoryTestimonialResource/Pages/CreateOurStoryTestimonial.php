<?php

namespace App\Filament\Resources\OurStoryTestimonialResource\Pages;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\OurStoryTestimonialResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOurStoryTestimonial extends CreateRecord
{
    protected static string $resource = OurStoryTestimonialResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return MediaPicker::syncUrlFromAsset($data, 'photo');
    }
}
