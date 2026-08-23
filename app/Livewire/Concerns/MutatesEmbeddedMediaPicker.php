<?php

namespace App\Livewire\Concerns;

use App\Filament\Forms\Components\MediaPicker;

trait MutatesEmbeddedMediaPicker
{
    protected function mutateEmbeddedMedia(array $data, string $field, mixed $record = null, array $extra = []): array
    {
        $assetKey = "{$field}_asset_id";
        $data = MediaPicker::syncFieldFromAsset($data, $field);

        if (empty($data[$field]) && is_object($record) && filled($record->{$field} ?? null)) {
            // Preserve legacy URL when picker is empty, but allow intentional clear
            // when the record previously had an asset id and the form cleared it.
            $userClearedAsset = array_key_exists($assetKey, $data)
                && empty($data[$assetKey])
                && filled($record->{$assetKey} ?? null);

            if (! $userClearedAsset) {
                $data[$field] = $record->{$field};
            }
        }

        return array_merge($data, $extra);
    }
}
