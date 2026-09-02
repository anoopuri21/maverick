<?php

namespace App\Filament\Concerns;

use App\Filament\Forms\Components\MediaPicker;

trait HydratesRepeaterMediaFields
{
    protected function syncImageIfSelected(array $payload, string $field): array
    {
        if (! empty($payload["{$field}_asset_id"])) {
            return MediaPicker::syncFieldFromAsset($payload, $field);
        }

        if (array_key_exists("{$field}_asset_id", $payload) && empty($payload["{$field}_asset_id"])) {
            return MediaPicker::syncFieldFromAsset($payload, $field);
        }

        return $payload;
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @return array<int, array<string, mixed>>
     */
    protected function hydrateRepeaterMediaFields(array $rows, string $field): array
    {
        foreach ($rows as &$row) {
            if (! is_array($row)) {
                continue;
            }

            $assetKey = "{$field}_asset_id";

            if (filled($row[$field] ?? null) || blank($row[$assetKey] ?? null)) {
                continue;
            }

            $row = MediaPicker::syncFieldFromAsset($row, $field);
        }

        unset($row);

        return array_values($rows);
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @param  array<int, array<string, mixed>>  $existingRows
     * @return array<int, array<string, mixed>>
     */
    protected function preserveRepeaterImageFields(array $rows, array $existingRows, string $field): array
    {
        $assetKey = "{$field}_asset_id";

        foreach ($rows as $index => &$row) {
            $existing = $existingRows[$index] ?? [];

            if (array_key_exists($assetKey, $row)) {
                if (empty($row[$assetKey]) && empty($row[$field])) {
                    continue;
                }
            }

            if (empty($row[$field]) && empty($row[$assetKey] ?? null)) {
                $row[$field] = $existing[$field] ?? null;
                $row[$assetKey] = $existing[$assetKey] ?? null;
            }
        }

        unset($row);

        return $rows;
    }
}
