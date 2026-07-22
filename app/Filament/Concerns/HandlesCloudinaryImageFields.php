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