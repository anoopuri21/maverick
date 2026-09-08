<?php

namespace App\Services;

use App\Models\MediaAsset;
use App\Models\MediaMigrationMap;
use App\Services\Concerns\ExtractsStoredUrls;
use App\Services\Concerns\ScansMediaTables;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Phase 2 of the Cloudinary account migration: point every stored URL at
 * the NEW account using ONLY verified mapping rows (never blind swaps).
 *
 * Update order (plan §4.2): media_assets urls (+ pid/folder for R2 rows)
 * → denormalized Eloquent columns via *_asset_id linkage → settings JSON
 * (linkage first, legacy-direct urls via public_id mapping). Anything
 * without a verified mapping is SKIPPED + reported. Transformed variants
 * are rebuilt on the new pid with the same transform chain. Externals,
 * foreign-cloud urls and videos are never touched. Dry-run by default,
 * idempotent (crash-safe via rerun), and every live run ends with a
 * source-cloud residue scan (§4.3: image residue must be 0).
 */
class MediaCutoverService
{
    use ExtractsStoredUrls;
    use ScansMediaTables;

    protected const SAMPLE_LIMIT = 10;

    public function __construct(
        protected CloudinaryService $cloudinary,
    ) {}

    /** @var array<string, mixed> */
    protected array $run = [];

    /** @var array<string, array{new_pid: string, new_url: string, status: string}> old_pid => usable mapping */
    protected array $goodMaps = [];

    /** @var array<string, array{status: string, reason: string|null}> old_pid => any mapping */
    protected array $allMaps = [];

    /** @var array<string, string> new_pid => old_pid (migrated rows, joins already-cut R2 rows) */
    protected array $newToOld = [];

    /** @var array<int, string> asset id => old pid */
    protected array $assetPid = [];

    protected bool $touched = false;

    /**
     * @return array{dry_run: bool, assets: array{scanned: int, updated: int, current: int, skipped: int, reasons: array<string, int>, samples: list<array{ref: string, reason: string}>}, fields: array{rows_touched: int, cells_updated: int, current: int, skipped: int, repaired: int, filled: int, reasons: array<string, int>, samples: list<array{ref: string, reason: string}>}, settings: array{rows: int, rows_updated: int, urls_updated: int, current: int, skipped: int, repaired: int, reasons: array<string, int>, samples: list<array{ref: string, reason: string}>}, residue: null|array{image_total: int, image_samples: list<array{url: string, source: string}>, video_total: int, video_samples: list<array{url: string, source: string}>}}
     */
    public function cutover(bool $dryRun = true): array
    {
        $this->guard();
        $this->resetRun($dryRun);
        $this->loadMaps();

        $this->cutoverAssets();
        $this->cutoverFields();
        $this->cutoverSettings();

        $residue = $dryRun ? null : $this->scanResidue();

        Log::info('[media-cutover] Completed', [
            'dry_run' => $dryRun,
            'assets_updated' => $this->run['assets']['updated'],
            'fields_updated' => $this->run['fields']['cells_updated'],
            'settings_updated' => $this->run['settings']['urls_updated'],
            'image_residue' => $residue['image_total'] ?? null,
        ]);

        return [
            'dry_run' => $dryRun,
            'assets' => $this->run['assets'],
            'fields' => $this->run['fields'],
            'settings' => $this->run['settings'],
            'residue' => $residue,
        ];
    }

    /**
     * @throws \RuntimeException
     */
    protected function guard(): void
    {
        // [R1] Cutover assumes a shared-mode migration; env-folder mode
        // would have hard-failed the migrate step already.
        if ($this->cloudinary->usesEnvFolder()) {
            throw new \RuntimeException(
                '[R1 guard] CLOUDINARY_ENV_FOLDER is enabled — refusing cutover. '.
                'The migration requires one shared folder (set CLOUDINARY_ENV_FOLDER=false).'
            );
        }

        if (! filled($this->cloudinary->sourceCloudName())) {
            throw new \RuntimeException(
                'Source cloud name is missing — set CLOUDINARY_SOURCE_CLOUD_NAME to the OLD account.'
            );
        }
    }

