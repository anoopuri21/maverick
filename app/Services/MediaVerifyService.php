<?php

namespace App\Services;

use App\Models\MediaAsset;
use App\Services\Concerns\ExtractsStoredUrls;
use App\Services\Concerns\ScansMediaTables;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Phase 4 gate of the Cloudinary account migration: prove zero broken
 * images by requesting every distinct stored image URL (HEAD, with a
 * ranged-GET fallback for servers that reject HEAD).
 *
 * Any LIVE reference that fails fails the gate (any host — a broken
 * pexels image on a live page is still a broken image). Trashed-only
 * failures are hygiene warnings. Videos are out of scope (skipped) and
 * bot-hostile hosts can be allowlisted via config or --allow-host.
 */
class MediaVerifyService
{
    use ExtractsStoredUrls;
    use ScansMediaTables;

    protected const SAMPLE_LIMIT = 50;

    /**
     * @param  list<string>  $allowHosts
     * @return array{checked: int, ok: int, failed_live: list<array{url: string, source: string, status: int|null, error: string|null}>, failed_live_total: int, failed_trashed: list<array{url: string, source: string, status: int|null, error: string|null}>, failed_trashed_total: int, skipped: array{video: int, allowed_host: int}, allowed_hosts: list<string>}
     */
    public function verify(array $allowHosts = []): array
    {
        $allowed = array_values(array_unique(array_filter(array_map(
            static fn (string $host) => strtolower(trim($host)),
            array_merge((array) config('media.verify_skip_hosts', []), $allowHosts)
        ))));

        $urls = $this->collectUrls();

        $checked = 0;
        $ok = 0;
        $failedLive = [];
        $failedLiveTotal = 0;
        $failedTrashed = [];
        $failedTrashedTotal = 0;
        $skippedVideo = 0;
        $skippedAllowed = 0;

        foreach ($urls as $url => $meta) {
            if (($meta['video'] ?? false) || $this->isVideoUrl($url)) {
                $skippedVideo++;

                continue;
            }

            $host = strtolower((string) parse_url($url, PHP_URL_HOST));

            if ($host === '') {
                $this->recordFailure($meta['live'], $url, $meta['source'], null, 'unparseable url',
                    $failedLive, $failedLiveTotal, $failedTrashed, $failedTrashedTotal);

                continue;
            }

            if (in_array($host, $allowed, true)) {
                $skippedAllowed++;

                continue;
            }

            $this->throttle();
            $checked++;
            $result = $this->checkUrl($url);

            if ($result['ok']) {
                $ok++;

                continue;
            }

            $this->recordFailure($meta['live'], $url, $meta['source'], $result['status'], $result['error'],
                $failedLive, $failedLiveTotal, $failedTrashed, $failedTrashedTotal);
        }

        Log::info('[media-verify] Completed', [
            'checked' => $checked,
            'ok' => $ok,
            'failed_live' => $failedLiveTotal,
            'failed_trashed' => $failedTrashedTotal,
        ]);

        return [
            'checked' => $checked,
            'ok' => $ok,
            'failed_live' => $failedLive,
            'failed_live_total' => $failedLiveTotal,
            'failed_trashed' => $failedTrashed,
            'failed_trashed_total' => $failedTrashedTotal,
            'skipped' => ['video' => $skippedVideo, 'allowed_host' => $skippedAllowed],
            'allowed_hosts' => $allowed,
        ];
    }

    /**
     * @return array{ok: bool, status: int|null, error: string|null}
     */
    protected function checkUrl(string $url): array
    {
        try {
            $response = Http::timeout(15)->head($url);

            if (in_array($response->status(), [405, 501], true)) {
                // HEAD not supported — fetch the first KB instead of the
                // whole file (most servers honor Range; timeout bounds it).
                $response = Http::timeout(15)->withHeaders(['Range' => 'bytes=0-1023'])->get($url);
            }

            // Redirects are followed automatically; a final 3xx still means
            // the host answered for this path.
            $status = $response->status();
            $ok = ($status >= 200 && $status < 400);

            return ['ok' => $ok, 'status' => $status, 'error' => $ok ? null : 'HTTP '.$status];
        } catch (\Throwable $e) {
            return ['ok' => false, 'status' => null, 'error' => substr($e->getMessage(), 0, 200)];
        }
    }

