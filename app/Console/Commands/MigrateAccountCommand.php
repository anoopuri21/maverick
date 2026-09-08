<?php

namespace App\Console\Commands;

use App\Services\MediaMigrationService;
use Illuminate\Console\Command;

class MigrateAccountCommand extends Command
{
    protected $signature = 'media:migrate-account
        {--dry-run : Preview without uploading or writing mapping rows}
        {--limit= : Max rows to process in this run (shared-hosting batches)}
        {--verify : Compare the mapping against the DEST account listing}
        {--retry-skipped : Reset collision-family skips to pending and retry them}';

    protected $description = 'Copy media files from the old Cloudinary account to the new one (fetch-upload) and record the mapping (Phase 1).';

    public function handle(MediaMigrationService $service): int
    {
        if ($this->option('verify')) {
            return $this->runVerify($service);
        }

        $dryRun = (bool) $this->option('dry-run');
        $limit = $this->option('limit') !== null && (int) $this->option('limit') > 0
            ? (int) $this->option('limit')
            : null;

        try {
            $result = $service->migrate($dryRun, $limit, (bool) $this->option('retry-skipped'));
        } catch (\RuntimeException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info($dryRun ? 'DRY-RUN — no uploads, no mapping writes.' : 'Migration run completed.');

        if (($result['retried_skipped'] ?? 0) > 0) {
            $this->line(sprintf('Collision-family skips reset for retry: %d.', $result['retried_skipped']));
        }
        $this->info(sprintf(
            'Processed: %d (migrated %d, shared %d, skipped %d, failed %d, deferred %d, already %d)%s',
            $result['processed'],
            $result['migrated'],
            $result['shared'],
            $result['skipped'],
            $result['failed'],
            $result['deferred'],
            $result['already'],
            $result['remaining'] !== null ? ', remaining ~'.$result['remaining'] : ' (rerun to continue)'
        ));

        if ($result['skip_reasons'] !== []) {
            $rows = [];

            foreach ($result['skip_reasons'] as $reason => $count) {
                $rows[] = [$reason, $count];
            }

            $this->table(['Skip reason', 'Count'], $rows);
        }

        foreach ($result['failures'] as $failure) {
            $this->error($failure['pid'].': '.$failure['error']);
        }

        if ($result['samples'] !== []) {
            $rows = [];

            foreach (array_slice($result['samples'], 0, 20) as $sample) {
                $rows[] = [$sample['pid'], $sample['new'] ?? '—', $sample['status'], $sample['source']];
            }

            $this->table(['Old public_id', 'New public_id', 'Status', 'Source'], $rows);
        }

        if ($result['failed'] > 0) {
            $this->error('Failures present — fix causes and rerun (failed rows retry automatically).');

            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    protected function runVerify(MediaMigrationService $service): int
    {
        try {
            $result = $service->verify();
        } catch (\RuntimeException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info(sprintf(
            'Mapped: %d, on DEST: %d, missing: %d, byte-mismatched: %d, DEST orphans: %d%s',
            $result['mapped'],
            $result['dest_total'],
            $result['missing_total'],
            $result['mismatched_total'],
            $result['orphans_total'],
            $result['pages_capped'] ? ' (LISTING CAPPED — inconclusive)' : ''
        ));

        foreach ($result['missing'] as $row) {
            $this->error('missing on DEST: '.$row['new_pid'].' (old: '.$row['old_pid'].')');
        }

        foreach ($result['mismatched'] as $row) {
            $this->error(sprintf(
                'bytes differ: %s (mapped %s, dest %s)',
                $row['new_pid'],
                $row['mapped_bytes'] ?? '?',
                $row['dest_bytes'] ?? '?'
            ));
        }

        if ($result['pass']) {
            $this->info('Verify PASSED — every mapped file exists on DEST with matching bytes.');

            return self::SUCCESS;
        }

        $this->error('Verify FAILED — see rows above.');

        return self::FAILURE;
    }
}
