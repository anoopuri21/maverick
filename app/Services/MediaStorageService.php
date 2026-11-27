<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Stores permanent site images on this application's public disk.
 *
 * The public disk is exposed through Laravel's /storage symlink. The disk lives
 * outside the repository, so deployments must preserve storage/app/public and
 * it must be included in the server backup routine.
 */
class MediaStorageService
{
    /**
     * Store a newly-uploaded image using a non-guessable path.
     *
     * @return array{disk: string, path: string, url: string, size_bytes: int, mime_type: string|null, width: int|null, height: int|null}
     */
    public function storeFile(string $sourcePath, string $folder = 'general', ?string $originalName = null): array
    {
        if (! is_readable($sourcePath)) {
            throw new \RuntimeException('Media file is not readable.');
        }

        $mime = mime_content_type($sourcePath) ?: null;
        $extension = $this->extensionFor($mime, $originalName ?: $sourcePath);
        $path = $this->prefix().'/'.$this->safeFolder($folder).'/'.now()->format('Y/m').'/'.Str::uuid().'.'.$extension;
        $stream = fopen($sourcePath, 'rb');

        try {
            if (! Storage::disk($this->disk())->put($path, $stream, ['visibility' => 'public'])) {
                throw new \RuntimeException('Could not save the image to server storage.');
            }
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        return $this->describe($path, $mime);
    }

    /**
     * Copy one legacy Cloudinary image to deterministic server storage.
     *
     * A URL hash makes this operation idempotent: rerunning a failed migration
     * does not download the same source image again.
     *
     * @return array{disk: string, path: string, url: string, size_bytes: int, mime_type: string|null, width: int|null, height: int|null}
     */
    public function copyRemoteImage(string $url): array
    {
        $source = $this->canonicalSourceUrl($url);
        $pathPrefix = $this->prefix().'/cloudinary/'.hash('sha256', $source);
        $existing = $this->existingCloudinaryCopy($pathPrefix);

        if ($existing !== null) {
            return $this->describe($existing);
        }

        $response = Http::accept('image/avif,image/webp,image/apng,image/svg+xml,image/*;q=0.8,*/*;q=0.5')
            ->timeout((int) config('media.remote_download_timeout_seconds', 90))
            ->retry(2, 500)
            ->get($source);

        if (! $response->successful()) {
            throw new \RuntimeException("Could not download legacy image (HTTP {$response->status()}).");
        }

        $contents = $response->body();
        if ($contents === '') {
            throw new \RuntimeException('Downloaded legacy image was empty.');
        }

        $header = (string) $response->header('Content-Type', '');
        $mime = strtolower(trim(strtok($header, ';')));
        if (! str_starts_with($mime, 'image/')) {
            $detected = (new \finfo(FILEINFO_MIME_TYPE))->buffer($contents) ?: '';
            $mime = strtolower($detected);
        }

        if (! str_starts_with($mime, 'image/')) {
            throw new \RuntimeException('Legacy URL did not return an image.');
        }

        $path = $pathPrefix.'.'.$this->extensionFor($mime, $source);
        if (! Storage::disk($this->disk())->put($path, $contents, ['visibility' => 'public'])) {
            throw new \RuntimeException('Could not save the recovered image to server storage.');
        }

        return $this->describe($path, $mime);
    }

    public function delete(?string $disk, ?string $path): bool
    {
        if (blank($path)) {
            return false;
        }

        return Storage::disk($disk ?: $this->disk())->delete($path);
    }

    public function disk(): string
    {
        return (string) config('media.storage_disk', 'public');
    }

    public function publicUrl(string $path): string
    {
        // Public images must be served from this application's domain, not the
        // storage provider's domain. A relative URL also survives APP_URL changes.
        if ($this->disk() === 'public') {
            return '/storage/'.ltrim($path, '/');
        }

        return Storage::disk($this->disk())->url($path);
    }

    public function canonicalSourceUrl(string $url): string
    {
        $url = html_entity_decode(trim($url), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $parts = parse_url($url);

        if (! is_array($parts) || empty($parts['host'])) {
            throw new \InvalidArgumentException('Legacy media URL is not a valid absolute URL.');
        }

        $scheme = strtolower((string) ($parts['scheme'] ?? 'https'));
        $host = strtolower((string) $parts['host']);
        $path = $parts['path'] ?? '';
        $query = isset($parts['query']) && $parts['query'] !== '' ? '?'.$parts['query'] : '';

        return "{$scheme}://{$host}{$path}{$query}";
    }

    /**
     * @return array{disk: string, path: string, url: string, size_bytes: int, mime_type: string|null, width: int|null, height: int|null}
     */
    protected function describe(string $path, ?string $knownMime = null): array
    {
        $disk = Storage::disk($this->disk());
        $fullPath = method_exists($disk, 'path') ? $disk->path($path) : null;
        $dimensions = $fullPath && is_file($fullPath) ? (@getimagesize($fullPath) ?: [null, null]) : [null, null];

        return [
            'disk' => $this->disk(),
            'path' => $path,
            'url' => $this->publicUrl($path),
            'size_bytes' => (int) ($disk->size($path) ?: 0),
            'mime_type' => $knownMime ?: ($disk->mimeType($path) ?: null),
            'width' => $dimensions[0] ?? null,
            'height' => $dimensions[1] ?? null,
        ];
    }

    protected function existingCloudinaryCopy(string $pathPrefix): ?string
    {
        foreach (Storage::disk($this->disk())->files(dirname($pathPrefix)) as $path) {
            if (str_starts_with($path, $pathPrefix.'.')) {
                return $path;
            }
        }

        return null;
    }

    protected function prefix(): string
    {
        return trim((string) config('media.storage_prefix', 'media'), '/');
    }

    protected function safeFolder(string $folder): string
    {
        $segments = array_filter(explode('/', str_replace('\\', '/', $folder)), fn (string $part) => $part !== '' && $part !== '.');
        $segments = array_map(static function (string $part): string {
            $clean = Str::slug($part);

            return $clean !== '' ? $clean : 'general';
        }, $segments);

        return implode('/', $segments) ?: 'general';
    }

    protected function extensionFor(?string $mime, string $name): string
    {
        $fromMime = match (strtolower((string) $mime)) {
            'image/jpeg', 'image/jpg', 'image/pjpeg' => 'jpg',
            'image/png', 'image/apng' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/avif' => 'avif',
            'image/svg+xml' => 'svg',
            'image/bmp', 'image/x-ms-bmp' => 'bmp',
            'image/tiff' => 'tiff',
            'image/x-icon', 'image/vnd.microsoft.icon' => 'ico',
            default => null,
        };

        if ($fromMime !== null) {
            return $fromMime;
        }

        $extension = strtolower(pathinfo(parse_url($name, PHP_URL_PATH) ?: $name, PATHINFO_EXTENSION));

        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif', 'svg', 'bmp', 'tif', 'tiff', 'ico'], true)
            ? ($extension === 'jpeg' ? 'jpg' : $extension)
            : 'img';
    }
}
