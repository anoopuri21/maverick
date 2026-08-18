<?php

namespace App\Filament\Resources\PartnershipGalleryItemResource\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Forms\Components\MediaPicker;
use App\Filament\Resources\PartnershipGalleryItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPartnershipGalleryItem extends EditRecord
{
    use HandlesCloudinaryImageFields;

    protected static string $resource = PartnershipGalleryItemResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = MediaPicker::syncFieldFromAsset($data, 'image_url');

        return $this->preserveExistingImageFields($data, $this->record);
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
