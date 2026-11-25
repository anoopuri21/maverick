<?php

namespace App\Services;

use App\Models\MediaAsset;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

/**
 * Copies legacy Cloudinary image URLs to the application disk and rewrites all
 * database references in place. It never deletes a remote asset.
 */
class CloudinaryMediaMigrationService
{
    /** @var array<string, string> */
    protected array $resolvedUrls = [];

    /** @var array<string, true> */
    protected array $seenUrls = [];

    /** @var list<string> */
    protected array $errors = [];

    /** @var array<string, int> */
    protected array $result = [];

    public function __construct(
        protected MediaStorageService $storage,
        protected CloudinaryService $cloudinary,
    ) {}

    /**
     * @return array{sources: int, unique_sources: int, recovered: int, already_present: int, rows_updated: int, static_recovered: int, remote_recovered: int, errors: list<string>}
     */
    public function run(bool $write, bool $includeRemote = true, bool $includeStatic = true): array
    {
        $this->resolvedUrls = [];
        $this->seenUrls = [];
        $this->errors = [];
        $this->result = [
            'sources' => 0,
            'unique_sources' => 0,
            'recovered' => 0,
            'already_present' => 0,
            'rows_updated' => 0,
            'static_recovered' => 0,
            'remote_recovered' => 0,
        ];

        $this->scanDatabase($write);

        if ($includeStatic) {
            $this->recoverStaticSources($write);
        }

        if ($includeRemote) {
            $this->recoverRemoteInventory($write);
        }

        $this->result['unique_sources'] = count($this->seenUrls);
        $this->result['errors'] = $this->errors;

        return $this->result;
    }

    protected function scanDatabase(bool $write): void
    {
        foreach ($this->tables() as $table) {
            if (in_array($table, config('media.schema_skip_tables', []), true)) {
                continue;
            }

            $columns = $this->columns($table);
            $chunkColumn = $this->chunkColumn($columns);
            $contentColumns = $this->contentColumns($columns);

            if ($chunkColumn === null || $contentColumns === []) {
                continue;
            }

            try {
                DB::table($table)
                    ->select(array_values(array_unique(array_merge([$chunkColumn], $contentColumns))))
                    ->orderBy($chunkColumn)
                    ->chunkById(100, function ($rows) use ($table, $chunkColumn, $contentColumns, $write): void {
                        foreach ($rows as $row) {
                            $data = (array) $row;
                            $updates = [];

                            foreach ($contentColumns as $column) {
                                $value = $data[$column] ?? null;
                                if (! is_string($value) || $value === '') {
                                    continue;
                                }

                                $replaced = $this->replaceUrls($value, $write);
                                if ($replaced !== $value) {
                                    $updates[$column] = $replaced;
                                }
                            }

                            if ($write && $updates !== []) {
                                DB::table($table)->where($chunkColumn, $data[$chunkColumn])->update($updates);
                                $this->result['rows_updated']++;
                            }
                        }
                    }, $chunkColumn);
            } catch (\Throwable $e) {
                $this->error("{$table}: {$e->getMessage()}");
            }
        }
    }

    protected function recoverStaticSources(bool $write): void
    {
        foreach ((array) config('media.legacy_static_cloudinary_urls', []) as $url) {
            if (! is_string($url) || ! $this->isCloudinaryImage($url)) {
                continue;
            }

            $this->track($url);
            if (! $write) {
                continue;
            }

            try {
                $this->recover($url);
                $this->result['static_recovered']++;
            } catch (\Throwable $e) {
                $this->error("static {$url}: {$e->getMessage()}");
            }
        }
    }

    protected function recoverRemoteInventory(bool $write): void
    {
        if (! $this->cloudinary->hasCredentials()) {
            $this->error('Cloudinary credentials are not configured; database URLs can still be migrated, but unreferenced remote images were not inventoried.');

            return;
        }

        try {
            $cursor = null;
            do {
                $page = $this->cloudinary->listAllImages($cursor);
                foreach ($page['resources'] as $resource) {
                    $url = $resource['secure_url'] ?? null;
                    if (! is_string($url) || ! $this->isCloudinaryImage($url)) {
                        continue;
                    }

                    $this->track($url);
                    if (! $write) {
                        continue;
                    }

                    try {
                        $this->recover($url, is_string($resource['public_id'] ?? null) ? $resource['public_id'] : null);
                        $this->result['remote_recovered']++;
                    } catch (\Throwable $e) {
                        $this->error("remote {$url}: {$e->getMessage()}");
                    }
                }
                $cursor = $page['next_cursor'];

                if ($cursor) {
                    usleep(250000);
                }
            } while ($cursor);
        } catch (\Throwable $e) {
            $this->error('Cloudinary inventory: '.$e->getMessage());
        }
    }

    protected function replaceUrls(string $value, bool $write): string
    {
        // Settings payloads are JSON and may contain escaped slashes. Normalize
        // only while scanning; if no Cloudinary image is found, preserve the
        // column byte-for-byte rather than reserializing arbitrary content.
        $candidate = str_replace('\\/', '/', $value);
        $matched = false;

        $result = preg_replace_callback(
            // JSON serializers may escape each slash as `\/`; both ordinary
            // URL columns and nested Spatie-settings JSON must be recovered.
            '~https?://(?:[a-z0-9-]+\.)?cloudinary\.com/[^\s<>"\'\)]+~i',
            function (array $match) use ($write, &$matched): string {
                $rawUrl = rtrim($match[0], "'.,;:");
                $suffix = substr($match[0], strlen($rawUrl));
                $url = $rawUrl;

                if (! $this->isCloudinaryImage($url)) {
                    return $match[0];
                }

                $matched = true;
                $this->track($url);
                if (! $write) {
                    return $match[0];
                }

                try {
                    return $this->recover($url).$suffix;
                } catch (\Throwable $e) {
                    $this->error("{$url}: {$e->getMessage()}");

                    return $match[0];
                }
            },
            $candidate
        );

        return $matched ? ($result ?? $value) : $value;
    }

