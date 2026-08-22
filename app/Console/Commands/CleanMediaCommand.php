<?php

namespace App\Console\Commands;

use App\Services\MediaCleanService;
use Illuminate\Console\Command;

class CleanMediaCommand extends Command
{
    protected $signature = 'media:clean
                            {--dry-run : Default. Show unused media without deleting}
                            {--confirm : Soft-delete unused media_assets (never deletes used files)}
                            {--purge-cloudinary : Also destroy unused files on Cloudinary}
                            {--include-orphans : Include Cloudinary files not referenced by the site}';

    protected $description = 'Flag unused media and optionally recycle it (dry-run first; never deletes used files)';

    public function handle(MediaCleanService $cleaner): int
    {
        $confirm = (bool) $this->option('confirm');
        $dryRun = ! $confirm;
        $purge = (bool) $this->option('purge-cloudinary');
        $orphans = (bool) $this->option('include-orphans');

        if ($dryRun) {
            $this->info('Dry-run — nothing will be deleted.');
        } else {
            $this->warn('Confirm mode — unused media_assets will be soft-deleted.');
            if ($purge) {
                $this->warn('Cloudinary destroy is ON. Recycle log will keep public_id + URL.');
            }
        }

        $result = $cleaner->execute(
            dryRun: $dryRun,
            deleteFromCloudinary: $confirm && $purge,
            includeOrphans: $orphans,
        );

        $preview = $result['preview'];
        $usage = $preview['usage'];

        $this->newLine();
        $this->line('<fg=cyan>Media usage</>');
        $this->line('  Used       : <fg=green>'.$usage['used'].'</>');
        $this->line('  Unused     : <fg=yellow>'.$usage['unused'].'</>');
        $this->line('  Duplicates : '.$usage['duplicates']);

        $this->newLine();
        $this->line('<fg=cyan>Clean result</>');
        $this->line('  Unused rows : <fg=yellow>'.$result['deleted'].'</>'.($dryRun ? ' (would recycle)' : ' recycled'));
        $this->line('  Cloudinary  : '.$result['cloudinary_deleted'].($purge ? '' : ' (not purged)'));
        $this->line('  Orphans     : '.$result['orphans']);
        $this->line('  Skipped     : '.$result['skipped']);
        $this->line('  Errors      : <fg=red>'.count($result['errors']).'</>');

        if ($preview['cloudinary_error']) {
            $this->newLine();
            $this->warn('Cloudinary compare: '.$preview['cloudinary_error']);
        }

        $unused = $preview['unused_assets'];
        if ($unused !== []) {
            $this->newLine();
            $this->table(
                ['ID', 'Name', 'Folder', 'Public ID', 'Duplicate'],
                array_map(fn (array $row) => [
                    $row['id'],
                    $row['name'] ?? '',
                    $row['folder'] ?? '',
                    $row['public_id'] ?? '',
                    ! empty($row['is_duplicate']) ? 'yes' : '',
                ], array_slice($unused, 0, 50))
            );
            if (count($unused) > 50) {
                $this->line('  … and '.(count($unused) - 50).' more');
            }
        }

        if ($result['errors'] !== []) {
            $this->newLine();
            foreach (array_slice($result['errors'], 0, 25) as $error) {
                $this->line('  - '.$error);
            }
        }

        return $result['errors'] !== [] ? self::FAILURE : self::SUCCESS;
    }
}
