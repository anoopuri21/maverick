<?php

namespace App\Filament\Resources\InsightResource\Pages;

use App\Filament\Resources\InsightResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInsight extends EditRecord
{
    protected static string $resource = InsightResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($data, 'featured_image_url');

        if (empty($data['featured_image_url']) && ! empty($this->record->featured_image_url)) {
            $data['featured_image_url'] = $this->record->featured_image_url;
        }

        return $data;
    }
}
