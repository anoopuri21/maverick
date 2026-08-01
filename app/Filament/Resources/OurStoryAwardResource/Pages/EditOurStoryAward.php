<?php

namespace App\Filament\Resources\OurStoryAwardResource\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Resources\OurStoryAwardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOurStoryAward extends EditRecord
{
    use HandlesCloudinaryImageFields;

    protected static string $resource = OurStoryAwardResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($data, 'image_url');

        return $this->preserveExistingImageFields($data, $this->record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
