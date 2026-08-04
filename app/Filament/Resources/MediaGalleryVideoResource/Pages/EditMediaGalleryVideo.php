<?php

namespace App\Filament\Resources\MediaGalleryVideoResource\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\MediaGalleryVideoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMediaGalleryVideo extends EditRecord
{
    use HandlesCloudinaryImageFields;

    protected static string $resource = MediaGalleryVideoResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = MediaPicker::syncFieldFromAsset($data, 'thumbnail_url');

        if (empty($data['thumbnail_url']) && !empty($this->record->thumbnail_url)) {
            $data['thumbnail_url'] = $this->record->thumbnail_url;
        }

        if (empty($data['thumbnail_url_asset_id']) && !empty($this->record->thumbnail_url_asset_id)) {
            $data['thumbnail_url_asset_id'] = $this->record->thumbnail_url_asset_id;
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
