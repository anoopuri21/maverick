<?php

namespace App\Console\Commands;

use App\Services\MediaMergeService;
use Illuminate\Console\Command;

class MergeDuplicatesCommand extends Command
{
    protected $signature = 'media:merge-duplicates
                            {--dry-run : Show what would merge without writing}
                            {--confirm : Repoint references and soft-delete duplicate rows}
                            {--limit= : Max duplicate groups to process}';

    protected $description = 'Merge same-hash media_assets rows into one canonical row (dry-run by default)';

    public function handle(MediaMergeService $merger): int
    {
        $confirm = (bool) $this->option('confirm');
        $dryRun = ! $confirm;
        $limit = $this->option('limit') !== null ? max(1, (int) $this->option('limit')) : null;

        if ($dryRun) {
            $this->info('Dry-run — nothing will change.');
        } else {
            $this->warn('Confirm mode — references will be repointed and duplicate rows soft-deleted.');
        }

        $result = $merger->merge(dryRun: $dryRun, limit: $limit);

        $this->newLine();
        $this->line('<fg=cyan>Duplicate groups</>');
        $this->line('  Total groups     : '.$result['groups_total']);
        $this->line('  Groups processed : '.$result['groups_processed']);
        $this->line('  Duplicates       : <fg=yellow>'.$result['merged'].'</>'.($dryRun ? ' (would merge)' : ' merged'));
        $this->line('  Skipped          : '.$result['skipped']);
        $this->line('  Errors           : <fg=red>'.count($result['errors']).'</>');

        $details = $result['details'];

        if ($details !== []) {
            $this->newLine();
            $this->table(
                ['Dup', 'Canonical', 'Hash', 'FK', 'URLs', 'Transformed', 'JSON', 'Status'],
                array_map(fn (array $row) => [
                    $row['dup_id'],
                    $row['canonical_id'],
                    $row['hash'],
                    $row['refs']['fk'],
                    $row['refs']['urls'],
                    $row['refs']['transformed'],
                    $row['refs']['json_fk'] + $row['refs']['json_urls'],
                    $row['status'],
                ], array_slice($details, 0, 50))
            );

            if (count($details) > 50) {
                $this->line('  … and '.(count($details) - 50).' more');
            }

            $jsonTouched = array_sum(array_map(
                fn (array $row) => $row['refs']['json_fk'] + $row['refs']['json_urls'],
                $details
            ));

            if ($jsonTouched > 0) {
                $this->comment('Note: settings JSON payloads are re-encoded on write (same data, normalized escaping).');
            }
        }

        if ($result['errors'] !== []) {
            $this->newLine();
            $this->warn('Error details:');

            foreach (array_slice($result['errors'], 0, 25) as $error) {
                $this->line('  - '.$error);
            }
        }

        if ($dryRun && $result['merged'] > 0) {
            $this->newLine();
            $this->comment('Dry-run only. Re-run with --confirm to apply the merges above.');
        }

        return $result['errors'] !== [] ? self::FAILURE : self::SUCCESS;
    }
}
