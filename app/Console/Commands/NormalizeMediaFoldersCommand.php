<?php

namespace App\Console\Commands;

use App\Services\CloudinaryService;
use App\Services\MediaFolderNormalizer;
use Illuminate\Console\Command;

class NormalizeMediaFoldersCommand extends Command
{
    protected $signature = 'media:normalize-folders
                            {--dry-run : Report folder/disk_env updates without writing}';

    protected $description = 'Re-point media_assets folder and disk_env to the shared Cloudinary folder (idempotent)';

    public function handle(MediaFolderNormalizer $normalizer, CloudinaryService $cloudinary): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $this->info('Base folder : '.$cloudinary->resolveBaseFolder());
        $this->info('disk_env    : '.$cloudinary->diskEnv());
        $this->info('env_folder  : '.($cloudinary->usesEnvFolder() ? 'on' : 'off (shared)'));
        if ($dryRun) {
            $this->warn('Dry-run — no rows will be updated.');
        }

        $result = $normalizer->normalize($dryRun);

        $this->newLine();
        $this->line('<fg=cyan>Media folder normalize</>');
        $this->line('  Updated : <fg=green>'.$result['updated'].'</>'.($dryRun ? ' (would update)' : ''));
        $this->line('  Merged  : <fg=yellow>'.$result['merged'].'</>');
        $this->line('  Skipped : '.$result['skipped']);

        return self::SUCCESS;
    }
}
