<?php

namespace App\Console\Commands;

use App\Services\CloudinaryMediaMigrationService;
use App\Support\PublicContentCache;
use Illuminate\Console\Command;

class MigrateCloudinaryMediaCommand extends Command
{
    protected $signature = 'media:migrate-cloudinary
                            {--dry-run : Inventory legacy URLs only; do not download or rewrite anything}
                            {--confirm : Copy images to this server and rewrite database URLs}
                            {--skip-remote : Do not inventory image assets from the Cloudinary account}
                            {--skip-static : Do not recover legacy CSS and default-settings images}';

    protected $description = 'Copy Cloudinary images to self-hosted storage and replace all database references';

    public function handle(CloudinaryMediaMigrationService $migrator): int
    {
        $write = (bool) $this->option('confirm');

        if (! $write) {
            $this->warn('Dry-run — no image will be downloaded and no database URL will change. Use --confirm after reviewing the inventory.');
        } else {
            $this->warn('Migration mode — images are copied to storage/app/public and database URLs are rewritten. Cloudinary files are never deleted.');
        }

        $result = $migrator->run(
            write: $write,
            includeRemote: ! (bool) $this->option('skip-remote'),
            includeStatic: ! (bool) $this->option('skip-static'),
        );

        if ($write && $result['rows_updated'] > 0) {
            // URL values are also held in the application's public-page cache.
            // Spatie settings cache is cleared in the deployment runbook below.
            PublicContentCache::flush();
        }

        $this->newLine();
        $this->line('<fg=cyan>Self-hosted media migration</>');
        $this->line('  Cloudinary image occurrences : '.$result['sources']);
        $this->line('  Unique source images         : '.$result['unique_sources']);
        $this->line('  Copied to this server        : <fg=green>'.$result['recovered'].'</>');
        $this->line('  Already on this server       : '.$result['already_present']);
        $this->line('  Database rows rewritten      : <fg=green>'.$result['rows_updated'].'</>');
        $this->line('  Bootstrap images recovered   : '.$result['static_recovered']);
        $this->line('  Remote-account images checked: '.$result['remote_recovered']);
        $this->line('  Errors                       : <fg=red>'.count($result['errors']).'</>');

        if ($write) {
            $this->newLine();
            $this->comment('Next: php artisan optimize:clear && php artisan storage:link');
            $this->comment('Verify the site and an admin upload before removing Cloudinary credentials or disabling the old account.');
        }

        if ($result['errors'] !== []) {
            $this->newLine();
            $this->warn('The following source URLs were left unchanged and should be retried after fixing access:');
            foreach (array_slice($result['errors'], 0, 25) as $error) {
                $this->line('  - '.$error);
            }
            if (count($result['errors']) > 25) {
                $this->line('  … and '.(count($result['errors']) - 25).' more');
            }
        }

        return $result['errors'] === [] ? self::SUCCESS : self::FAILURE;
    }
}
