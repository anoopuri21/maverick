<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    use HandlesCloudinaryImageFields;

    protected static string $resource = EventResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (isset($data['gallery_images']) && is_array($data['gallery_images'])) {
            $data['gallery_images'] = array_map(function ($item) {
                if (is_string($item)) {
                    return ['url' => $item];
                }
                return $item;
            }, $data['gallery_images']);
        }

        if (isset($data['gallery_videos']) && is_array($data['gallery_videos'])) {
            $data['gallery_videos'] = array_map(function ($item) {
                if (is_string($item)) {
                    return ['url' => $item, 'title' => ''];
                }
                return $item;
            }, $data['gallery_videos']);
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = MediaPicker::syncFieldFromAsset($data, 'display_image');

        return $this->preserveExistingImageFields($data, $this->record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
