<?php

namespace App\Filament\Resources\ProgramResource\Pages;

use App\Filament\Concerns\HandlesCloudinaryImageFields;
use App\Filament\Resources\ProgramResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProgram extends EditRecord
{
    use HandlesCloudinaryImageFields;

    protected static string $resource = ProgramResource::class;

    protected array $seoData = [];

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load existing SEO data into form
        if ($this->record->seo) {
            $data['seo'] = $this->record->seo->toArray();
        }
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($data, 'image_url');

        $data = $this->preserveExistingImageFields($data, $this->record);

        if (isset($data['seo'])) {
            // MediaPicker stores the asset id in "{field}_asset_id" and syncs the
            // URL into "{field}". The seo_metadata table only persists the URL
            // columns, so strip the transient asset-id keys before saving.
            $seo = $data['seo'];
            unset(
                $seo['og_image_url_asset_id'],
                $seo['twitter_image_url_asset_id'],
            );
            $this->seoData = $seo;
            unset($data['seo']);
        }
        return $data;
    }

    protected function afterSave(): void
    {
        if (!empty($this->seoData)) {
            $this->record->seo()->updateOrCreate([], $this->seoData);
        }
    }
}