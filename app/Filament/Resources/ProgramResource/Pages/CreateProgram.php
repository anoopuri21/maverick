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
        // Extract SEO data before creating program
        if (isset($data['seo'])) {
            $this->seoData = $data['seo'];
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