    /**
     * @param  list<array{url: string, source: string, status: int|null, error: string|null}>  $failedLive
     * @param  list<array{url: string, source: string, status: int|null, error: string|null}>  $failedTrashed
     */
    protected function recordFailure(bool $live, string $url, string $source, ?int $status, ?string $error, array &$failedLive, int &$failedLiveTotal, array &$failedTrashed, int &$failedTrashedTotal): void
    {
        if ($live) {
            $failedLiveTotal++;

            if (count($failedLive) < self::SAMPLE_LIMIT) {
                $failedLive[] = ['url' => $url, 'source' => $source, 'status' => $status, 'error' => $error];
            }

            return;
        }

        $failedTrashedTotal++;

        if (count($failedTrashed) < self::SAMPLE_LIMIT) {
            $failedTrashed[] = ['url' => $url, 'source' => $source, 'status' => $status, 'error' => $error];
        }
    }

    /**
     * Every distinct absolute URL in the library: media_assets rows (trashed
     * included, flagged) + every scanned column. Live wins when one URL is
     * referenced both live and trashed.
     *
     * @return array<string, array{source: string, live: bool, video: bool}>
     */
    protected function collectUrls(): array
    {
        $urls = [];

        MediaAsset::withoutGlobalScopes([SoftDeletingScope::class])
            ->select(['id', 'url', 'mime_type', 'deleted_at'])
            ->orderBy('id')
            ->chunkById(200, function ($rows) use (&$urls) {
                foreach ($rows as $row) {
                    $url = trim((string) ($row->url ?? ''));

                    if ($url === '' || ! str_contains($url, 'http')) {
                        continue;
                    }

                    $this->addUrl($urls, $url, 'media_assets#'.$row->id,
                        $row->deleted_at === null,
                        str_starts_with(strtolower((string) ($row->mime_type ?? '')), 'video/'));
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
                    $base, $urlCols, $jsonCols, $textCols, $labelCols, &$urls
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
                                $this->addUrl($urls, $url, $source.'.'.$column, true, false);
                            }
                        }

                        foreach ($jsonCols as $column) {
                            foreach ($this->jsonStrings($data[$column] ?? null) as $string) {
                                foreach ($this->extractUrlsFromString($string) as $url) {
                                    $this->addUrl($urls, $url, $source.'.'.$column, true, false);
                                }
                            }
                        }
                    }
                }, $chunkColumn);
            } catch (\Throwable $e) {
                Log::warning('[media-verify] Skipped table scan', [
                    'table' => $table,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $urls;
    }

    /**
     * @param  array<string, array{source: string, live: bool, video: bool}>  $urls
     */
    protected function addUrl(array &$urls, string $url, string $source, bool $live, bool $video): void
    {
        if (! isset($urls[$url])) {
            $urls[$url] = ['source' => $source, 'live' => $live, 'video' => $video];

            return;
        }

        if ($live) {
            $urls[$url]['live'] = true;
        }
    }

    protected function isVideoUrl(string $url): bool
    {
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));

        if (str_contains($host, 'cloudinary.com')) {
            return $this->resourceTypeFromUrl($url) === 'video';
        }

        if (in_array($host, ['youtube.com', 'www.youtube.com', 'youtu.be', 'vimeo.com', 'player.vimeo.com'], true)) {
            return true;
        }

        $ext = strtolower((string) pathinfo((string) parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));

        return in_array($ext, ['mp4', 'mov', 'avi', 'webm', 'mkv', 'm3u8', 'mpd'], true);
    }

    /**
     * @return list<string>
     */
    protected function jsonStrings(mixed $value, int $depth = 0): array
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
                    return $this->jsonStrings($decoded, $depth + 1);
                }
            }

            return [$value];
        }

        if (! is_array($value)) {
            return [];
        }

        $out = [];

        foreach ($value as $item) {
            array_push($out, ...$this->jsonStrings($item, $depth + 1));
        }

        return $out;
    }

    protected function throttle(): void
    {
        $ms = (int) config('media.verify_throttle_ms', 100);

        if ($ms > 0) {
            usleep($ms * 1000);
        }
    }
}
