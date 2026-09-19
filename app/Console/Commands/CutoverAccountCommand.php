<?php

namespace App\Console\Commands;

use App\Services\MediaCutoverService;
use Illuminate\Console\Command;

class CutoverAccountCommand extends Command
{
    protected $signature = 'media:cutover-account
        {--confirm : Apply the cutover (default is dry-run)}';

    protected $description = 'Point stored URLs at the new Cloudinary account using only verified mapping rows (Phase 2).';

    public function handle(MediaCutoverService $service): int
    {
        $dryRun = ! (bool) $this->option('confirm');

        try {
            $result = $service->cutover($dryRun);
        } catch (\RuntimeException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        if ($dryRun) {
            $this->info('DRY-RUN — no writes.');
        } else {
            $this->info('Cutover applied. (Rollback until Phase 5 = restore the fresh pre-cutover DB backup.)');
        }

        $this->line(sprintf(
            'Assets: %d scanned, %d updated, %d current, %d skipped.',
            $result['assets']['scanned'],
            $result['assets']['updated'],
            $result['assets']['current'],
            $result['assets']['skipped']
        ));
        $this->line(sprintf(
            'Fields: %d rows touched, %d cells updated, %d current, %d skipped (%d mismatch repairs, %d blank fills).',
            $result['fields']['rows_touched'],
            $result['fields']['cells_updated'],
            $result['fields']['current'],
            $result['fields']['skipped'],
            $result['fields']['repaired'],
            $result['fields']['filled']
        ));
        $this->line(sprintf(
            'Settings: %d rows, %d updated, %d urls updated, %d current, %d skipped (%d repairs).',
            $result['settings']['rows'],
            $result['settings']['rows_updated'],
            $result['settings']['urls_updated'],
            $result['settings']['current'],
            $result['settings']['skipped'],
            $result['settings']['repaired']
        ));

        foreach (['assets' => 'Asset skips', 'fields' => 'Field skips', 'settings' => 'Settings skips'] as $phase => $title) {
            if ($result[$phase]['reasons'] === []) {
                continue;
            }

            $rows = [];

            foreach ($result[$phase]['reasons'] as $reason => $count) {
                $rows[] = [$reason, $count];
            }

            $this->table([$title, 'Count'], $rows);
        }

        if ($dryRun) {
            $this->info('Review the counts above, then run with --confirm (after a fresh DB backup).');

            return self::SUCCESS;
        }

        $residue = $result['residue'];

        $this->line(sprintf(
            'Residue: %d source-cloud IMAGE urls, %d source-cloud video urls (videos stay on the old account by design).',
            $residue['image_total'],
            $residue['video_total']
        ));

        foreach ($residue['image_samples'] as $sample) {
            $this->error('residue: '.$sample['url'].' ('.$sample['source'].')');
        }

        // [R4] Local databases are separate — they do not follow prod cutover.
        $this->info('Reminder [R4]: every dev must refresh their LOCAL db from a post-cutover backup (never run media:sync-cloudinary locally).');

        if ($residue['image_total'] > 0) {
            $this->error('Cutover INCOMPLETE — resolve the skips above (rerun migrate for failures/collisions, then cutover again).');

            return self::FAILURE;
        }

        $this->info('Cutover COMPLETE — zero source-cloud image references remain.');

        return self::SUCCESS;
    }
}
