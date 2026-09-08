<?php

namespace App\Services;

use App\Models\MediaAsset;
use App\Services\Concerns\ExtractsStoredUrls;
use App\Services\Concerns\ScansMediaTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Merge same-hash duplicate media_assets rows into one canonical row.
 *
 * Every reference to a duplicate — *_asset_id columns (including JSON
 * payloads), denormalized URL columns, rich-text <img> embeds — is repointed
 * to the canonical row, then the duplicate is soft-deleted. Dry-run by
 * default, idempotent, one transaction per duplicate.
 */
class MediaMergeService
{
    use ExtractsStoredUrls;
    use ScansMediaTables;

    public function __construct(
        protected CloudinaryService $cloudinary,
    ) {}

    /**
     * @return array{dry_run: bool, groups_total: int, groups_processed: int, merged: int, skipped: int, errors: list<string>, details: list<array{hash: string, canonical_id: int, dup_id: int, status: string, refs: array{fk: int, urls: int, transformed: int, json_fk: int, json_urls: int}}>}
     */
    public function merge(bool $dryRun = true, ?int $limit = null): array
    {
        $hashes = $this->duplicateHashes();
        $total = count($hashes);

        if ($limit !== null && $limit > 0) {
            $hashes = array_slice($hashes, 0, $limit);
        }

        $groups = $this->loadGroups($hashes);
        $details = [];
        $merged = 0;
        $skipped = 0;
        $errors = [];

        foreach ($groups as $group) {
            $canonical = $this->pickCanonical($group);

            foreach ($group as $dup) {
                if ($dup->id === $canonical->id) {
                    continue;
                }

                try {
                    if ($dryRun) {
                        $refs = $this->scanReferences($dup, $canonical, false);
                        $details[] = $this->detail($dup, $canonical, $refs, 'would-merge');
                    } else {
                        $refs = $this->emptyRefs();

                        DB::transaction(function () use ($dup, $canonical, &$refs) {
                            $refs = $this->scanReferences($dup, $canonical, true);
                            $dup->delete();
                        });

                        $details[] = $this->detail($dup, $canonical, $refs, 'merged');
                    }

                    $merged++;
                } catch (\Throwable $e) {
                    report($e);
                    $skipped++;
                    $errors[] = 'asset #'.$dup->id.': '.$e->getMessage();
                    $details[] = $this->detail($dup, $canonical, $this->emptyRefs(), 'skipped');
                }
            }
        }

        Log::info('[media-merge] Completed', [
            'dry_run' => $dryRun,
            'groups_total' => $total,
            'groups_processed' => count($groups),
            'merged' => $merged,
            'skipped' => $skipped,
            'errors' => count($errors),
        ]);

        return [
            'dry_run' => $dryRun,
            'groups_total' => $total,
            'groups_processed' => count($groups),
            'merged' => $merged,
            'skipped' => $skipped,
            'errors' => $errors,
            'details' => $details,
        ];
    }

    /**
     * @return list<string>
     */
    protected function duplicateHashes(): array
    {
        return MediaAsset::query()
            ->select('hash')
            ->whereNotNull('hash')
            ->where('hash', '!=', '')
            ->groupBy('hash')
            ->havingRaw('COUNT(*) > 1')
            ->orderBy('hash')
            ->pluck('hash')
            ->all();
    }

    /**
     * @param  list<string>  $hashes
     * @return list<\Illuminate\Support\Collection<int, MediaAsset>>
     */
    protected function loadGroups(array $hashes): array
    {
        if ($hashes === []) {
            return [];
        }

        return MediaAsset::query()
            ->whereIn('hash', $hashes)
            ->orderBy('hash')
            ->orderBy('id')
            ->get()
            ->groupBy('hash')
            ->values()
            ->all();
    }

    /**
     * @param  \Illuminate\Support\Collection<int, MediaAsset>  $group
     */
    protected function pickCanonical($group): MediaAsset
    {
        // (hash, disk_env) is UNIQUE, so groups always span disk_env values.
        // Prefer the current environment's row, then the lowest id.
        $target = $this->cloudinary->diskEnv();
        $sorted = $group->all();

        usort($sorted, static function (MediaAsset $a, MediaAsset $b) use ($target) {
            return [$a->disk_env === $target ? 0 : 1, $a->id]
                <=> [$b->disk_env === $target ? 0 : 1, $b->id];
        });

        return $sorted[0];
    }

