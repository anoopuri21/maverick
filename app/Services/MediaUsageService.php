<?php

namespace App\Services;

use App\Models\MediaAsset;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class MediaUsageService
{
    public function __construct(
        protected CloudinaryService $cloudinary,
    ) {}

    /**
     * Scan every media reference, persist used / is_duplicate flags.
     *
     * @return array{used: int, unused: int, duplicates: int, referenced_ids: list<int>}
     */
    public function refresh(): array
    {
        $referenced = $this->collectReferencedAssetIds();
        $duplicateIds = $this->duplicateAssetIds();

        $used = 0;
        $unused = 0;
        $duplicates = 0;

        MediaAsset::withoutGlobalScopes([SoftDeletingScope::class])
            ->orderBy('id')
            ->chunkById(200, function ($assets) use ($referenced, $duplicateIds, &$used, &$unused, &$duplicates) {
                foreach ($assets as $asset) {
                    $isUsed = isset($referenced[$asset->id]);
                    $isDup = isset($duplicateIds[$asset->id]);

                    $asset->forceFill([
                        'used' => $isUsed,
                        'is_duplicate' => $isDup,
                    ])->saveQuietly();

                    $isUsed ? $used++ : $unused++;
                    if ($isDup) {
                        $duplicates++;
                    }
                }
            });

        Log::info('[media-usage] Refreshed usage flags', [
            'used' => $used,
            'unused' => $unused,
            'duplicates' => $duplicates,
        ]);

        return [
            'used' => $used,
            'unused' => $unused,
            'duplicates' => $duplicates,
            'referenced_ids' => array_keys($referenced),
        ];
    }

    /**
     * @return array<int, true>
     */
    public function collectReferencedAssetIds(): array
    {
        $assets = MediaAsset::withoutGlobalScopes([SoftDeletingScope::class])
            ->get(['id', 'url', 'cloudinary_public_id']);

        $byId = [];
        $byUrl = [];
        $byPublicId = [];

        foreach ($assets as $asset) {
            $byId[(int) $asset->id] = (int) $asset->id;
            if (filled($asset->url)) {
                $byUrl[$this->normalizeUrl($asset->url)] = (int) $asset->id;
            }
            if (filled($asset->cloudinary_public_id)) {
                $byPublicId[$asset->cloudinary_public_id] = (int) $asset->id;
            }
        }

        $referenced = [];

        foreach ($this->tables() as $table) {
            if (in_array($table, config('media.schema_skip_tables', []), true)) {
                continue;
            }

            $columns = $this->columns($table);
            if ($columns === []) {
                continue;
            }

            $idColumns = [];
            $urlColumns = [];
            $jsonColumns = [];

            foreach ($columns as $column) {
                $name = $column['name'];
                $type = strtolower((string) ($column['type_name'] ?? $column['type'] ?? ''));

                if ($name === 'id') {
                    continue;
                }

                if ($name === 'media_asset_id' || str_ends_with($name, '_asset_id')) {
                    $idColumns[] = $name;
                } elseif ($this->looksLikeMediaUrlColumn($name)) {
                    $urlColumns[] = $name;
                }

                if (in_array($type, ['json', 'jsonb', 'text', 'longtext', 'mediumtext'], true)
                    && ($type === 'json' || $type === 'jsonb' || $name === 'payload' || str_contains($name, 'json'))) {
                    $jsonColumns[] = $name;
                }
            }

            if ($idColumns === [] && $urlColumns === [] && $jsonColumns === []) {
                continue;
            }

            $chunkColumn = $this->chunkColumn($table, $columns);
            $select = array_values(array_unique(array_merge([$chunkColumn], $idColumns, $urlColumns, $jsonColumns)));

            try {
                DB::table($table)->select($select)->orderBy($chunkColumn)->chunkById(200, function ($rows) use (
                    $idColumns,
                    $urlColumns,
                    $jsonColumns,
                    $byUrl,
                    $byPublicId,
                    &$referenced
                ) {
                    foreach ($rows as $row) {
                        $data = (array) $row;

                        foreach ($idColumns as $column) {
                            $id = $data[$column] ?? null;
                            if (is_numeric($id) && (int) $id > 0) {
                                $referenced[(int) $id] = true;
                            }
                        }

                        foreach ($urlColumns as $column) {
                            $this->markUrl($data[$column] ?? null, $byUrl, $byPublicId, $referenced);
                        }

                        foreach ($jsonColumns as $column) {
                            $this->walkJson($data[$column] ?? null, $byUrl, $byPublicId, $referenced);
                        }
                    }
                }, $chunkColumn);
            } catch (\Throwable $e) {
                Log::warning('[media-usage] Skipped table scan', [
                    'table' => $table,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $referenced;
    }

    /**
     * @return array<int, true>
     */
    protected function duplicateAssetIds(): array
    {
        $groups = MediaAsset::query()
            ->select('hash')
            ->whereNotNull('hash')
            ->where('hash', '!=', '')
            ->groupBy('hash')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('hash');

        if ($groups->isEmpty()) {
            return [];
        }

        $ids = [];
        MediaAsset::query()
            ->whereIn('hash', $groups)
            ->orderBy('id')
            ->get(['id', 'hash'])
            ->groupBy('hash')
            ->each(function ($rows) use (&$ids) {
                $rows->skip(1)->each(function ($row) use (&$ids) {
                    $ids[(int) $row->id] = true;
                });
            });

        return $ids;
    }

    /**
     * @param  array<string, int>  $byUrl
     * @param  array<string, int>  $byPublicId
     * @param  array<int, true>  $referenced
     */
    protected function markUrl(mixed $value, array $byUrl, array $byPublicId, array &$referenced): void
    {
        if (! is_string($value) || $value === '') {
            return;
        }

        $normalized = $this->normalizeUrl($value);
        if (isset($byUrl[$normalized])) {
            $referenced[$byUrl[$normalized]] = true;
        }

        $publicId = $this->cloudinary->extractPublicId($value);
        if ($publicId && isset($byPublicId[$publicId])) {
            $referenced[$byPublicId[$publicId]] = true;
        }
    }

    /**
     * @param  array<string, int>  $byUrl
     * @param  array<string, int>  $byPublicId
     * @param  array<int, true>  $referenced
     */
    protected function walkJson(mixed $value, array $byUrl, array $byPublicId, array &$referenced, int $depth = 0): void
    {
        if ($depth > 20) {
            return;
        }

        if (is_string($value)) {
            $trimmed = trim($value);
            if ($trimmed === '') {
                return;
            }

            if (($trimmed[0] ?? '') === '{' || ($trimmed[0] ?? '') === '[') {
                $decoded = json_decode($trimmed, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $this->walkJson($decoded, $byUrl, $byPublicId, $referenced, $depth + 1);

                    return;
                }
            }

            $this->markUrl($value, $byUrl, $byPublicId, $referenced);

            return;
        }

        if (! is_array($value)) {
            return;
        }

        foreach ($value as $key => $item) {
            if (is_string($key) && (str_ends_with($key, '_asset_id') || $key === 'media_asset_id') && is_numeric($item) && (int) $item > 0) {
                $referenced[(int) $item] = true;
            }

            $this->walkJson($item, $byUrl, $byPublicId, $referenced, $depth + 1);
        }
    }

    protected function looksLikeMediaUrlColumn(string $name): bool
    {
        $name = strtolower($name);

        return str_contains($name, 'image')
            || str_contains($name, 'logo')
            || str_contains($name, 'photo')
            || str_contains($name, 'thumbnail')
            || str_contains($name, 'banner')
            || str_contains($name, 'avatar')
            || str_contains($name, 'favicon')
            || str_contains($name, 'icon')
            || str_ends_with($name, '_url');
    }

    /**
     * @return list<string>
     */
    protected function tables(): array
    {
        if (method_exists(Schema::getFacadeRoot(), 'getTableListing')) {
            $tables = Schema::getTableListing();
        } else {
            $tables = Schema::getAllTables();
        }

        return array_values(array_filter(array_map(function ($table) {
            if (is_string($table)) {
                return $table;
            }

            if (is_object($table)) {
                return $table->name ?? $table->tablename ?? $table->table_name ?? null;
            }

            return null;
        }, $tables)));
    }

    /**
     * @return list<array{name: string, type?: string, type_name?: string}>
     */
    protected function columns(string $table): array
    {
        if (method_exists(Schema::getFacadeRoot(), 'getColumns')) {
            return Schema::getColumns($table);
        }

        return array_map(
            static fn (string $name) => ['name' => $name, 'type_name' => ''],
            Schema::getColumnListing($table)
        );
    }

    /**
     * @param  list<array{name: string}>  $columns
     */
    protected function chunkColumn(string $table, array $columns): string
    {
        $names = array_column($columns, 'name');

        if (in_array('id', $names, true)) {
            return 'id';
        }

        return $names[0] ?? 'id';
    }

    protected function normalizeUrl(string $url): string
    {
        $url = trim($url);
        $parts = parse_url($url);

        if (! is_array($parts) || empty($parts['host'])) {
            return $url;
        }

        $scheme = strtolower($parts['scheme'] ?? 'https');
        $host = strtolower($parts['host']);
        $path = $parts['path'] ?? '';

        return $scheme.'://'.$host.$path;
    }
}
