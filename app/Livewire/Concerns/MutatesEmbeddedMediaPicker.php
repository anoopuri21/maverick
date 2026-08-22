<?php

namespace App\Livewire\Concerns;

use App\Filament\Forms\Components\MediaPicker;

trait MutatesEmbeddedMediaPicker
{
    protected function mutateEmbeddedMedia(array $data, string $field, mixed $record = null, array $extra = []): array
    {
        $data = MediaPicker::syncFieldFromAsset($data, $field);

        if (empty($data[$field]) && is_object($record) && filled($record->{$field} ?? null)) {
            $data[$field] = $record->{$field};
        }

        return array_merge($data, $extra);
    }
}
