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
        $data = $this->preserveExistingImageFields($data, $this->record);

        if (isset($data['seo'])) {
            $this->seoData = $data['seo'];
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