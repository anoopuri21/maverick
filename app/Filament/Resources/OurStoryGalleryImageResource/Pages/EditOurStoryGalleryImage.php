<?php

namespace App\Filament\Resources\OurStoryGalleryImageResource\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Resources\OurStoryGalleryImageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOurStoryGalleryImage extends EditRecord
{
    use HandlesCloudinaryImageFields;

    protected static string $resource = OurStoryGalleryImageResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Sync asset from MediaPicker
        $data = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($data, 'image_url');

        // If image_url is null but we have an existing value, preserve it
        if (empty($data['image_url']) && !empty($this->record->image_url)) {
            $data['image_url'] = $this->record->image_url;
        }

        // If image_url_asset_id is null but we have an existing value, preserve it
        if (empty($data['image_url_asset_id']) && !empty($this->record->image_url_asset_id)) {
            $data['image_url_asset_id'] = $this->record->image_url_asset_id;
        }

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
