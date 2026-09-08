<?php

namespace App\Services;

use App\Models\MediaAsset;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * Read-only inventory of every stored image URL.
 *
 * Pre-flight for the Cloudinary account migration: reports which cloud names
 * are referenced, flags stored transformation URLs, legacy env-prefixed
 * public_ids, same-hash duplicate groups, and byte volume. Never writes.
 */
class MediaAuditService
{
    protected const SAMPLE_LIMIT = 50;

    /** @var array<string, array{url: string, source: string, via: string}> */
    protected array $urls = [];

    public function __construct(
        protected CloudinaryService $cloudinary,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function audit(): array
    {
        $this->urls = [];

        $baseFolder = trim((string) config('services.cloudinary.upload_folder', 'maverick-academy'), '/');

        $guard = [
            'env_folder' => $this->cloudinary->usesEnvFolder(),
            'disk_env' => $this->cloudinary->diskEnv(),
            'base_folder' => $this->cloudinary->resolveBaseFolder(),
            'pass' => ! $this->cloudinary->usesEnvFolder(),
        ];

        $assets = $this->assetStats();
        $this->collectAssetUrls();
        $scan = $this->scanTables();

        $cloudNames = [];
        $externalHosts = [];
        $unparsed = 0;
        $transformedTotal = 0;
        $transformedSamples = [];
        $legacySeen = [];
        $legacySamples = [];

        foreach ($this->urls as $row) {
            $url = $row['url'];
            $host = strtolower((string) parse_url($url, PHP_URL_HOST));

            if ($host === '') {
                $unparsed++;

                continue;
            }

            if (! str_contains($host, 'cloudinary.com')) {
                $externalHosts[$host] = ($externalHosts[$host] ?? 0) + 1;

                continue;
            }

            $cloud = $this->cloudNameFromUrl($url);
            $cloudNames[$cloud] = ($cloudNames[$cloud] ?? 0) + 1;

            if ($this->hasTransformation($url)) {
                $transformedTotal++;

                if (count($transformedSamples) < self::SAMPLE_LIMIT) {
                    $transformedSamples[] = ['url' => $url, 'source' => $row['source']];
                }
            }

            $publicId = $this->extractPublicIdSmart($url);

            if ($publicId && $baseFolder !== '' && str_starts_with($publicId, $baseFolder.'-')) {
                if (! isset($legacySeen[$publicId])) {
                    $legacySeen[$publicId] = true;

                    if (count($legacySamples) < self::SAMPLE_LIMIT) {
                        $legacySamples[] = ['public_id' => $publicId, 'source' => $row['source']];
                    }
                }
            }
        }

        // Rows with a legacy public_id but a blank/missing URL are not covered
        // by URL extraction above, so check the column directly.
        if ($baseFolder !== '') {
            $like = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $baseFolder).'-%';

            MediaAsset::withoutGlobalScopes([SoftDeletingScope::class])
                ->where('cloudinary_public_id', 'like', $like)
                ->orderBy('id')
                ->chunkById(200, function ($rows) use (&$legacySeen, &$legacySamples) {
                    foreach ($rows as $row) {
                        if (isset($legacySeen[$row->cloudinary_public_id])) {
                            continue;
                        }

                        $legacySeen[$row->cloudinary_public_id] = true;

                        if (count($legacySamples) < self::SAMPLE_LIMIT) {
                            $legacySamples[] = [
                                'public_id' => $row->cloudinary_public_id,
                                'source' => 'media_assets#'.$row->id,
                            ];
                        }
                    }
                });
        }

        arsort($cloudNames);
        arsort($externalHosts);

        return [
            'guard' => $guard,
            'assets' => $assets,
            'scan' => array_merge($scan, ['distinct_urls' => count($this->urls)]),
            'cloud_names' => $cloudNames,
            'external_hosts' => $externalHosts,
            'unparsed_urls' => $unparsed,
            'transformed' => ['total' => $transformedTotal, 'samples' => $transformedSamples],
            'legacy_public_ids' => ['total' => count($legacySeen), 'samples' => $legacySamples],
            'duplicate_groups' => $this->duplicateGroups(),
        ];
    }

