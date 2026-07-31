<?php

namespace App\Services;

use App\Models\MediaAsset;
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
        return app()->environment();
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

        if (! is_readable($path)) {
            throw new \RuntimeException('Media file is not readable: '.$path);
        }

        $hash = hash_file('sha256', $path);
        $diskEnv = $this->currentDiskEnv();

        $existing = MediaAsset::withoutGlobalScopes([SoftDeletingScope::class])
            ->where('hash', $hash)
            ->where('disk_env', $diskEnv)
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
            'mime_type' => mime_content_type($path) ?: null,
            'size_bytes' => filesize($path) ?: null,
            'width' => $dimensions[0] ?? null,
            'height' => $dimensions[1] ?? null,
            'cloudinary_public_id' => $publicId,
            'url' => $url,
            'folder' => $folder,
            'disk_env' => $diskEnv,
        ]);

        Log::info('[media-library] Created new asset', ['id' => $asset->id, 'hash' => $hash, 'folder' => $folder]);

        return $asset;
    }

    protected function resolvePath(string|UploadedFile|TemporaryUploadedFile $file): string
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
