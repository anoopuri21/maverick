<?php

namespace App\Services;

use App\Models\MediaAsset;
use App\Models\MediaMigrationMap;
use App\Services\Concerns\ExtractsStoredUrls;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Phase 1 of the Cloudinary account migration: copy files from the old
 * account to the new account (Cloudinary-to-Cloudinary fetch-upload) and
 * record the authoritative old → new mapping in media_migration_map.
 *
 * Phase 1 NEVER touches media_assets/settings rows or the source account —
 * it only uploads to DEST and writes mapping rows. Resumable (re-run until
 * 0 remaining), idempotent, --limit batches for shared hosting.
 *
 * Rules (plan §3 + §13): [R1] env-folder mode hard-fails, [R2] legacy
 * "-suffix" public_ids normalize to the shared path (collision = skip +
 * review), [R3] same-hash groups upload once (canonical) and share.
 */
class MediaMigrationService
{
    use ExtractsStoredUrls;

    protected const SAMPLE_LIMIT = 50;

    public function __construct(
        protected CloudinaryService $cloudinary,
    ) {}

    /** @var array<string, mixed> */
    protected array $run = [];

    /** @var array<string, string> old_public_id => status */
    protected array $mapped = [];

    /** @var array<string, int> old_public_id => attempts (failed rows) */
    protected array $failedAttempts = [];

    /** @var array<string, string> new_public_id => claiming old_public_id (DB + this run) */
    protected array $claimedNew = [];

    /** @var array<string, int> hash => canonical asset id */
    protected array $canonicalId = [];

    /** @var array<string, string> hash => canonical old public_id */
    protected array $canonicalPid = [];

    /** @var array<string, bool> hash => canonical row is migration-eligible */
    protected array $canonicalOk = [];

    /** @var array<string, string> canonical old_public_id => mapping status */
    protected array $canonicalMapping = [];

    /** @var array<string, true> settings pids already seen this run */
    protected array $seenSettings = [];

    /**
     * @return array{dry_run: bool, limit: int|null, processed: int, migrated: int, shared: int, skipped: int, failed: int, deferred: int, already: int, remaining: int|null, skip_reasons: array<string, int>, failures: list<array{pid: string, error: string}>, samples: list<array{pid: string, new: string|null, status: string, source: string}>, dest_checks: string}
     */
    public function migrate(bool $dryRun = false, ?int $limit = null): array
    {
        // Dry-run makes zero DEST calls, so DEST creds are not required for it.
        $this->guard(! $dryRun);
        $this->resetRun($dryRun, $limit);
        $this->loadMappingState();
        $this->loadCanonicalState();

        // Assets first (withTrashed: scope is ALL, restores must keep working).
        MediaAsset::withoutGlobalScopes([SoftDeletingScope::class])
            ->orderBy('id')
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    if ($this->budgetExhausted()) {
                        $this->run['stopped_early'] = true;

                        return false;
                    }

                    $this->processAsset($row);
                }

