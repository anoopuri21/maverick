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

        $data = ProgramResource::cleanJsonForSave($data);

        if (isset($data['seo'])) {
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