    /**
     * Count (and optionally repoint) every reference to $dup.
     *
     * @return array{fk: int, urls: int, transformed: int, json_fk: int, json_urls: int}
     */
    protected function scanReferences(MediaAsset $dup, MediaAsset $canonical, bool $apply): array
    {
        $dupUrl = trim((string) $dup->url);
        $dupPid = trim((string) $dup->cloudinary_public_id);
        $canUrl = trim((string) $canonical->url);
        $canPid = trim((string) $canonical->cloudinary_public_id);

        if ($canUrl === '') {
            throw new \RuntimeException(
                "canonical asset #{$canonical->id} has a blank url — refusing to merge dup #{$dup->id}"
            );
        }

        $canExt = pathinfo((string) parse_url($canUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: null;

        $refs = $this->emptyRefs();
        $skip = config('media.schema_skip_tables', []);

        foreach ($this->tables() as $table) {
            if ($table === 'media_assets' || in_array($table, $skip, true)) {
                continue;
            }

            $columns = $this->columns($table);

            if ($columns === []) {
                continue;
            }

            $fkColumns = [];
            $urlColumns = [];
            $jsonColumns = [];
            $textColumns = [];

            foreach ($columns as $column) {
                $name = $column['name'];

                if ($name === 'id') {
                    continue;
                }

                if ($this->isAssetIdColumn($name)) {
                    $fkColumns[] = $name;

                    continue;
                }

                $type = strtolower((string) ($column['type_name'] ?? $column['type'] ?? ''));

                if ($this->looksLikeMediaUrlColumn($name)) {
                    $urlColumns[] = $name;
                }

                if ($this->isJsonColumn($name, $type)) {
                    $jsonColumns[] = $name;
                } elseif ($this->isTextColumnType($type)) {
                    $textColumns[] = $name;
                }
            }

            $textColumns = array_values(array_diff($textColumns, $urlColumns, $jsonColumns));

            if ($fkColumns === [] && $urlColumns === [] && $jsonColumns === [] && $textColumns === []) {
                continue;
            }

            $chunkColumn = $this->chunkColumn($table, $columns);
            $select = array_values(array_unique(array_merge(
                [$chunkColumn], $fkColumns, $urlColumns, $jsonColumns, $textColumns
            )));

            try {
                DB::table($table)->select($select)->orderBy($chunkColumn)->chunkById(200, function ($rows) use (
                    $table, $chunkColumn, $fkColumns, $urlColumns, $jsonColumns, $textColumns,
                    $dup, $canonical, $dupUrl, $dupPid, $canUrl, $canPid, $canExt, $apply, &$refs
                ) {
                    foreach ($rows as $row) {
                        $data = (array) $row;
                        $key = $data[$chunkColumn] ?? null;

                        foreach ($fkColumns as $column) {
                            if (! isset($data[$column]) || (int) $data[$column] !== $dup->id) {
                                continue;
                            }

                            $refs['fk']++;

                            if ($apply) {
                                DB::table($table)
                                    ->where($chunkColumn, $key)
                                    ->where($column, $dup->id)
                                    ->update([$column => $canonical->id]);
                            }
                        }

                        foreach (array_merge($urlColumns, $textColumns) as $column) {
                            $value = $data[$column] ?? '';

                            if (! is_string($value) || $value === '') {
                                continue;
                            }

                            [$new, $exact, $trans] = $this->repointUrlsInString(
                                $value, $dupUrl, $dupPid, $canUrl, $canPid, $canExt
                            );

                            if ($exact === 0 && $trans === 0) {
                                continue;
                            }

                            $refs['urls'] += $exact;
                            $refs['transformed'] += $trans;

                            if ($apply) {
                                DB::table($table)
                                    ->where($chunkColumn, $key)
                                    ->where($column, $value)
                                    ->update([$column => $new]);
                            }
                        }

                        foreach ($jsonColumns as $column) {
                            [$encoded, $changed, $fkCount, $urlCount, $transCount] = $this->repointJson(
                                $data[$column] ?? null, $dup->id, $canonical->id,
                                $dupUrl, $dupPid, $canUrl, $canPid, $canExt
                            );

                            if (! $changed) {
                                continue;
                            }

                            $refs['json_fk'] += $fkCount;
                            $refs['json_urls'] += $urlCount;
                            $refs['transformed'] += $transCount;

                            if ($apply && $encoded !== null) {
                                DB::table($table)
                                    ->where($chunkColumn, $key)
                                    ->where($column, $data[$column])
                                    ->update([$column => $encoded]);
                            }
                        }
                    }
                }, $chunkColumn);
            } catch (\Throwable $e) {
                Log::warning('[media-merge] Skipped table scan', [
                    'table' => $table,
                    'error' => $e->getMessage(),
                ]);

                // In apply mode a partial scan must abort this duplicate: the
                // surrounding transaction rolls everything back and the dup is
                // reported as skipped instead of half-merged.
                if ($apply) {
                    throw $e;
                }
            }
        }

        return $refs;
    }

    /**
     * Replace dup URLs inside a free-text value. Exact matches take the
     * canonical URL; stored transformation variants are rebuilt on the
     * canonical public_id with the same transform chain.
     *
     * @return array{0: string, 1: int, 2: int} [new string, exact replacements, transformed rebuilds]
     */
    protected function repointUrlsInString(string $text, string $dupUrl, string $dupPid, string $canUrl, string $canPid, ?string $canExt): array
    {
        $found = array_unique($this->extractUrlsFromString($text));

        if ($found === []) {
            return [$text, 0, 0];
        }

        // Longest first so a URL that extends another is replaced whole.
        usort($found, static fn (string $a, string $b) => strlen($b) <=> strlen($a));

        $exact = 0;
        $trans = 0;
        $new = $text;

        foreach ($found as $url) {
            if ($dupUrl !== '' && $url === $dupUrl) {
                $new = str_replace($url, $canUrl, $new);
                $exact++;

                continue;
            }

            if ($dupPid === '' || $canPid === '') {
                continue;
            }

            if ($this->extractPublicIdSmart($url) !== $dupPid) {
                continue;
            }

            $new = str_replace($url, $this->rebuildUrl(
                $this->cloudNameFromUrl($url),
                $this->transformSegments($url),
                $canPid,
                $canExt,
                $this->resourceTypeFromUrl($url)
            ), $new);
            $trans++;
        }

        return [$new, $exact, $trans];
    }

    /**
     * @return array{0: string|null, 1: bool, 2: int, 3: int, 4: int} [encoded, changed, jsonFk, jsonUrls, transformed]
     */
    protected function repointJson(mixed $value, int $dupId, int $canId, string $dupUrl, string $dupPid, string $canUrl, string $canPid, ?string $canExt): array
    {
        if (! is_string($value) || trim($value) === '') {
            return [null, false, 0, 0, 0];
        }

        $trimmed = trim($value);
        $looksJson = ($trimmed[0] ?? '') === '{' || ($trimmed[0] ?? '') === '[';
        $decoded = $looksJson ? json_decode($trimmed, true) : null;

        if (! is_array($decoded)) {
            // Not JSON (a payload column holding plain text) — treat as string.
            [$new, $exact, $trans] = $this->repointUrlsInString(
                $value, $dupUrl, $dupPid, $canUrl, $canPid, $canExt
            );

            return [$new !== $value ? $new : null, $new !== $value, 0, $exact, $trans];
        }

        $ctx = [
            'dupId' => $dupId, 'canId' => $canId,
            'dupUrl' => $dupUrl, 'dupPid' => $dupPid,
            'canUrl' => $canUrl, 'canPid' => $canPid, 'canExt' => $canExt,
        ];
        $counts = ['fk' => 0, 'urls' => 0, 'trans' => 0];
        $replaced = $this->walkReplace($decoded, $ctx, $counts);

        if ($counts['fk'] === 0 && $counts['urls'] === 0 && $counts['trans'] === 0) {
            return [null, false, 0, 0, 0];
        }

        return [
            json_encode($replaced, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            true,
            $counts['fk'],
            $counts['urls'],
            $counts['trans'],
        ];
    }

    /**
     * @param  array{fk: int, urls: int, trans: int}  $counts
     */
    protected function walkReplace(mixed $node, array $ctx, array &$counts, int $depth = 0): mixed
    {
        if ($depth > 20) {
            return $node;
        }

        if (is_array($node)) {
            $out = [];

            foreach ($node as $key => $item) {
                if (is_string($key) && $this->isAssetIdColumn($key)
                    && is_numeric($item) && (int) $item === $ctx['dupId']) {
                    $out[$key] = $ctx['canId'];
                    $counts['fk']++;

                    continue;
                }

                $out[$key] = $this->walkReplace($item, $ctx, $counts, $depth + 1);
            }

            return $out;
        }

        if (is_string($node)) {
            [$new, $exact, $trans] = $this->repointUrlsInString(
                $node, $ctx['dupUrl'], $ctx['dupPid'], $ctx['canUrl'], $ctx['canPid'], $ctx['canExt']
            );
            $counts['urls'] += $exact;
            $counts['trans'] += $trans;

            return $new;
        }

        return $node;
    }

    /**
     * @return array{fk: int, urls: int, transformed: int, json_fk: int, json_urls: int}
     */
    protected function emptyRefs(): array
    {
        return ['fk' => 0, 'urls' => 0, 'transformed' => 0, 'json_fk' => 0, 'json_urls' => 0];
    }

    /**
     * @param  array{fk: int, urls: int, transformed: int, json_fk: int, json_urls: int}  $refs
     * @return array{hash: string, canonical_id: int, dup_id: int, status: string, refs: array{fk: int, urls: int, transformed: int, json_fk: int, json_urls: int}}
     */
    protected function detail(MediaAsset $dup, MediaAsset $canonical, array $refs, string $status): array
    {
        return [
            'hash' => substr((string) $dup->hash, 0, 12).'...',
            'canonical_id' => $canonical->id,
            'dup_id' => $dup->id,
            'status' => $status,
            'refs' => $refs,
        ];
    }
}
