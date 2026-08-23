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
        protected CloudinaryService $cloudinary,
    ) {}

    public function currentDiskEnv(): string
    {
        return $this->cloudinary->diskEnv();
    }

    public function scopeLibrary(Builder $query): Builder
    {
        if ($this->cloudinary->usesEnvFolder()) {
            $query->where('disk_env', $this->currentDiskEnv());
        }

        return $query;
    }

    /**
     * Store a file in the media library with SHA-256 dedupe.
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

        $existingQuery = MediaAsset::withoutGlobalScopes([SoftDeletingScope::class])
            ->where('hash', $hash);

        if ($this->cloudinary->usesEnvFolder()) {
            $existingQuery->where('disk_env', $diskEnv);
        }

        $existing = $existingQuery->first();

        if ($existing) {
            if ($existing->trashed()) {
                $existing->restore();
                Log::info('[media-library] Restored soft-deleted asset', ['id' => $existing->id, 'hash' => $hash]);
            } else {
                Log::info('[media-library] Reusing existing asset', ['id' => $existing->id, 'hash' => $hash]);
            }

            return $existing;
        }

        $uploaded = $this->cloudinary->uploadImageDetailed($path, $folder);
        $url = $uploaded['url'] ?? null;
        $publicId = $uploaded['public_id'] ?? null;

        if (! $url || ! $publicId) {
            throw new \RuntimeException('Cloudinary upload did not return url/public_id');
        }

        $dimensions = @getimagesize($path) ?: [null, null];

        $asset = MediaAsset::create([
            'hash' => $hash,
            'original_name' => $originalName,
            'mime_type' => $mime !== '' ? $mime : null,
            'size_bytes' => $sizeBytes ?: null,
            'width' => $dimensions[0] ?? null,
            'height' => $dimensions[1] ?? null,
            'cloudinary_public_id' => $publicId,
            'url' => $url,
            'folder' => $uploaded['folder'] ?? $this->cloudinary->resolveUploadFolder($folder),
            'disk_env' => $diskEnv,
            'used' => false,
            'is_duplicate' => false,
        ]);

        Log::info('[media-library] Created new asset', ['id' => $asset->id, 'hash' => $hash, 'folder' => $asset->folder]);

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
