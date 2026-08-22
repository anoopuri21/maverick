<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    protected ?Cloudinary $cloudinary = null;

    public function hasCredentials(): bool
    {
        return filled(config('services.cloudinary.cloud_name'))
            && filled(config('services.cloudinary.api_key'))
            && filled(config('services.cloudinary.api_secret'));
    }

    public function usesEnvFolder(): bool
    {
        return (bool) config('services.cloudinary.env_folder', false);
    }

    /**
     * disk_env stored on media_assets. Shared-folder mode uses a stable value
     * so local and production see the same library rows.
     */
    public function diskEnv(): string
    {
        if ($this->usesEnvFolder()) {
            return app()->environment();
        }

        $configured = config('services.cloudinary.disk_env', 'shared');

        return filled($configured) ? (string) $configured : 'shared';
    }

    protected function client(): Cloudinary
    {
        if ($this->cloudinary instanceof Cloudinary) {
            return $this->cloudinary;
        }

        $cloudName = config('services.cloudinary.cloud_name');
        $apiKey = config('services.cloudinary.api_key');
        $apiSecret = config('services.cloudinary.api_secret');

        if (empty($cloudName) || empty($apiKey) || empty($apiSecret)) {
            throw new \RuntimeException(
                'Cloudinary credentials are missing. Please check your .env file for: '.
                'CLOUDINARY_CLOUD_NAME, CLOUDINARY_API_KEY, CLOUDINARY_API_SECRET. '.
                'After adding them, run: php artisan config:clear'
            );
        }

        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $cloudName,
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
            ],
            'url' => [
                'secure' => (bool) config('services.cloudinary.secure', true),
            ],
        ]);

        return $this->cloudinary;
    }

    /**
     * Upload an image to Cloudinary and return the secure URL.
     */
    public function uploadImage(string $filePath, string $folder = 'general'): ?string
    {
        $result = $this->uploadImageDetailed($filePath, $folder);

        return $result['url'] ?? null;
    }

    /**
     * Upload an image and return url + public_id metadata.
     *
     * @return array{url: string|null, public_id: string|null, folder: string}
     */
    public function uploadImageDetailed(string $filePath, string $folder = 'general'): array
    {
        try {
            $fullFolder = $this->resolveUploadFolder($folder);

            $result = $this->client()->uploadApi()->upload($filePath, [
                'folder' => $fullFolder,
                'resource_type' => 'image',
                'transformation' => [
                    'quality' => 'auto:good',
                    'fetch_format' => 'auto',
                ],
                'overwrite' => false,
                'unique_filename' => true,
            ]);

            $publicId = $result['public_id'] ?? null;

            return [
                'url' => $result['secure_url'] ?? null,
                'public_id' => $publicId,
                'folder' => $publicId ? $this->folderFromPublicId((string) $publicId) : $fullFolder,
            ];
        } catch (\Exception $e) {
            Log::error('Cloudinary upload failed: '.$e->getMessage());
            throw $e;
        }
    }

    public function resolveUploadFolder(string $folder = 'general'): string
    {
        $folder = trim(str_replace('\\', '/', $folder), '/');
        $base = $this->resolveBaseFolder();

        if ($folder === '' || $folder === $base) {
            return $base;
        }

        if (str_starts_with($folder, $base.'/')) {
            return $folder;
        }

        return $base.'/'.$folder;
    }

    /**
     * Shared Cloudinary base folder. Env suffix is opt-in via CLOUDINARY_ENV_FOLDER.
     */
    public function resolveBaseFolder(): string
    {
        $base = trim((string) config('services.cloudinary.upload_folder', 'maverick-academy'), '/');

        if ($base === '') {
            $base = 'maverick-academy';
        }

        if (! $this->usesEnvFolder()) {
            return $base;
        }

        $prefix = config('services.cloudinary.env_prefix');

        if ($prefix === null || $prefix === '') {
            $prefix = app()->environment('production') ? null : app()->environment();
        }

        if (filled($prefix)) {
            return rtrim($base, '-').'-'.$prefix;
        }

        return $base;
    }

    /**
     * Prefixes to scan when listing (shared folder + leftover env-scoped folders).
     *
     * @return list<string>
     */
    public function listPrefixes(): array
    {
        $base = trim((string) config('services.cloudinary.upload_folder', 'maverick-academy'), '/');
        $prefixes = [$this->resolveBaseFolder()];

        if ($base !== '') {
            $prefixes[] = $base;
            foreach ((array) config('services.cloudinary.legacy_env_suffixes', []) as $suffix) {
                if (filled($suffix)) {
                    $prefixes[] = rtrim($base, '-').'-'.$suffix;
                }
            }
        }

        return array_values(array_unique(array_filter($prefixes)));
    }

    /**
     * List uploaded images under a Cloudinary public_id prefix (Admin API, one page).
     *
     * @return array{resources: array<int, array<string, mixed>>, next_cursor: string|null}
     */
    public function listImagesByPrefix(string $prefix, ?string $nextCursor = null): array
    {
        try {
            $options = [
                'resource_type' => 'image',
                'type' => 'upload',
                'prefix' => $prefix,
                'max_results' => 500,
            ];

            if ($nextCursor) {
                $options['next_cursor'] = $nextCursor;
            }

            $result = $this->client()->adminApi()->assets($options);

            return [
                'resources' => isset($result['resources']) && is_array($result['resources'])
                    ? $result['resources']
                    : [],
                'next_cursor' => $result['next_cursor'] ?? null,
            ];
        } catch (\Exception $e) {
            Log::error('Cloudinary listImagesByPrefix failed: '.$e->getMessage(), [
                'prefix' => $prefix,
                'next_cursor' => $nextCursor,
            ]);

            throw $e;
        }
    }

    public function deleteImage(string $imageUrl): bool
    {
        $publicId = $this->extractPublicId($imageUrl);

        return $publicId ? $this->deleteByPublicId($publicId) : false;
    }

    public function deleteByPublicId(string $publicId): bool
    {
        try {
            $this->client()->uploadApi()->destroy($publicId);

            return true;
        } catch (\Exception $e) {
            Log::error('Cloudinary delete failed: '.$e->getMessage(), [
                'public_id' => $publicId,
            ]);

            return false;
        }
    }

    public function extractPublicId(string $url): ?string
    {
        if (! str_contains($url, 'cloudinary.com')) {
            return null;
        }

        $pattern = '#/upload/(?:v\d+/)?(.+?)(?:\.[a-z]{3,4})?$#i';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    public function folderFromPublicId(string $publicId): string
    {
        $dirname = dirname($publicId);

        if ($dirname === '.' || $dirname === DIRECTORY_SEPARATOR) {
            return '';
        }

        return str_replace('\\', '/', $dirname);
    }

    public function normalizeFolderPath(string $folder): string
    {
        $folder = str_replace('\\', '/', $folder);
        $base = trim((string) config('services.cloudinary.upload_folder', 'maverick-academy'), '/');

        if ($base === '' || $this->usesEnvFolder()) {
            return $folder;
        }

        $suffixes = implode('|', array_map(
            static fn (string $s) => preg_quote($s, '/'),
            (array) config('services.cloudinary.legacy_env_suffixes', [])
        ));

        if ($suffixes === '') {
            return $folder;
        }

        return (string) preg_replace(
            '/^'.preg_quote($base, '/').'-('.$suffixes.')\b/',
            $base,
            $folder
        );
    }
}
