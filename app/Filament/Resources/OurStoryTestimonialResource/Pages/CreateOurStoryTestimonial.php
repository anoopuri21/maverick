<?php

namespace App\Filament\Resources\OurStoryTestimonialResource\Pages;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\OurStoryTestimonialResource;
use App\Support\TestimonialPages;
use Filament\Resources\Pages\CreateRecord;

class CreateOurStoryTestimonial extends CreateRecord
{
    protected static string $resource = OurStoryTestimonialResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = MediaPicker::syncFieldFromAsset($data, 'photo', 'media_asset_id');
        $data['pages'] = TestimonialPages::normalize($data['pages'] ?? []);

        return $data;
    }
}
