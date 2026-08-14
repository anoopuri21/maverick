<?php

namespace App\Filament\Resources\ProgramResource\Pages;

use App\Filament\Resources\ProgramResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProgram extends CreateRecord
{
    protected static string $resource = ProgramResource::class;

    protected array $seoData = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($data, 'image_url');

        // Extract SEO data before creating program
        if (isset($data['seo'])) {
            // MediaPicker stores "{field}_asset_id"; seo_metadata only has URL
            // columns, so drop the transient asset-id keys before creating.
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

    protected function afterCreate(): void
    {
        // Save SEO data as morph relation
        if (!empty($this->seoData)) {
            $this->record->seo()->create($this->seoData);
        }
    }
}