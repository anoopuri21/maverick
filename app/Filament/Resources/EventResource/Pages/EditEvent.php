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
        $data['gallery_images'] = EventResource::normalizeRepeaterState(
            $data['gallery_images'] ?? null,
            fn ($item) => is_string($item) ? ['url' => $item] : (is_array($item) ? $item : null)
        );

        $data['gallery_videos'] = EventResource::normalizeRepeaterState(
            $data['gallery_videos'] ?? null,
            fn ($item) => is_string($item) ? ['url' => $item, 'title' => ''] : (is_array($item) ? $item : null)
        );

        $data['registration_fields'] = EventResource::normalizeRepeaterState(
            $data['registration_fields'] ?? null,
            fn ($item) => is_array($item) ? $item : null
        );

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
