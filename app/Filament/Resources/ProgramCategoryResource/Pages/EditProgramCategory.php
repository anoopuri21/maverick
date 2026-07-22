<?php

namespace App\Filament\Resources\ProgramCategoryResource\Pages;

use App\Filament\Resources\ProgramCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProgramCategory extends EditRecord
{
    protected static string $resource = ProgramCategoryResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $image = $data['image_url'] ?? null;

        if (is_array($image)) {
            $image = array_values($image)[0] ?? null;
        }

        $data['image_url'] = filled($image) ? $image : $this->record->image_url;

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
