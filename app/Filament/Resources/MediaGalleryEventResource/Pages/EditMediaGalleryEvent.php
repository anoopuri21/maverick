<?php

namespace App\Filament\Resources\MediaGalleryEventResource\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\MediaGalleryEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMediaGalleryEvent extends EditRecord
{
    use HandlesCloudinaryImageFields;

    protected static string $resource = MediaGalleryEventResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = MediaPicker::syncFieldFromAsset($data, 'image_url');

        if (empty($data['image_url']) && !empty($this->record->image_url)) {
            $data['image_url'] = $this->record->image_url;
        }

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