                return true;
            });

        // Legacy settings URLs (no media_assets row) with remaining budget.
        if (! $this->budgetExhausted()) {
            DB::table('settings')->orderBy('id')->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    if ($this->budgetExhausted()) {
                        $this->run['stopped_early'] = true;

                        return false;
                    }

                    $this->processSettingsRow($row);
                }

                return true;
            });
        }

        Log::info('[media-migrate] Completed', [
            'dry_run' => $dryRun,
            'processed' => $this->run['processed'],
            'migrated' => $this->run['migrated'],
            'shared' => $this->run['shared'],
            'skipped' => $this->run['skipped'],
            'failed' => $this->run['failed'],
            'deferred' => $this->run['deferred'],
        ]);

        return $this->summary();
    }

    /**
     * @return array{mapped: int, dest_total: int, missing: list<array{old_pid: string, new_pid: string}>, missing_total: int, mismatched: list<array{new_pid: string, mapped_bytes: int|null, dest_bytes: int|null}>, mismatched_total: int, orphans: list<string>, orphans_total: int, pages_capped: bool, pass: bool}
     */
    public function verify(): array
    {
        $this->guard(true);

        $base = trim((string) config('services.cloudinary.upload_folder', 'maverick-academy'), '/') ?: 'maverick-academy';

        // List the DEST account (normalized world: only the shared base folder).
        $dest = [];
        $cursor = null;
        $pages = 0;

        do {
            $this->throttle();
            $page = $this->cloudinary->listDestImagesByPrefix($base, $cursor);

            foreach ($page['resources'] as $resource) {
                $pid = $resource['public_id'] ?? null;

                if (is_string($pid) && $pid !== '') {
                    $dest[$pid] = isset($resource['bytes']) ? (int) $resource['bytes'] : null;
                }
            }

            $cursor = $page['next_cursor'] ?? null;
            $pages++;
        } while ($cursor !== null && $pages < 200);

        $pagesCapped = $cursor !== null;

        $mappings = MediaMigrationMap::query()
            ->whereIn('status', ['migrated', 'shared'])
            ->whereNotNull('new_public_id')
            ->get(['old_public_id', 'new_public_id', 'bytes']);

        $missing = [];
        $mismatched = [];
        $mappedNew = [];

        foreach ($mappings as $map) {
            $mappedNew[$map->new_public_id] = true;

            if (! array_key_exists($map->new_public_id, $dest)) {
                if (count($missing) < self::SAMPLE_LIMIT) {
                    $missing[] = ['old_pid' => $map->old_public_id, 'new_pid' => $map->new_public_id];
                }

                continue;
            }

            if ($map->bytes !== null && $dest[$map->new_public_id] !== null
                && (int) $dest[$map->new_public_id] !== (int) $map->bytes) {
                if (count($mismatched) < self::SAMPLE_LIMIT) {
                    $mismatched[] = [
                        'new_pid' => $map->new_public_id,
                        'mapped_bytes' => (int) $map->bytes,
                        'dest_bytes' => (int) $dest[$map->new_public_id],
                    ];
                }
            }
        }

        $missingTotal = 0;
        $mismatchTotal = 0;

        foreach ($mappings as $map) {
            if (! array_key_exists($map->new_public_id, $dest)) {
                $missingTotal++;

                continue;
            }

            if ($map->bytes !== null && $dest[$map->new_public_id] !== null
                && (int) $dest[$map->new_public_id] !== (int) $map->bytes) {
                $mismatchTotal++;
            }
        }

        $orphans = [];
        $orphanTotal = 0;

        foreach ($dest as $pid => $bytes) {
            if (isset($mappedNew[$pid])) {
                continue;
            }

            $orphanTotal++;

            if (count($orphans) < 20) {
                $orphans[] = $pid;
            }
        }

        return [
            'mapped' => $mappings->count(),
            'dest_total' => count($dest),
            'missing' => $missing,
            'missing_total' => $missingTotal,
            'mismatched' => $mismatched,
            'mismatched_total' => $mismatchTotal,
            'orphans' => $orphans,
            'orphans_total' => $orphanTotal,
            'pages_capped' => $pagesCapped,
            'pass' => $missingTotal === 0 && $mismatchTotal === 0 && ! $pagesCapped,
        ];
    }

    /**
     * @throws \RuntimeException
     */
    protected function guard(bool $needDest): void
    {
        // [R1] Env-folder mode would scatter uploads across env-suffix
        // folders on the new account — impossible by design, so hard-fail.
        if ($this->cloudinary->usesEnvFolder()) {
            throw new \RuntimeException(
                '[R1 guard] CLOUDINARY_ENV_FOLDER is enabled — refusing to migrate. '.
                'The new account must use one shared folder (set CLOUDINARY_ENV_FOLDER=false).'
            );
        }

        if (! filled($this->cloudinary->sourceCloudName())) {
            throw new \RuntimeException(
                'Source cloud name is missing — set CLOUDINARY_SOURCE_CLOUD_NAME to the OLD account.'
            );
        }

        if (! $needDest) {
            return;
        }

        if (! $this->cloudinary->destConfigured()) {
            throw new \RuntimeException(
                'DEST credentials are missing — set CLOUDINARY_DEST_CLOUD_NAME/_API_KEY/_API_SECRET to the NEW account.'
            );
        }

        if ($this->cloudinary->destCloudName() === $this->cloudinary->sourceCloudName()) {
            throw new \RuntimeException(
                'Source and DEST cloud names are identical — refusing to migrate an account onto itself.'
            );
        }
    }

    protected function resetRun(bool $dryRun, ?int $limit): void
    {
        $this->run = [
            'dry' => $dryRun,
            'limit' => $limit !== null && $limit > 0 ? $limit : null,
            'processed' => 0,
            'migrated' => 0,
            'shared' => 0,
            'skipped' => 0,
            'failed' => 0,
            'deferred' => 0,
            'already' => 0,
            'stopped_early' => false,
            'reasons' => [],
            'failures' => [],
            'samples' => [],
        ];
        $this->seenSettings = [];
    }

    protected function loadMappingState(): void
    {
        $this->mapped = MediaMigrationMap::query()->pluck('status', 'old_public_id')->all();

        $this->failedAttempts = MediaMigrationMap::query()
            ->where('status', 'failed')
            ->pluck('attempts', 'old_public_id')
            ->all();

        $this->claimedNew = MediaMigrationMap::query()
            ->whereNotNull('new_public_id')
            ->pluck('old_public_id', 'new_public_id')
            ->all();
    }

    protected function loadCanonicalState(): void
    {
        $this->canonicalId = [];
        $this->canonicalPid = [];
        $this->canonicalOk = [];
        $this->canonicalMapping = [];

        $hashes = MediaAsset::withoutGlobalScopes([SoftDeletingScope::class])
            ->select('hash')
            ->whereNotNull('hash')
            ->where('hash', '!=', '')
            ->groupBy('hash')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('hash');

        if ($hashes->isEmpty()) {
            return;
        }

        $target = $this->cloudinary->diskEnv();

        $groups = MediaAsset::withoutGlobalScopes([SoftDeletingScope::class])
            ->whereIn('hash', $hashes)
            ->orderBy('id')
            ->get(['id', 'hash', 'disk_env', 'cloudinary_public_id', 'url', 'mime_type'])
            ->groupBy('hash');

        foreach ($groups as $hash => $rows) {
            // Same rule as the merge canonical: current disk_env row wins,
            // then the lowest id.
            $sorted = $rows->all();

            usort($sorted, static function ($a, $b) use ($target) {
                return [$a->disk_env === $target ? 0 : 1, $a->id]
                    <=> [$b->disk_env === $target ? 0 : 1, $b->id];
            });

            $canonical = $sorted[0];
            $pid = $this->rowPid($canonical);

            $this->canonicalId[$hash] = (int) $canonical->id;
            $this->canonicalPid[$hash] = $pid;
            $this->canonicalOk[$hash] = $pid !== ''
                && $this->rowUsable($pid, (string) ($canonical->url ?? ''), (string) ($canonical->mime_type ?? ''))['ok'];
        }

        $this->canonicalMapping = MediaMigrationMap::query()
            ->whereIn('old_public_id', array_values($this->canonicalPid))
            ->pluck('status', 'old_public_id')
            ->all();
    }

    protected function budgetExhausted(): bool
    {
        return $this->run['limit'] !== null && $this->run['processed'] >= $this->run['limit'];
    }

    protected function processAsset(MediaAsset $row): void
    {
        $pid = $this->rowPid($row);
        $url = trim((string) ($row->url ?? ''));
        $hash = (string) ($row->hash ?? '');

        if ($pid === '') {
            // No mappable identity — counted but never consumes budget (it
            // can never resolve, so batches must not stall on it).
            $this->countSkip('no-identity', $pid, null, 'asset', false);

            return;
        }

        $status = $this->mapped[$pid] ?? null;

        if ($status !== null && $status !== 'failed') {
            $this->run['already']++;

            return;
        }

        // [R3] Non-canonical same-hash rows share the canonical upload.
        if ($hash !== '' && isset($this->canonicalId[$hash]) && $this->canonicalId[$hash] !== (int) $row->id) {
            if (($this->canonicalOk[$hash] ?? false) === true) {
                $canPid = $this->canonicalPid[$hash];
                $canStatus = $this->canonicalMapping[$canPid] ?? $this->mapped[$canPid] ?? null;

                if ($canStatus === 'migrated') {
                    $this->recordShared($pid, $url, (int) $row->id, $canPid);

                    return;
                }

                // Canonical not migrated yet (later in id order, limited
                // batch, or failed) — pick up on a later run.
                $this->run['deferred']++;

                return;
            }
            // Canonical itself is unusable (blank identity/video/foreign) —
            // fall through and evaluate this row on its own.
        }

        $usable = $this->rowUsable($pid, $url, (string) ($row->mime_type ?? ''));

        if (! $usable['ok']) {
            $this->storeMapping([
                'old_public_id' => $pid,
                'new_public_id' => null,
                'old_url' => $url !== '' ? $url : null,
                'new_url' => null,
                'bytes' => null,
                'media_asset_id' => (int) $row->id,
                'source' => 'asset',
                'source_ref' => 'media_assets#'.$row->id,
                'status' => 'skipped',
                'reason' => $usable['reason'],
                'last_error' => null,
                'attempts' => 0,
            ]);
            $this->countSkip($usable['reason'], $pid, null, 'asset', true);

            return;
        }

        $this->attemptUpload(
            $pid,
            $this->fetchSourceUrl($url !== '' ? $url : null, $pid),
            (int) $row->id,
            'asset',
            'media_assets#'.$row->id,
            $row->size_bytes !== null ? (int) $row->size_bytes : null,
            $status === 'failed'
        );
    }

    protected function processSettingsRow(object $row): void
    {
        $label = 'settings:'.($row->group ?? '').'.'.($row->name ?? '');
        $source = $this->cloudinary->sourceCloudName();

        foreach ($this->settingsUrls($row->payload ?? null) as $url) {
            $host = strtolower((string) parse_url($url, PHP_URL_HOST));

            if (! str_contains($host, 'cloudinary.com')) {
                continue;
            }

            if ($this->resourceTypeFromUrl($url) !== 'image') {
                $this->countSkip('video-excluded', '', null, 'settings', false);

                continue;
            }

            $pid = $this->extractPublicIdSmart($url);

            if ($pid === null || $pid === '') {
                continue;
            }

            if (isset($this->seenSettings[$pid])) {
                continue;
            }

            $this->seenSettings[$pid] = true;

            $mappedStatus = $this->mapped[$pid] ?? null;

            if ($mappedStatus !== null && $mappedStatus !== 'failed') {
                $this->run['already']++;

                continue;
            }

            if ($this->cloudNameFromUrl($url) !== $source) {
                $this->storeMapping([
                    'old_public_id' => $pid,
                    'new_public_id' => null,
                    'old_url' => $url,
                    'new_url' => null,
                    'bytes' => null,
                    'media_asset_id' => null,
                    'source' => 'settings',
                    'source_ref' => $label,
                    'status' => 'skipped',
                    'reason' => 'foreign-cloud',
                    'last_error' => null,
                    'attempts' => 0,
                ]);
                $this->countSkip('foreign-cloud', $pid, null, 'settings', true);

                continue;
            }

            if ($this->budgetExhausted()) {
                $this->run['stopped_early'] = true;

                return;
            }

            $this->attemptUpload($pid, $this->fetchSourceUrl($url, $pid), null, 'settings', $label, null, $mappedStatus === 'failed');
        }
    }

    /**
     * @return list<string>
     */
    protected function settingsUrls(mixed $payload): array
    {
        if (! is_string($payload) || trim($payload) === '') {
            return [];
        }

        $trimmed = trim($payload);
        $found = [];

        if (($trimmed[0] ?? '') === '{' || ($trimmed[0] ?? '') === '[') {
            $decoded = json_decode($trimmed, true);

            if (is_array($decoded)) {
                $this->walkSettingsValue($decoded, $found);

                return array_values(array_unique($found));
            }
        }

        return $this->extractUrlsFromString($payload);
    }

    /**
     * @param  list<string>  $found
     */
    protected function walkSettingsValue(mixed $node, array &$found, int $depth = 0): void
    {
        if ($depth > 20) {
            return;
        }

        if (is_string($node)) {
            foreach ($this->extractUrlsFromString($node) as $url) {
                $found[] = $url;
            }

            return;
        }

        if (! is_array($node)) {
            return;
        }

        foreach ($node as $item) {
            $this->walkSettingsValue($item, $found, $depth + 1);
        }
    }

    protected function attemptUpload(string $pid, string $sourceUrl, ?int $assetId, string $source, ?string $sourceRef, ?int $knownBytes, bool $isRetry): void
    {
        // [R2] Legacy env-suffixed public_ids land on the shared path.
        $target = $this->normalizeNewPublicId($pid);

        // A failed row reserves its own target — that self-claim must not
        // block its retry; only another pid's claim blocks.
        if (isset($this->claimedNew[$target]) && $this->claimedNew[$target] !== $pid) {
            $this->storeMapping([
                'old_public_id' => $pid,
                'new_public_id' => $target,
                'old_url' => null,
                'new_url' => null,
                'bytes' => null,
                'media_asset_id' => $assetId,
                'source' => $source,
                'source_ref' => $sourceRef,
                'status' => 'skipped',
                'reason' => 'new-pid-claimed',
                'last_error' => null,
                'attempts' => 0,
            ]);
            $this->countSkip('new-pid-claimed', $pid, $target, $source, true);

            return;
        }

        if (! $this->run['dry']) {
            $this->throttle();
            $existing = $this->cloudinary->destResource($target);

            if ($existing !== null) {
                // Retry of a call that may have succeeded server-side: adopt
                // it when the bytes match (or cannot be compared).
                if ($isRetry && ($knownBytes === null || (int) ($existing['bytes'] ?? -1) === $knownBytes)) {
                    $this->storeMapping([
                        'old_public_id' => $pid,
                        'new_public_id' => $target,
                        'old_url' => null,
                        'new_url' => $existing['secure_url'] ?? $this->fetchDestFallback($target),
                        'bytes' => isset($existing['bytes']) ? (int) $existing['bytes'] : null,
                        'media_asset_id' => $assetId,
                        'source' => $source,
                        'source_ref' => $sourceRef,
                        'status' => 'migrated',
                        'reason' => 'adopted-on-retry',
                        'last_error' => null,
                        'attempts' => ($this->failedAttempts[$pid] ?? 0) + 1,
                    ]);
                    $this->countOutcome('migrated', $pid, $target, $source);

                    return;
                }

                $this->storeMapping([
                    'old_public_id' => $pid,
                    'new_public_id' => $target,
                    'old_url' => null,
                    'new_url' => null,
                    'bytes' => null,
                    'media_asset_id' => $assetId,
                    'source' => $source,
                    'source_ref' => $sourceRef,
                    'status' => 'skipped',
                    'reason' => 'collision',
                    'last_error' => null,
                    'attempts' => 0,
                ]);
                $this->countSkip('collision', $pid, $target, $source, true);

                return;
            }
        }

        if ($this->run['dry']) {
            // Track in-run claims so dry-run previews same-target conflicts.
            $this->claimedNew[$target] = $pid;
            $this->countOutcome('migrated', $pid, $target, $source);

            return;
        }

        $this->throttle();

        try {
            $result = $this->cloudinary->uploadRemoteImage($sourceUrl, $target);
        } catch (\Throwable $e) {
            report($e);
            $this->storeMapping([
                'old_public_id' => $pid,
                'new_public_id' => $target,
                'old_url' => null,
                'new_url' => null,
                'bytes' => null,
                'media_asset_id' => $assetId,
                'source' => $source,
                'source_ref' => $sourceRef,
                'status' => 'failed',
                'reason' => 'upload-error',
                'last_error' => substr($e->getMessage(), 0, 500),
                'attempts' => ($this->failedAttempts[$pid] ?? 0) + 1,
            ]);
            $this->countFailed($pid, $target, $source, $e->getMessage());

            return;
        }

        if (! filled($result['secure_url'] ?? null)) {
            $this->storeMapping([
                'old_public_id' => $pid,
                'new_public_id' => $target,
                'old_url' => null,
                'new_url' => null,
                'bytes' => null,
                'media_asset_id' => $assetId,
                'source' => $source,
                'source_ref' => $sourceRef,
                'status' => 'failed',
                'reason' => 'no-secure-url',
                'last_error' => 'DEST upload returned no secure_url',
                'attempts' => ($this->failedAttempts[$pid] ?? 0) + 1,
            ]);
            $this->countFailed($pid, $target, $source, 'DEST upload returned no secure_url');

            return;
        }

        $this->storeMapping([
            'old_public_id' => $pid,
            'new_public_id' => $target,
            'old_url' => null,
            'new_url' => $result['secure_url'],
            'bytes' => $result['bytes'] ?? null,
            'media_asset_id' => $assetId,
            'source' => $source,
            'source_ref' => $sourceRef,
            'status' => 'migrated',
            'reason' => null,
            'last_error' => null,
            'attempts' => ($this->failedAttempts[$pid] ?? 0) + 1,
        ]);
        $this->countOutcome('migrated', $pid, $target, $source);
    }

    protected function recordShared(string $pid, string $url, int $assetId, string $canPid): void
    {
        $can = MediaMigrationMap::query()->where('old_public_id', $canPid)->first();

        if ($can === null || $can->status !== 'migrated') {
            $this->run['deferred']++;

            return;
        }

        $this->storeMapping([
            'old_public_id' => $pid,
            'new_public_id' => $can->new_public_id,
            'old_url' => $url !== '' ? $url : null,
            'new_url' => $can->new_url,
            'bytes' => $can->bytes,
            'media_asset_id' => $assetId,
            'source' => 'asset',
            'source_ref' => 'media_assets#'.$assetId,
            'status' => 'shared',
            'reason' => 'same-hash:'.$canPid,
            'last_error' => null,
            'attempts' => 0,
        ]);
        $this->countOutcome('shared', $pid, $can->new_public_id, 'asset');
    }

    /**
     * Column public_id wins (it is the cutover join key); otherwise derive
     * the base public_id from the stored URL (transform-aware).
     */
    protected function rowPid(MediaAsset $row): string
    {
        if (filled($row->cloudinary_public_id)) {
            return trim((string) $row->cloudinary_public_id);
        }

        return $this->extractPublicIdSmart((string) ($row->url ?? '')) ?? '';
    }

    /**
     * @return array{ok: bool, reason: string}
     */
    protected function rowUsable(string $pid, string $url, string $mime): array
    {
        if ($pid === '') {
            return ['ok' => false, 'reason' => 'no-identity'];
        }

        if (str_starts_with(strtolower($mime), 'video/')) {
            return ['ok' => false, 'reason' => 'video-excluded'];
        }

        if ($url === '') {
            // PID-only row: fetch via a rebuilt source-cloud URL.
            return ['ok' => true, 'reason' => ''];
        }

        $host = strtolower((string) parse_url($url, PHP_URL_HOST));

        if (! str_contains($host, 'cloudinary.com')) {
            return ['ok' => false, 'reason' => 'non-cloudinary-url'];
        }

        if ($this->resourceTypeFromUrl($url) !== 'image') {
            return ['ok' => false, 'reason' => 'video-excluded'];
        }

        $source = $this->cloudinary->sourceCloudName();

        if ($this->cloudNameFromUrl($url) === $this->cloudinary->destCloudName()) {
            return ['ok' => false, 'reason' => 'already-on-dest'];
        }

        if ($this->cloudNameFromUrl($url) !== $source) {
            return ['ok' => false, 'reason' => 'foreign-cloud'];
        }

        return ['ok' => true, 'reason' => ''];
    }

    /**
     * [R2] Strip a legacy env suffix so the file lands on the shared path:
     * "<base>-local/x" → "<base>/x". Normal public_ids pass through.
     */
    protected function normalizeNewPublicId(string $pid): string
    {
        $base = trim((string) config('services.cloudinary.upload_folder', 'maverick-academy'), '/') ?: 'maverick-academy';

        foreach ((array) config('services.cloudinary.legacy_env_suffixes', []) as $suffix) {
            if (! filled($suffix)) {
                continue;
            }

            $prefix = $base.'-'.$suffix.'/';

            if (str_starts_with($pid, $prefix)) {
                return $base.'/'.substr($pid, strlen($prefix));
            }
        }

        return $pid;
    }

    /**
     * Fetch source for the remote upload: the exact stored URL when it is a
     * plain delivery URL, otherwise a rebuilt untransformed source-cloud URL
     * (a transform must never be baked into the migrated file).
     */
    protected function fetchSourceUrl(?string $url, string $pid): string
    {
        $source = (string) $this->cloudinary->sourceCloudName();

        if ($url !== null && $url !== '' && ! $this->hasTransformation($url)) {
            return $url;
        }

        $ext = $url ? pathinfo((string) parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: null : null;

        return $this->rebuildUrl($source, [], $pid, $ext, 'image');
    }

    protected function fetchDestFallback(string $pid): string
    {
        return $this->rebuildUrl((string) $this->cloudinary->destCloudName(), [], $pid, null, 'image');
    }

    protected function storeMapping(array $attrs): void
    {
        if ($this->run['dry']) {
            return;
        }

        MediaMigrationMap::updateOrCreate(['old_public_id' => $attrs['old_public_id']], $attrs);

        $this->mapped[$attrs['old_public_id']] = $attrs['status'];

        if (! empty($attrs['new_public_id'])) {
            $this->claimedNew[$attrs['new_public_id']] = $attrs['old_public_id'];
        }

        if (in_array($attrs['old_public_id'], $this->canonicalPid, true)) {
            $this->canonicalMapping[$attrs['old_public_id']] = $attrs['status'];
        }
    }

    protected function countOutcome(string $status, string $pid, ?string $target, string $source): void
    {
        $this->run['processed']++;
        $this->run[$status]++;

        if (count($this->run['samples']) < self::SAMPLE_LIMIT) {
            $this->run['samples'][] = ['pid' => $pid, 'new' => $target, 'status' => $status, 'source' => $source];
        }
    }

    protected function countSkip(string $reason, string $pid, ?string $target, string $source, bool $consume): void
    {
        if ($consume) {
            $this->run['processed']++;
        }

        $this->run['skipped']++;
        $this->run['reasons'][$reason] = ($this->run['reasons'][$reason] ?? 0) + 1;

        if ($pid !== '' && count($this->run['samples']) < self::SAMPLE_LIMIT) {
            $this->run['samples'][] = ['pid' => $pid, 'new' => $target, 'status' => 'skipped:'.$reason, 'source' => $source];
        }
    }

    protected function countFailed(string $pid, ?string $target, string $source, string $error): void
    {
        $this->run['processed']++;
        $this->run['failed']++;

        if (count($this->run['failures']) < 20) {
            $this->run['failures'][] = ['pid' => $pid, 'error' => substr($error, 0, 200)];
        }

        if (count($this->run['samples']) < self::SAMPLE_LIMIT) {
            $this->run['samples'][] = ['pid' => $pid, 'new' => $target, 'status' => 'failed', 'source' => $source];
        }
    }

    /**
     * @return array{dry_run: bool, limit: int|null, processed: int, migrated: int, shared: int, skipped: int, failed: int, deferred: int, already: int, remaining: int|null, skip_reasons: array<string, int>, failures: list<array{pid: string, error: string}>, samples: list<array{pid: string, new: string|null, status: string, source: string}>, dest_checks: string}
     */
    protected function summary(): array
    {
        $remaining = null;

        if (! $this->run['stopped_early']) {
            $remaining = $this->run['dry']
                ? $this->run['deferred']
                : $this->run['deferred'] + MediaMigrationMap::query()->where('status', 'failed')->count();
        }

        return [
            'dry_run' => $this->run['dry'],
            'limit' => $this->run['limit'],
            'processed' => $this->run['processed'],
            'migrated' => $this->run['migrated'],
            'shared' => $this->run['shared'],
            'skipped' => $this->run['skipped'],
            'failed' => $this->run['failed'],
            'deferred' => $this->run['deferred'],
            'already' => $this->run['already'],
            'remaining' => $remaining,
            'skip_reasons' => $this->run['reasons'],
            'failures' => $this->run['failures'],
            'samples' => $this->run['samples'],
            'dest_checks' => $this->run['dry'] ? 'skipped-dry-run' : 'live',
        ];
    }

    protected function throttle(): void
    {
        $ms = (int) config('media.migration_throttle_ms', 250);

        if ($ms > 0) {
            usleep($ms * 1000);
        }
    }
}
