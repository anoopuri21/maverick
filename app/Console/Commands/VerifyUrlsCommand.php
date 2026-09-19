<?php

namespace App\Console\Commands;

use App\Services\MediaVerifyService;
use Illuminate\Console\Command;

class VerifyUrlsCommand extends Command
{
    protected $signature = 'media:verify-urls
        {--allow-host=* : Skip a host entirely (repeatable, e.g. bot-hostile externals)}';

    protected $description = 'Request every distinct stored image URL and report non-200s (Phase 4 gate).';

    public function handle(MediaVerifyService $service): int
    {
        $result = $service->verify((array) $this->option('allow-host'));

        $this->line(sprintf(
            'Checked %d urls: %d ok, %d live failures, %d trashed-only failures (%d videos + %d allowed hosts skipped).',
            $result['checked'],
            $result['ok'],
            $result['failed_live_total'],
            $result['failed_trashed_total'],
            $result['skipped']['video'],
            $result['skipped']['allowed_host']
        ));

        foreach ($result['failed_live'] as $failure) {
            $this->error(sprintf('%s [%s] %s',
                $failure['url'],
                $failure['source'],
                $failure['status'] !== null ? 'HTTP '.$failure['status'] : ($failure['error'] ?? 'unknown error')
            ));
        }

        foreach ($result['failed_trashed'] as $failure) {
            $this->warn(sprintf('trashed-only: %s [%s] %s',
                $failure['url'],
                $failure['source'],
                $failure['status'] !== null ? 'HTTP '.$failure['status'] : ($failure['error'] ?? 'unknown error')
            ));
        }

        if ($result['failed_live_total'] > count($result['failed_live'])
            || $result['failed_trashed_total'] > count($result['failed_trashed'])) {
            $this->line('Output capped — see counts above for totals.');
        }

        if ($result['failed_live_total'] > 0) {
            $this->error('Verify FAILED — fix the urls above (or explicitly allowlist their hosts) and rerun.');

            return self::FAILURE;
        }

        $this->info('Verify PASSED — every live image URL is reachable.');

        return self::SUCCESS;
    }
}
