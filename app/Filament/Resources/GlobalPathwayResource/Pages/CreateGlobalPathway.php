<?php

namespace App\Filament\Resources\GlobalPathwayResource\Pages;

use App\Filament\Resources\GlobalPathwayResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateGlobalPathway extends CreateRecord
{
    protected static string $resource = GlobalPathwayResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['slug']) && ! empty($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        return $data;
    }
}
