<?php

namespace App\Console\Commands;

use App\Services\MediaAuditService;
use Illuminate\Console\Command;

class AuditCloudinaryUrlsCommand extends Command
{
    protected $signature = 'media:audit-cloudinary-urls';

    protected $description = 'Inventory every stored image URL (read-only pre-flight for the Cloudinary account migration)';

    public function handle(MediaAuditService $audit): int
    {
        $this->info('Scanning database for stored image URLs (read-only, nothing will change)...');
        $this->newLine();

        $report = $audit->audit();

        $this->renderGuard($report['guard']);
        $this->renderAssets($report['assets'], $report['scan']);
        $this->renderCloudNames($report['cloud_names'], $report['external_hosts'], $report['unparsed_urls']);
        $this->renderTransformed($report['transformed']);
        $this->renderLegacy($report['legacy_public_ids']);
        $this->renderDuplicates($report['duplicate_groups']);

        $this->newLine();
        $this->comment('Read-only audit complete. Next: media:merge-duplicates --dry-run, then media:migrate-account --dry-run.');

        return self::SUCCESS;
    }

    /**
     * @param  array{env_folder: bool, disk_env: string, base_folder: string, pass: bool}  $guard
     */
    protected function renderGuard(array $guard): void
    {
        $this->line('<fg=cyan>Shared-folder guard</>');

        if ($guard['pass']) {
            $this->line('  Status      : <fg=green>PASS (shared folder mode)</>');
        } else {
            $this->error('  Status      : FAIL — CLOUDINARY_ENV_FOLDER is ON. Migration commands will refuse to run.');
        }

        $this->line('  Base folder : '.$guard['base_folder']);
        $this->line('  disk_env    : '.$guard['disk_env']);
        $this->newLine();
    }

    /**
     * @param  array{total: int, trashed: int, missing_url: int, missing_public_id: int, total_bytes: int, unknown_bytes: int}  $assets
     * @param  array{tables: int, skipped: list<string>, distinct_urls: int}  $scan
     */
    protected function renderAssets(array $assets, array $scan): void
    {
        $this->line('<fg=cyan>Library volume</>');
        $this->line('  media_assets rows : '.$assets['total'].' (trashed: '.$assets['trashed'].')');
        $this->line('  Missing URL       : '.$assets['missing_url'].' · Missing public_id: '.$assets['missing_public_id']);
        $this->line('  Stored bytes      : '.$this->formatBytes($assets['total_bytes']).' (unknown size: '.$assets['unknown_bytes'].' rows)');
        $this->line('  Tables scanned    : '.$scan['tables'].' · Skipped: '.count($scan['skipped']));
        $this->line('  Distinct URLs     : <fg=white>'.$scan['distinct_urls'].'</>');
        $this->newLine();
    }

    /**
     * @param  array<string, int>  $cloudNames
     * @param  array<string, int>  $externalHosts
     */
    protected function renderCloudNames(array $cloudNames, array $externalHosts, int $unparsed): void
    {
        $this->line('<fg=cyan>Cloud names referenced (distinct URLs)</>');

        if ($cloudNames === []) {
            $this->line('  (no Cloudinary URLs found)');
        } else {
            $this->table(
                ['Cloud name', 'URLs'],
                array_map(
                    static fn (string $cloud, int $count) => [$cloud, $count],
                    array_keys($cloudNames),
                    array_values($cloudNames)
                )
            );
        }

        if ($externalHosts !== []) {
            $this->line('<fg=cyan>Non-Cloudinary hosts (never migrated, left untouched)</>');
            $this->table(
                ['Host', 'URLs'],
                array_map(
                    static fn (string $host, int $count) => [$host, $count],
                    array_keys($externalHosts),
                    array_values($externalHosts)
                )
            );
        }

        if ($unparsed > 0) {
            $this->warn('  Unparsed URL strings: '.$unparsed);
        }

        $this->newLine();
    }

    /**
     * @param  array{total: int, samples: list<array{url: string, source: string}>}  $transformed
     */
    protected function renderTransformed(array $transformed): void
    {
        $this->line('<fg=cyan>Stored transformation URLs (need manual mapping review)</>');
        $this->line('  Total: '.$transformed['total']);

        if ($transformed['samples'] !== []) {
            $this->table(
                ['URL', 'Found in'],
                array_map(
                    static fn (array $row) => [$row['url'], $row['source']],
                    $transformed['samples']
                )
            );
        }

        $this->newLine();
    }

    /**
     * @param  array{total: int, samples: list<array{public_id: string, source: string}>}  $legacy
     */
    protected function renderLegacy(array $legacy): void
    {
        $this->line('<fg=cyan>Legacy env-prefixed public_ids (will normalize into the shared folder)</>');
        $this->line('  Total: '.$legacy['total']);

        if ($legacy['samples'] !== []) {
            $this->table(
                ['Public ID', 'Found in'],
                array_map(
                    static fn (array $row) => [$row['public_id'], $row['source']],
                    $legacy['samples']
                )
            );
        }

        $this->newLine();
    }

    /**
     * @param  array{total: int, samples: list<array{hash: string, ids: list<int>, count: int}>}  $duplicates
     */
    protected function renderDuplicates(array $duplicates): void
    {
        $this->line('<fg=cyan>Same-hash duplicate groups (merge candidates)</>');
        $this->line('  Total groups: '.$duplicates['total']);

        if ($duplicates['samples'] !== []) {
            $this->table(
                ['Hash', 'Asset IDs', 'Count'],
                array_map(
                    static fn (array $row) => [$row['hash'], implode(', ', $row['ids']), $row['count']],
                    $duplicates['samples']
                )
            );
        }
    }

    protected function formatBytes(int $bytes): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = (int) min(floor(log($bytes, 1024)), count($units) - 1);

        return round($bytes / (1024 ** $power), 1).' '.$units[$power];
    }
}
