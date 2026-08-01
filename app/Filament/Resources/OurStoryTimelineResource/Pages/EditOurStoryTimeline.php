<?php

namespace App\Filament\Resources\OurStoryTimelineResource\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Resources\OurStoryTimelineResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOurStoryTimeline extends EditRecord
{
    use HandlesCloudinaryImageFields;

    protected static string $resource = OurStoryTimelineResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($data, 'icon_url');

        return $this->preserveExistingImageFields($data, $this->record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
