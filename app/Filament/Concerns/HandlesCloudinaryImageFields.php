<?php

namespace App\Filament\Concerns;

trait HandlesCloudinaryImageFields
{
    protected static function existingCloudinaryImage(?string $file): ?array
    {
        if (blank($file)) {
            return null;
        }

        $path = parse_url($file, PHP_URL_PATH) ?: $file;

        return [
            'name' => basename($path),
            'size' => 1,
            'type' => 'image/*',
            'url' => $file,
        ];
    }

    protected function preserveExistingImageFields(array $data, object|array $source): array
    {
        foreach ($data as $key => $value) {
            // Fields managed by MediaPicker (asset id + its synced URL column)
            // must not be "preserved": clearing the picker is intentional.
            if (str_ends_with($key, '_asset_id') || array_key_exists($key.'_asset_id', $data)) {
                continue;
            }

            if (! $this->isImageField($key)) {
                continue;
            }

            if (is_array($value)) {
                $value = array_values($value)[0] ?? null;
            }

            $existing = data_get($source, $key);

            $data[$key] = filled($value) ? $value : $existing;
        }

        return $data;
    }

    protected function isImageField(string $key): bool
    {
        $key = strtolower($key);

        return str_contains($key, 'image')
            || str_contains($key, 'logo')
            || str_contains($key, 'favicon')
            || str_contains($key, 'thumbnail')
            || str_contains($key, 'banner')
            || str_contains($key, 'photo')
            || str_contains($key, 'avatar')
            || str_contains($key, 'icon');
    }
}