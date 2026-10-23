<?php

namespace App\Filament\Resources\OurStoryTestimonialResource\Pages;

use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\OurStoryTestimonialResource;
use App\Models\OurStoryTestimonial;
use App\Support\TestimonialPages;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOurStoryTestimonial extends EditRecord
{
    protected static string $resource = OurStoryTestimonialResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        /** @var OurStoryTestimonial $record */
        $record = $this->getRecord();
        $data = MediaPicker::syncFieldFromAsset($data, 'photo', 'media_asset_id');
        $data = MediaPicker::preserveOrClearField($data, 'photo', $record->photo, 'media_asset_id');
        $data['pages'] = TestimonialPages::normalize($data['pages'] ?? []);

        return $data;
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
