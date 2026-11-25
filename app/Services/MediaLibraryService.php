<?php

namespace App\Services;

use App\Models\MediaAsset;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MediaLibraryService
{
    public function __construct(
        protected MediaStorageService $storage,
    ) {}

    public function currentDiskEnv(): string
    {
        // Existing remote rows use `shared`; this distinct marker allows a new
        // self-hosted upload to coexist with the same legacy file hash while a
        // migration is still in progress.
        return 'self-hosted';
    }

    public function scopeLibrary(Builder $query): Builder
    {
        return $query;
    }

    /**
     * Store a file in the self-hosted media library with SHA-256 dedupe.
     */
    public function store(
        string|UploadedFile|TemporaryUploadedFile $file,
        string $folder = 'general',
        ?string $originalName = null,
    ): MediaAsset {
        $path = $this->resolvePath($file);
        $originalName ??= $this->resolveOriginalName($file);

        if ($path === false || $path === '' || ! is_readable($path)) {
            throw new \RuntimeException('Media file is not readable.');
        }

        $maxKb = (int) config('media.max_upload_kilobytes', 5120);
        $sizeBytes = filesize($path) ?: 0;
        if ($maxKb > 0 && $sizeBytes > ($maxKb * 1024)) {
            throw new \RuntimeException("Media file exceeds the {$maxKb} KB upload limit.");
        }

        $mime = mime_content_type($path) ?: '';
        $allowedPrefixes = config('media.allowed_mime_prefixes', ['image/']);
        $mimeOk = $mime === '' || collect($allowedPrefixes)->contains(
            fn (string $prefix) => str_starts_with($mime, $prefix)
        );
        if (! $mimeOk) {
            throw new \RuntimeException('Only image uploads are allowed in the media library.');
        }

        $hash = hash_file('sha256', $path);
        $diskEnv = $this->currentDiskEnv();

        $existing = MediaAsset::withoutGlobalScopes([SoftDeletingScope::class])
            ->where('hash', $hash)
            ->whereNotNull('storage_path')
            ->first();

        if ($existing) {
            if ($existing->trashed()) {
                $existing->restore();
                Log::info('[media-library] Restored soft-deleted asset', ['id' => $existing->id, 'hash' => $hash]);
            } else {
                Log::info('[media-library] Reusing existing asset', ['id' => $existing->id, 'hash' => $hash]);
            }

            return $existing;
        }

        $stored = $this->storage->storeFile($path, $folder, $originalName);

        $asset = MediaAsset::create([
            'hash' => $hash,
            'original_name' => $originalName,
            'mime_type' => $stored['mime_type'] ?: ($mime !== '' ? $mime : null),
            'size_bytes' => $stored['size_bytes'] ?: $sizeBytes ?: null,
            'width' => $stored['width'],
            'height' => $stored['height'],
            'cloudinary_public_id' => null,
            'url' => $stored['url'],
            'source_url' => null,
            'folder' => trim(str_replace('\\', '/', $folder), '/') ?: 'general',
            'disk_env' => $diskEnv,
            'storage_disk' => $stored['disk'],
            'storage_path' => $stored['path'],
            'used' => false,
            'is_duplicate' => false,
        ]);

        Log::info('[media-library] Created self-hosted asset', [
            'id' => $asset->id,
            'hash' => $hash,
            'folder' => $asset->folder,
            'path' => $asset->storage_path,
        ]);

        return $asset;
    }

    protected function resolvePath(string|UploadedFile|TemporaryUploadedFile $file): string|false
    {
        if (is_string($file)) {
            return $file;
        }

        return $file->getRealPath();
    }

    protected function resolveOriginalName(string|UploadedFile|TemporaryUploadedFile $file): ?string
    {
        if (is_string($file)) {
            return basename($file);
        }

        return $file->getClientOriginalName();
    }
}
