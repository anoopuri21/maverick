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
