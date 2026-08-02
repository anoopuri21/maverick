<?php

namespace App\Filament\Resources\OurStoryTestimonialResource\Pages;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\OurStoryTestimonialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOurStoryTestimonial extends EditRecord
{
    protected static string $resource = OurStoryTestimonialResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return MediaPicker::syncUrlFromAsset($data, 'photo');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