    protected function recover(string $url, ?string $publicId = null): string
    {
        $source = $this->storage->canonicalSourceUrl($url);
        if (isset($this->resolvedUrls[$source])) {
            return $this->resolvedUrls[$source];
        }

        $expectedPathPrefix = trim((string) config('media.storage_prefix', 'media'), '/').'/cloudinary/'.hash('sha256', $source).'.';
        $alreadyPresent = collect(Storage::disk($this->storage->disk())->files(dirname($expectedPathPrefix)))
            ->contains(fn (string $path): bool => str_starts_with($path, $expectedPathPrefix));

        $stored = $this->storage->copyRemoteImage($source);
        $asset = $this->storeAsset($source, $stored, $publicId);

        $this->resolvedUrls[$source] = $asset->url;
        $this->result[$alreadyPresent ? 'already_present' : 'recovered']++;

        return $asset->url;
    }

    /**
     * @param  array{disk: string, path: string, url: string, size_bytes: int, mime_type: string|null, width: int|null, height: int|null}  $stored
     */
    protected function storeAsset(string $source, array $stored, ?string $publicId = null): MediaAsset
    {
        $asset = MediaAsset::withoutGlobalScopes([SoftDeletingScope::class])
            ->where('source_url', $source)
            ->orWhere('url', $source)
            ->first();

        $fullPath = Storage::disk($stored['disk'])->path($stored['path']);
        $hash = is_readable($fullPath) ? hash_file('sha256', $fullPath) : null;

        if (! $asset && $hash) {
            $asset = MediaAsset::withoutGlobalScopes([SoftDeletingScope::class])
                ->where('hash', $hash)
                ->first();
        }

        $attributes = [
            'hash' => $hash ?: ('imported:'.substr(hash('sha256', $source), 0, 55)),
            'original_name' => basename(parse_url($source, PHP_URL_PATH) ?: 'legacy-image'),
            'mime_type' => $stored['mime_type'],
            'size_bytes' => $stored['size_bytes'] ?: null,
            'width' => $stored['width'],
            'height' => $stored['height'],
            'cloudinary_public_id' => $publicId ?: $this->cloudinary->extractPublicId($source),
            'url' => $stored['url'],
            'source_url' => $source,
            'folder' => 'legacy/cloudinary',
            'disk_env' => 'shared',
            'storage_disk' => $stored['disk'],
            'storage_path' => $stored['path'],
        ];

        if ($asset) {
            $asset->forceFill(array_filter($attributes, static fn (mixed $value): bool => $value !== null))->saveQuietly();

            if ($asset->trashed()) {
                $asset->restore();
            }

            return $asset;
        }

        return MediaAsset::create($attributes + [
            'used' => false,
            'is_duplicate' => false,
        ]);
    }

    protected function track(string $url): void
    {
        $this->result['sources']++;
        $this->seenUrls[$this->storage->canonicalSourceUrl($url)] = true;
    }

    protected function isCloudinaryImage(string $url): bool
    {
        $parts = parse_url($url);
        if (! is_array($parts)) {
            return false;
        }

        $host = strtolower((string) ($parts['host'] ?? ''));
        $path = (string) ($parts['path'] ?? '');
        $isCloudinaryHost = $host === 'cloudinary.com' || str_ends_with($host, '.cloudinary.com');
        $hasImagePath = str_contains($path, '/image/upload/')
            || preg_match('/\.(?:avif|bmp|gif|ico|jpe?g|png|svg|tiff?|webp)$/i', $path) === 1;

        return $isCloudinaryHost && $hasImagePath;
    }

    /** @return list<string> */
    protected function tables(): array
    {
        $tables = method_exists(Schema::getFacadeRoot(), 'getTableListing')
            ? Schema::getTableListing()
            : Schema::getAllTables();

        return array_values(array_filter(array_map(static function (mixed $table): ?string {
            if (is_string($table)) {
                return $table;
            }

            return is_object($table) ? ($table->name ?? $table->tablename ?? $table->table_name ?? null) : null;
        }, $tables)));
    }

    /** @return list<array{name: string, type?: string, type_name?: string}> */
    protected function columns(string $table): array
    {
        if (method_exists(Schema::getFacadeRoot(), 'getColumns')) {
            return Schema::getColumns($table);
        }

        return array_map(static fn (string $name): array => ['name' => $name, 'type_name' => ''], Schema::getColumnListing($table));
    }

    /** @param list<array{name: string, type?: string, type_name?: string}> $columns */
    protected function chunkColumn(array $columns): ?string
    {
        $names = array_column($columns, 'name');

        return in_array('id', $names, true) ? 'id' : ($names[0] ?? null);
    }

    /** @param list<array{name: string, type?: string, type_name?: string}> $columns
     *  @return list<string>
     */
    protected function contentColumns(array $columns): array
    {
        $content = [];

        foreach ($columns as $column) {
            $name = $column['name'];
            $type = strtolower((string) ($column['type_name'] ?? $column['type'] ?? ''));

            if ($name === 'source_url') {
                continue;
            }

            if (str_contains($type, 'char') || str_contains($type, 'text') || str_contains($type, 'json')) {
                $content[] = $name;
            }
        }

        return $content;
    }

    protected function error(string $message): void
    {
        $this->errors[] = $message;
        Log::warning('[media:migrate-cloudinary] '.$message);
    }
}