    /**
     * @return array{total: int, trashed: int, missing_url: int, missing_public_id: int, total_bytes: int, unknown_bytes: int}
     */
    protected function assetStats(): array
    {
        $query = MediaAsset::withoutGlobalScopes([SoftDeletingScope::class]);

        return [
            'total' => (clone $query)->count(),
            'trashed' => MediaAsset::onlyTrashed()->count(),
            'missing_url' => (clone $query)->where(function ($q) {
                $q->whereNull('url')->orWhere('url', '');
            })->count(),
            'missing_public_id' => (clone $query)->where(function ($q) {
                $q->whereNull('cloudinary_public_id')->orWhere('cloudinary_public_id', '');
            })->count(),
            'total_bytes' => (int) (clone $query)->sum('size_bytes'),
            'unknown_bytes' => (clone $query)->whereNull('size_bytes')->count(),
        ];
    }

    protected function collectAssetUrls(): void
    {
        MediaAsset::withoutGlobalScopes([SoftDeletingScope::class])
            ->select(['id', 'url'])
            ->orderBy('id')
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    $this->ingestString($row->url ?? '', 'media_assets#'.$row->id, 'column');
                }
            });
    }

    /**
     * @return array{tables: int, skipped: list<string>}
     */
    protected function scanTables(): array
    {
        $skip = config('media.schema_skip_tables', []);
        $scanned = 0;
        $skipped = [];

        foreach ($this->tables() as $table) {
            if ($table === 'media_assets' || in_array($table, $skip, true)) {
                $skipped[] = $table;

                continue;
            }

            $columns = $this->columns($table);

            if ($columns === []) {
                continue;
            }

            $names = array_column($columns, 'name');
            $urlColumns = [];
            $jsonColumns = [];
            $textColumns = [];

            foreach ($columns as $column) {
                $name = $column['name'];

                if ($name === 'id') {
                    continue;
                }

                $type = strtolower((string) ($column['type_name'] ?? $column['type'] ?? ''));

                if ($this->looksLikeMediaUrlColumn($name)) {
                    $urlColumns[] = $name;
                }

                if (in_array($type, ['json', 'jsonb'], true)
                    || (in_array($type, ['text', 'longtext', 'mediumtext'], true)
                        && ($name === 'payload' || str_contains($name, 'json')))) {
                    $jsonColumns[] = $name;
                } elseif (in_array($type, ['text', 'longtext', 'mediumtext', 'string', 'varchar', 'char'], true)) {
                    $textColumns[] = $name;
                }
            }

            // ingestString() also regex-scans for embedded URLs, so URL/JSON
            // columns never need the plain-text pass as well.
            $textColumns = array_values(array_diff($textColumns, $urlColumns, $jsonColumns));

            if ($urlColumns === [] && $jsonColumns === [] && $textColumns === []) {
                continue;
            }

            $scanned++;
            $chunkColumn = $this->chunkColumn($table, $columns);

            $labelColumns = [];
            if (in_array('group', $names, true) && in_array('name', $names, true)) {
                $labelColumns = ['group', 'name'];
            }

            $select = array_values(array_unique(array_merge(
                [$chunkColumn], $urlColumns, $jsonColumns, $textColumns, $labelColumns
            )));

            try {
                DB::table($table)->select($select)->orderBy($chunkColumn)->chunkById(200, function ($rows) use (
                    $table, $urlColumns, $jsonColumns, $textColumns, $labelColumns
                ) {
                    foreach ($rows as $row) {
                        $data = (array) $row;
                        $source = $table;

                        if ($labelColumns !== []) {
                            $source .= ':'.($data['group'] ?? '').'.'.($data['name'] ?? '');
                        }

                        foreach ($urlColumns as $column) {
                            $this->ingestString($data[$column] ?? '', $source.'.'.$column, 'column');
                        }

                        foreach ($textColumns as $column) {
                            $this->ingestString($data[$column] ?? '', $source.'.'.$column, 'column');
                        }

                        foreach ($jsonColumns as $column) {
                            $this->ingestJson($data[$column] ?? null, $source.'.'.$column);
                        }
                    }
                }, $chunkColumn);
            } catch (\Throwable $e) {
                Log::warning('[media-audit] Skipped table scan', [
                    'table' => $table,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return ['tables' => $scanned, 'skipped' => $skipped];
    }

    protected function ingestString(mixed $value, string $source, string $via): void
    {
        if (! is_string($value)) {
            return;
        }

        $raw = trim($value);

        if ($raw === '') {
            return;
        }

        // Protocol-relative URLs (//res.cloudinary.com/...) are rare in storage;
        // normalize so classification works.
        if (str_starts_with($raw, '//')) {
            $this->recordUrl('https:'.$raw, $source, $via);

            return;
        }

        if (preg_match_all('#https?://[^\s"\'<>]+#i', $raw, $matches) === false) {
            return;
        }

        foreach ($matches[0] as $found) {
            $found = rtrim($found, '.,;)]}');

            if ($found === '') {
                continue;
            }

            $this->recordUrl($found, $source, $found === $raw ? $via : 'embedded');
        }
    }

    protected function ingestJson(mixed $value, string $source, int $depth = 0): void
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
                    $this->ingestJson($decoded, $source, $depth + 1);

                    return;
                }
            }

            $this->ingestString($value, $source, 'json');

            return;
        }

        if (! is_array($value)) {
            return;
        }

        foreach ($value as $item) {
            $this->ingestJson($item, $source, $depth + 1);
        }
    }

    protected function recordUrl(string $url, string $source, string $via): void
    {
        $url = trim($url);

        if ($url === '' || isset($this->urls[$url])) {
            return;
        }

        $this->urls[$url] = ['url' => $url, 'source' => $source, 'via' => $via];
    }

    protected function cloudNameFromUrl(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH) ?? '';
        $segments = array_values(array_filter(explode('/', $path)));

        return $segments[0] ?? '(unknown)';
    }

    /**
     * Conservative check: a comma always means a transformation chain, and a
     * leading param segment (w_500, f_auto, ...) means a single transform.
     * A public_id folder that literally looks like a param is a false positive
     * by design — the audit flags it for human review.
     */
    protected function hasTransformation(string $url): bool
    {
        $path = (string) parse_url($url, PHP_URL_PATH);
        $pos = strpos($path, '/upload/');

        if ($pos === false) {
            return false;
        }

        foreach (array_filter(explode('/', substr($path, $pos + 8))) as $segment) {
            if (preg_match('/^v\d+$/', $segment)) {
                continue;
            }

            return $this->isTransformSegment($segment);
        }

        return false;
    }

    protected function isTransformSegment(string $segment): bool
    {
        if ($segment === '' || preg_match('/^v\d+$/', $segment)) {
            return false;
        }

        if (str_contains($segment, ',')) {
            return true;
        }

        return (bool) preg_match('/^(w|h|c|g|x|y|r|q|f|e|dpr|o|bo|b|a|t|fl|dl|l|u|pg|vs|du|so|eo|vc|ac|af|cs|d|fn|ki)_/i', $segment);
    }

    /**
     * Best-effort public_id that also understands stored transformation URLs.
     * CloudinaryService::extractPublicId() is intentionally left untouched.
     */
    protected function extractPublicIdSmart(string $url): ?string
    {
        $path = (string) parse_url($url, PHP_URL_PATH);
        $pos = strpos($path, '/upload/');

        if ($pos === false) {
            return null;
        }

        $parts = [];

        foreach (array_filter(explode('/', substr($path, $pos + 8))) as $segment) {
            if (preg_match('/^v\d+$/', $segment) || $this->isTransformSegment($segment)) {
                continue;
            }

            $parts[] = $segment;
        }

        if ($parts === []) {
            return null;
        }

        $last = array_pop($parts);
        $dot = strrpos($last, '.');

        if ($dot !== false) {
            $last = substr($last, 0, $dot);
        }

        $parts[] = $last;
        $publicId = implode('/', $parts);

        return $publicId !== '' ? $publicId : null;
    }

    /**
     * @return array{total: int, samples: list<array{hash: string, ids: list<int>, count: int}>}
     */
    protected function duplicateGroups(): array
    {
        $hashes = MediaAsset::query()
            ->select('hash')
            ->whereNotNull('hash')
            ->where('hash', '!=', '')
            ->groupBy('hash')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('hash');

        if ($hashes->isEmpty()) {
            return ['total' => 0, 'samples' => []];
        }

        $groups = [];

        MediaAsset::query()
            ->whereIn('hash', $hashes)
            ->orderBy('id')
            ->get(['id', 'hash'])
            ->groupBy('hash')
            ->each(function ($rows, $hash) use (&$groups) {
                $groups[] = [
                    'hash' => substr((string) $hash, 0, 12).'...',
                    'ids' => $rows->pluck('id')->all(),
                    'count' => $rows->count(),
                ];
            });

        usort($groups, static fn (array $a, array $b) => $b['count'] <=> $a['count']);

        return ['total' => count($groups), 'samples' => array_slice($groups, 0, self::SAMPLE_LIMIT)];
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
}
