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
        if ($this->record->seo) {
            $data['seo'] = $this->record->seo->toArray();
        }

        $data = $this->syncBenefitIconPresets($data);

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = \App\Filament\Forms\Components\MediaPicker::syncFieldFromAsset($data, 'image_url');

        $data = $this->preserveExistingImageFields($data, $this->record);

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

    /** Sync icon_preset select from stored icon values on load. */
    protected function syncBenefitIconPresets(array $data): array
    {
        $options = array_keys([
            'users' => 1, 'book-open' => 1, 'globe' => 1, 'trending-up' => 1, 'laptop' => 1,
            'sparkles' => 1, 'shield' => 1, 'award' => 1, 'graduation-cap' => 1, 'briefcase' => 1,
            'target' => 1, 'lightbulb' => 1, 'heart-handshake' => 1, 'clock' => 1, 'map-pin' => 1,
        ]);

        foreach ($data['benefits'] ?? [] as $i => $benefit) {
            $icon = $benefit['icon'] ?? null;
            if ($icon && in_array($icon, $options, true)) {
                $data['benefits'][$i]['icon_preset'] = $icon;
            }
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