    protected function resetRun(bool $dryRun): void
    {
        $this->run = [
            'dry' => $dryRun,
            'assets' => ['scanned' => 0, 'updated' => 0, 'current' => 0, 'skipped' => 0, 'reasons' => [], 'samples' => []],
            'fields' => ['rows_touched' => 0, 'cells_updated' => 0, 'current' => 0, 'skipped' => 0, 'repaired' => 0, 'filled' => 0, 'reasons' => [], 'samples' => []],
            'settings' => ['rows' => 0, 'rows_updated' => 0, 'urls_updated' => 0, 'current' => 0, 'skipped' => 0, 'repaired' => 0, 'reasons' => [], 'samples' => []],
        ];
    }

    protected function loadMaps(): void
    {
        $this->goodMaps = [];
        $this->allMaps = [];
        $this->newToOld = [];
        $this->assetPid = [];

        foreach (MediaMigrationMap::query()->get(['old_public_id', 'new_public_id', 'new_url', 'status', 'reason']) as $map) {
            $this->allMaps[$map->old_public_id] = ['status' => $map->status, 'reason' => $map->reason];

            if (in_array($map->status, ['migrated', 'shared'], true)
                && filled($map->new_public_id) && filled($map->new_url)) {
                $this->goodMaps[$map->old_public_id] = [
                    'new_pid' => $map->new_public_id,
                    'new_url' => $map->new_url,
                    'status' => $map->status,
                ];

                if ($map->status === 'migrated') {
                    $this->newToOld[$map->new_public_id] = $map->old_public_id;
                }
            }
        }

        MediaAsset::withoutGlobalScopes([SoftDeletingScope::class])
            ->select(['id', 'cloudinary_public_id', 'url'])
            ->orderBy('id')
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    $pid = filled($row->cloudinary_public_id)
                        ? trim((string) $row->cloudinary_public_id)
                        : ($this->extractPublicIdSmart((string) ($row->url ?? '')) ?? '');

                    if ($pid !== '') {
                        $this->assetPid[(int) $row->id] = $pid;
                    }
                }
            });
    }

    // ------------------------------------------------------------------
    // Phase A: media_assets rows.
    // ------------------------------------------------------------------

    protected function cutoverAssets(): void
    {
        MediaAsset::withoutGlobalScopes([SoftDeletingScope::class])
            ->orderBy('id')
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    $this->run['assets']['scanned']++;

                    $pid = filled($row->cloudinary_public_id)
                        ? trim((string) $row->cloudinary_public_id)
                        : ($this->extractPublicIdSmart((string) ($row->url ?? '')) ?? '');

                    if ($pid === '') {
                        $this->recordSkip('assets', 'no-identity', 'media_assets#'.$row->id);

                        continue;
                    }

                    // Already-cut R2 rows carry the NEW pid — join them back to
                    // their mapping so reruns report current, not unmapped.
                    $map = $this->goodMaps[$pid]
                        ?? (isset($this->newToOld[$pid]) ? ($this->goodMaps[$this->newToOld[$pid]] ?? null) : null);

                    if ($map === null) {
                        $this->recordSkip('assets', $this->mapReason($pid), 'media_assets#'.$row->id);

                        continue;
                    }

                    $updates = [];

                    if (trim((string) ($row->url ?? '')) !== $map['new_url']) {
                        $updates['url'] = $map['new_url'];
                    }

                    // [R2] Truly re-homed rows (migrated with a changed pid)
                    // also move pid + folder. SHARED rows keep their own pid:
                    // pointing them at the canonical pid would violate the
                    // UNIQUE(pid) constraint — the url is what matters.
                    if ($map['status'] === 'migrated' && $map['new_pid'] !== $pid) {
                        if ((string) $row->cloudinary_public_id !== $map['new_pid']) {
                            $updates['cloudinary_public_id'] = $map['new_pid'];
                        }

                        $folder = $this->cloudinary->folderFromPublicId($map['new_pid']);

                        if ((string) ($row->folder ?? '') !== $folder) {
                            $updates['folder'] = $folder;
                        }
                    }

                    if ($updates === []) {
                        $this->run['assets']['current']++;

                        continue;
                    }

                    if (! $this->run['dry']) {
                        MediaAsset::withoutGlobalScopes([SoftDeletingScope::class])
                            ->where('id', $row->id)
                            ->update($updates);
                    }

                    $this->run['assets']['updated']++;
                }
            });
    }

    protected function mapReason(string $pid): string
    {
        $map = $this->allMaps[$pid] ?? null;

        if ($map === null) {
            return 'unmapped';
        }

        if ($map['status'] === 'failed') {
            return 'mapping-failed';
        }

        if ($map['status'] === 'skipped') {
            return 'mapping-skipped:'.($map['reason'] ?? 'unknown');
        }

        return 'mapping-no-url';
    }

    // ------------------------------------------------------------------
    // Phase B: denormalized Eloquent columns (settings has its own pass).
    // ------------------------------------------------------------------

    protected function cutoverFields(): void
    {
        $skip = config('media.schema_skip_tables', []);

        foreach ($this->tables() as $table) {
            $base = $this->baseTable($table);

            if ($base === 'media_assets' || $base === 'settings' || in_array($base, $skip, true)) {
                continue;
            }

            $columns = $this->columns($table);

            if ($columns === []) {
                continue;
            }

            $idCols = [];
            $urlCols = [];
            $jsonCols = [];
            $textCols = [];

            foreach ($columns as $column) {
                $name = $column['name'];

                if ($name === 'id') {
                    continue;
                }

                if ($this->isAssetIdColumn($name)) {
                    $idCols[] = $name;

                    continue;
                }

                $type = strtolower((string) ($column['type_name'] ?? $column['type'] ?? ''));

                if ($this->looksLikeMediaUrlColumn($name)) {
                    $urlCols[] = $name;
                }

                if ($this->isJsonColumn($name, $type)) {
                    $jsonCols[] = $name;
                } elseif ($this->isTextColumnType($type)) {
                    $textCols[] = $name;
                }
            }

            $textCols = array_values(array_diff($textCols, $urlCols, $jsonCols));

            if ($idCols === [] && $urlCols === [] && $jsonCols === [] && $textCols === []) {
                continue;
            }

            // Linkage pairing: url col U is driven by U_asset_id. When a
            // table has no prefixed pairs but exactly one id column (bare
            // media_asset_id), it drives every url column.
            $pairs = [];

            foreach ($urlCols as $urlCol) {
                if (in_array($urlCol.'_asset_id', $idCols, true)) {
                    $pairs[$urlCol] = $urlCol.'_asset_id';
                }
            }

            $single = ($pairs === [] && count($idCols) === 1) ? $idCols[0] : null;

            $chunkColumn = $this->chunkColumn($table, $columns);
            $select = array_values(array_unique(array_merge(
                [$chunkColumn], $idCols, $urlCols, $jsonCols, $textCols
            )));

            try {
                DB::table($table)->select($select)->orderBy($chunkColumn)->chunkById(200, function ($rows) use (
                    $table, $base, $chunkColumn, $idCols, $urlCols, $jsonCols, $textCols, $pairs, $single
                ) {
                    foreach ($rows as $row) {
                        $data = (array) $row;
                        $key = $data[$chunkColumn] ?? null;
                        $this->touched = false;

                        foreach ($urlCols as $urlCol) {
                            $idCol = $pairs[$urlCol] ?? $single;

                            $this->cutoverUrlCell(
                                $table, $chunkColumn, $key, $urlCol,
                                $data[$urlCol] ?? null,
                                $idCol !== null ? ($data[$idCol] ?? null) : null,
                                $base.'.'.$urlCol.'#'.$key
                            );
                        }

                        foreach ($textCols as $column) {
                            $value = $data[$column] ?? null;

                            if (! is_string($value) || $value === '' || ! str_contains($value, 'http')) {
                                continue;
                            }

                            [$new, $changed] = $this->repointTextUrls($value, 'fields');

                            if (! $changed) {
                                continue;
                            }

                            if (! $this->run['dry']) {
                                DB::table($table)
                                    ->where($chunkColumn, $key)
                                    ->where($column, $value)
                                    ->update([$column => $new]);
                            }

                            $this->touched = true;
                        }

                        foreach ($jsonCols as $column) {
                            [$new, $changed] = $this->cutoverJsonColumnValue($data[$column] ?? null, 'fields');

                            if (! $changed) {
                                continue;
                            }

                            if (! $this->run['dry']) {
                                DB::table($table)
                                    ->where($chunkColumn, $key)
                                    ->where($column, $data[$column])
                                    ->update([$column => $new]);
                            }

                            $this->touched = true;
                        }

                        if ($this->touched) {
                            $this->run['fields']['rows_touched']++;
                        }
                    }
                }, $chunkColumn);
            } catch (\Throwable $e) {
                Log::warning('[media-cutover] Skipped table scan', [
                    'table' => $table,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    protected function cutoverUrlCell(string $table, string $chunkCol, mixed $key, string $column, mixed $value, mixed $idVal, string $ref): void
    {
        $decision = $this->linkedNewUrl(is_string($value) ? $value : '', $idVal);

        switch ($decision['action']) {
            case 'current':
                $this->run['fields']['current']++;

                return;
            case 'ignore':
                return;
            case 'external':
                $this->recordSkip('fields', 'external-preserved', $ref);

                return;
            case 'foreign':
                $this->recordSkip('fields', 'foreign-preserved', $ref);

                return;
            case 'unmapped':
                $this->recordSkip('fields', $decision['detail'] ?? 'unmapped-url', $ref);

                return;
            case 'fill':
                $this->run['fields']['filled']++;

                break;
            case 'update':
                if (! empty($decision['mismatch'])) {
                    $this->run['fields']['repaired']++;
                }

                break;
            default:
                return;
        }

        if (! $this->run['dry']) {
            DB::table($table)
                ->where($chunkCol, $key)
                ->where($column, $value)
                ->update([$column => $decision['url']]);
        }

        $this->run['fields']['cells_updated']++;
        $this->touched = true;
    }

    // ------------------------------------------------------------------
    // Phase C: settings JSON payloads.
    // ------------------------------------------------------------------

    protected function cutoverSettings(): void
    {
        DB::table('settings')->orderBy('id')->chunkById(200, function ($rows) {
            foreach ($rows as $row) {
                $this->run['settings']['rows']++;

                [$new, $changed] = $this->cutoverJsonColumnValue($row->payload ?? null, 'settings');

                if (! $changed) {
                    continue;
                }

                if (! $this->run['dry']) {
                    DB::table('settings')
                        ->where('id', $row->id)
                        ->where('payload', $row->payload)
                        ->update(['payload' => $new, 'updated_at' => now()]);
                }

                $this->run['settings']['rows_updated']++;
            }
        });
    }

    /**
     * @return array{0: mixed, 1: bool} [new raw value, changed]
     */
    protected function cutoverJsonColumnValue(mixed $raw, string $bucket): array
    {
        if (! is_string($raw) || trim($raw) === '') {
            return [$raw, false];
        }

        $trimmed = trim($raw);

        if (($trimmed[0] ?? '') === '{' || ($trimmed[0] ?? '') === '[') {
            $decoded = json_decode($trimmed, true);

            if (is_array($decoded)) {
                [$node, $changed] = $this->cutoverJsonNode($decoded, $bucket);

                if (! $changed) {
                    return [$raw, false];
                }

                return [json_encode($node, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), true];
            }
        }

        return $this->repointTextUrls($raw, $bucket);
    }

    /**
     * @return array{0: mixed, 1: bool} [new node, changed]
     */
    protected function cutoverJsonNode(mixed $node, string $bucket): array
    {
        if (is_string($node)) {
            return $this->cutoverJsonString($node, null, $bucket);
        }

        if (! is_array($node)) {
            return [$node, false];
        }

        if (array_is_list($node)) {
            $changed = false;
            $out = [];

            foreach ($node as $item) {
                [$new, $itemChanged] = $this->cutoverJsonNode($item, $bucket);
                $out[] = $new;
                $changed = $changed || $itemChanged;
            }

            return [$out, $changed];
        }

        // Object rules: explicit K ↔ K_asset_id pairs win; only when an
        // object has no pairs at all does a lone *_asset_id drive every
        // URL sibling (repeater items); the rest resolve pid-direct.
        $ids = [];

        foreach ($node as $key => $value) {
            if (is_string($key) && $this->isAssetIdColumn($key) && is_numeric($value) && (int) $value > 0) {
                $ids[$key] = (int) $value;
            }
        }

        $pairs = [];

        foreach ($node as $key => $value) {
            if (is_string($key) && is_string($value) && isset($ids[$key.'_asset_id'])) {
                $pairs[$key] = $ids[$key.'_asset_id'];
            }
        }

        $fallback = ($pairs === [] && count($ids) === 1) ? reset($ids) : null;

        $changed = false;
        $out = [];

        foreach ($node as $key => $value) {
            if (! is_string($value)) {
                [$new, $itemChanged] = $this->cutoverJsonNode($value, $bucket);
                $out[$key] = $new;
                $changed = $changed || $itemChanged;

                continue;
            }

            [$new, $itemChanged] = $this->cutoverJsonString(
                $value, (is_string($key) && isset($pairs[$key])) ? $pairs[$key] : $fallback, $bucket
            );
            $out[$key] = $new;
            $changed = $changed || $itemChanged;
        }

        return [$out, $changed];
    }

    /**
     * Free-text strings stay silent unless they hold mappable source-cloud
     * urls — plain text and externals must not pollute the report.
     *
     * @return array{0: string, 1: bool} [new string, changed]
     */
    protected function cutoverJsonString(string $value, mixed $idVal, string $bucket): array
    {
        if (! str_contains($value, 'http')) {
            return [$value, false];
        }

        $found = $this->extractUrlsFromString($value);

        if ($found === []) {
            return [$value, false];
        }

        if (count(array_unique($found)) === 1 && trim($value) === $found[0]
            && is_numeric($idVal) && (int) $idVal > 0) {
            $decision = $this->linkedNewUrl($value, $idVal);

            switch ($decision['action']) {
                case 'current':
                    $this->run[$bucket]['current']++;

                    return [$value, false];
                case 'update':
                case 'fill':
                    if (! empty($decision['mismatch'])) {
                        $this->run[$bucket]['repaired']++;
                    }

                    $this->countUrlUpdated($bucket);

                    return [$decision['url'], true];
                case 'unmapped':
                    $this->recordSkip($bucket, $decision['detail'] ?? 'unmapped-url', substr($value, 0, 80));

                    return [$value, false];
                default:
                    return [$value, false];
            }
        }

        return $this->repointTextUrls($value, $bucket);
    }

    // ------------------------------------------------------------------
    // Shared URL decision engine (pure) + text repointing.
    // ------------------------------------------------------------------

    /**
     * Decide the fate of one URL cell with optional asset linkage.
     * Linkage wins over pid match; externals/foreign clouds are preserved.
     *
     * @return array{action: string, url?: string, mismatch?: bool, detail?: string}
     */
    protected function linkedNewUrl(string $cell, mixed $idVal): array
    {
        $assetId = is_numeric($idVal) && (int) $idVal > 0 ? (int) $idVal : null;
        $assetPid = $assetId !== null ? ($this->assetPid[$assetId] ?? null) : null;
        $map = $assetPid !== null ? ($this->goodMaps[$assetPid] ?? null) : null;

        if (trim($cell) === '') {
            if ($map !== null) {
                return ['action' => 'fill', 'url' => $map['new_url']];
            }

            return ['action' => 'ignore'];
        }

        $host = strtolower((string) parse_url($cell, PHP_URL_HOST));

        if (! str_contains($host, 'cloudinary.com')) {
            return ['action' => 'external'];
        }

        if ($map !== null && $cell === $map['new_url']) {
            return ['action' => 'current'];
        }

        $source = (string) $this->cloudinary->sourceCloudName();

        if ($this->cloudNameFromUrl($cell) !== $source) {
            // A new-world cell pointing at the wrong file: the linkage is
            // authoritative. Truly foreign clouds are never touched.
            if ($map !== null && $this->cloudNameFromUrl($cell) === $this->cloudNameFromUrl($map['new_url'])) {
                return ['action' => 'update', 'url' => $map['new_url'], 'mismatch' => true];
            }

            return ['action' => 'foreign'];
        }

        if ($map !== null) {
            $base = $this->extractPublicIdSmart($cell);

            if ($base !== null && $base === $assetPid && $this->hasTransformation($cell)) {
                return ['action' => 'update', 'url' => $this->rebuildOnMapping($cell, $map)];
            }

            return [
                'action' => 'update',
                'url' => $map['new_url'],
                'mismatch' => $base !== null && $base !== $assetPid,
            ];
        }

        $repointed = $this->pidDirectUrl($cell);

        if ($repointed !== null) {
            return ['action' => 'update', 'url' => $repointed];
        }

        if ($assetId !== null && $assetPid === null) {
            return ['action' => 'unmapped', 'detail' => 'unknown-asset'];
        }

        if ($assetId !== null) {
            return ['action' => 'unmapped', 'detail' => 'unmapped-asset'];
        }

        return ['action' => 'unmapped', 'detail' => 'unmapped-url'];
    }

    /**
     * Resolve a source-cloud URL purely by its base public_id (transform
     * preserved). Null when unmappable.
     */
    protected function pidDirectUrl(string $url): ?string
    {
        if (! $this->isSourceCloudUrl($url)) {
            return null;
        }

        $base = $this->extractPublicIdSmart($url);

        if ($base === null) {
            return null;
        }

        $map = $this->goodMaps[$base] ?? null;

        if ($map === null) {
            return null;
        }

        if ($this->hasTransformation($url)) {
            return $this->rebuildOnMapping($url, $map);
        }

        return $map['new_url'];
    }

    /**
     * @param  array{new_pid: string, new_url: string, status: string}  $map
     */
    protected function rebuildOnMapping(string $oldUrl, array $map): string
    {
        return $this->rebuildUrl(
            $this->cloudNameFromUrl($map['new_url']),
            $this->transformSegments($oldUrl),
            $map['new_pid'],
            pathinfo((string) parse_url($oldUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: null,
            $this->resourceTypeFromUrl($oldUrl)
        );
    }

    protected function isSourceCloudUrl(string $url): bool
    {
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));

        return str_contains($host, 'cloudinary.com')
            && $this->cloudNameFromUrl($url) === (string) $this->cloudinary->sourceCloudName();
    }

    /**
     * Repoint every source-cloud URL inside free text via pid mapping.
     * Non-source urls pass through silently.
     *
     * @return array{0: string, 1: bool} [new text, changed]
     */
    protected function repointTextUrls(string $text, string $bucket): array
    {
        $found = array_unique($this->extractUrlsFromString($text));

        if ($found === []) {
            return [$text, false];
        }

        // Longest first so a URL that extends another is replaced whole.
        usort($found, static fn (string $a, string $b) => strlen($b) <=> strlen($a));

        $new = $text;
        $changed = false;

        foreach ($found as $url) {
            if (! $this->isSourceCloudUrl($url)) {
                continue;
            }

            $repointed = $this->pidDirectUrl($url);

            if ($repointed === null) {
                $this->recordSkip($bucket, 'unmapped-url', substr($url, 0, 80));

                continue;
            }

            if ($repointed === $url) {
                $this->run[$bucket]['current']++;

                continue;
            }

            $new = str_replace($url, $repointed, $new);
            $changed = true;
            $this->countUrlUpdated($bucket);
        }

        return [$new, $changed];
    }

    protected function countUrlUpdated(string $bucket): void
    {
        if ($bucket === 'settings') {
            $this->run['settings']['urls_updated']++;
        } else {
            $this->run['fields']['cells_updated']++;
        }
    }

    // ------------------------------------------------------------------
    // Phase D: source-cloud residue scan (live runs only, §4.3).
    // ------------------------------------------------------------------

    /**
     * @return array{image_total: int, image_samples: list<array{url: string, source: string}>, video_total: int, video_samples: list<array{url: string, source: string}>}
     */
    protected function scanResidue(): array
    {
        $images = [];
        $videos = [];

        $add = function (string $url, string $source) use (&$images, &$videos): void {
            if (! $this->isSourceCloudUrl($url)) {
                return;
            }

            if ($this->resourceTypeFromUrl($url) === 'video') {
                $videos[$url] ??= $source;
            } else {
                $images[$url] ??= $source;
            }
        };

        MediaAsset::withoutGlobalScopes([SoftDeletingScope::class])
            ->select(['id', 'url'])
            ->orderBy('id')
            ->chunkById(200, function ($rows) use ($add) {
                foreach ($rows as $row) {
                    foreach ($this->extractUrlsFromString((string) ($row->url ?? '')) as $url) {
                        $add($url, 'media_assets#'.$row->id);
                    }
                }
            });

        $skip = config('media.schema_skip_tables', []);

        foreach ($this->tables() as $table) {
            $base = $this->baseTable($table);

            if ($base === 'media_assets' || in_array($base, $skip, true)) {
                continue;
            }

            $columns = $this->columns($table);

            if ($columns === []) {
                continue;
            }

            $names = array_column($columns, 'name');
            $urlCols = [];
            $jsonCols = [];
            $textCols = [];

            foreach ($columns as $column) {
                $name = $column['name'];

                if ($name === 'id') {
                    continue;
                }

                $type = strtolower((string) ($column['type_name'] ?? $column['type'] ?? ''));

                if ($this->looksLikeMediaUrlColumn($name)) {
                    $urlCols[] = $name;
                }

                if ($this->isJsonColumn($name, $type)) {
                    $jsonCols[] = $name;
                } elseif ($this->isTextColumnType($type)) {
                    $textCols[] = $name;
                }
            }

            $textCols = array_values(array_diff($textCols, $urlCols, $jsonCols));

            if ($urlCols === [] && $jsonCols === [] && $textCols === []) {
                continue;
            }

            $labelCols = [];

            if (in_array('group', $names, true) && in_array('name', $names, true)) {
                $labelCols = ['group', 'name'];
            }

            $chunkColumn = $this->chunkColumn($table, $columns);
            $select = array_values(array_unique(array_merge(
                [$chunkColumn], $urlCols, $jsonCols, $textCols, $labelCols
            )));

            try {
                DB::table($table)->select($select)->orderBy($chunkColumn)->chunkById(200, function ($rows) use (
                    $base, $urlCols, $jsonCols, $textCols, $labelCols, $add
                ) {
                    foreach ($rows as $row) {
                        $data = (array) $row;
                        $source = $base;

                        if ($labelCols !== []) {
                            $source .= ':'.($data['group'] ?? '').'.'.($data['name'] ?? '');
                        }

                        foreach (array_merge($urlCols, $textCols) as $column) {
                            $value = $data[$column] ?? null;

                            if (! is_string($value) || $value === '') {
                                continue;
                            }

                            foreach ($this->extractUrlsFromString($value) as $url) {
                                $add($url, $source.'.'.$column);
                            }
                        }

                        foreach ($jsonCols as $column) {
                            foreach ($this->residueJsonStrings($data[$column] ?? null) as $string) {
                                foreach ($this->extractUrlsFromString($string) as $url) {
                                    $add($url, $source.'.'.$column);
                                }
                            }
                        }
                    }
                }, $chunkColumn);
            } catch (\Throwable $e) {
                Log::warning('[media-cutover] Residue scan skipped table', [
                    'table' => $table,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return [
            'image_total' => count($images),
            'image_samples' => $this->residueSamples($images),
            'video_total' => count($videos),
            'video_samples' => $this->residueSamples($videos),
        ];
    }

    /**
     * @return list<string>
     */
    protected function residueJsonStrings(mixed $value, int $depth = 0): array
    {
        if ($depth > 20) {
            return [];
        }

        if (is_string($value)) {
            $trimmed = trim($value);

            if ($trimmed === '') {
                return [];
            }

            if (($trimmed[0] ?? '') === '{' || ($trimmed[0] ?? '') === '[') {
                $decoded = json_decode($trimmed, true);

                if (is_array($decoded)) {
                    return $this->residueJsonStrings($decoded, $depth + 1);
                }
            }

            return [$value];
        }

        if (! is_array($value)) {
            return [];
        }

        $out = [];

        foreach ($value as $item) {
            array_push($out, ...$this->residueJsonStrings($item, $depth + 1));
        }

        return $out;
    }

    /**
     * @param  array<string, string>  $bucket
     * @return list<array{url: string, source: string}>
     */
    protected function residueSamples(array $bucket): array
    {
        $samples = [];

        foreach ($bucket as $url => $source) {
            $samples[] = ['url' => $url, 'source' => $source];

            if (count($samples) >= self::SAMPLE_LIMIT) {
                break;
            }
        }

        return $samples;
    }

    protected function recordSkip(string $bucket, string $reason, string $ref): void
    {
        $this->run[$bucket]['skipped']++;
        $this->run[$bucket]['reasons'][$reason] = ($this->run[$bucket]['reasons'][$reason] ?? 0) + 1;

        if (count($this->run[$bucket]['samples']) < self::SAMPLE_LIMIT) {
            $this->run[$bucket]['samples'][] = ['ref' => substr($ref, 0, 120), 'reason' => $reason];
        }
    }
}
