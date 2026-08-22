<?php

namespace App\Console\Commands;

use App\Models\MediaAsset;
use App\Services\CloudinaryService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Throwable;

class SyncCloudinaryMediaCommand extends Command
{
    protected $signature = 'media:sync-cloudinary
                            {--folder= : Cloudinary folder/prefix to sync (default: resolveBaseFolder())}
                            {--dry-run : Report what would be created without inserting}';

    protected $description = 'Sync Cloudinary folder assets into the media_assets table (idempotent)';

    public function handle(CloudinaryService $cloudinary): int
    {
        $folder = $this->option('folder') ?: $cloudinary->resolveBaseFolder();
        $dryRun = (bool) $this->option('dry-run');
        $diskEnv = $cloudinary->diskEnv();

        $this->info('Syncing Cloudinary prefix: '.$folder.($dryRun ? ' (dry-run)' : ''));

        $fetched = 0;
        $created = 0;
        $skipped = 0;
        $errors = [];

        $nextCursor = null;
        $page = 0;

        $bar = $this->output->createProgressBar();
        $bar->start();

        try {
            do {
                $page++;
                $response = $cloudinary->listImagesByPrefix($folder, $nextCursor);
                $resources = $response['resources'];
                $nextCursor = $response['next_cursor'];

                foreach ($resources as $resource) {
                    $fetched++;
                    $bar->advance();

                    try {
                        $publicId = $resource['public_id'] ?? null;
                        $secureUrl = $resource['secure_url'] ?? null;

                        if (! $publicId || ! $secureUrl) {
                            $errors[] = 'Missing public_id or secure_url on resource';
                            continue;
                        }

                        $exists = MediaAsset::withoutGlobalScopes([SoftDeletingScope::class])
                            ->where('cloudinary_public_id', $publicId)
                            ->exists();

                        if ($exists) {
                            $skipped++;
                            continue;
                        }

                        if ($dryRun) {
                            $created++;
                            continue;
                        }

                        MediaAsset::create([
                            'hash' => $this->syntheticHash($publicId),
                            'original_name' => basename($publicId),
                            'mime_type' => $this->mimeFromFormat($resource['format'] ?? null),
                            'size_bytes' => isset($resource['bytes']) ? (int) $resource['bytes'] : null,
                            'width' => isset($resource['width']) ? (int) $resource['width'] : null,
                            'height' => isset($resource['height']) ? (int) $resource['height'] : null,
                            'cloudinary_public_id' => $publicId,
                            'url' => $secureUrl,
                            'folder' => $cloudinary->normalizeFolderPath($this->folderFromPublicId($publicId)),
                            'disk_env' => $diskEnv,
                            'used' => false,
                        ]);

                        $created++;
                    } catch (Throwable $e) {
                        $id = $resource['public_id'] ?? 'unknown';
                        $errors[] = "{$id}: ".$e->getMessage();
                    }
                }

                if ($nextCursor) {
                    usleep(250000);
                }
            } while ($nextCursor);
        } catch (Throwable $e) {
            $bar->finish();
            $this->newLine(2);
            $this->error('Sync aborted: '.$e->getMessage());

            return self::FAILURE;
        }

        $bar->finish();
        $this->newLine(2);

        $this->line('<fg=cyan>Cloudinary Media Sync Summary</>');
        $this->line('  Folder/prefix : '.$folder);
        $this->line('  Pages         : '.$page);
        $this->line('  Total fetched : <fg=white>'.$fetched.'</>');
        $this->line('  Created       : <fg=green>'.$created.'</>'.($dryRun ? ' (would create)' : ''));
        $this->line('  Skipped       : <fg=yellow>'.$skipped.'</>');
        $this->line('  Errors        : <fg=red>'.count($errors).'</>');

        if ($errors !== []) {
            $this->newLine();
            $this->warn('Error details:');
            foreach (array_slice($errors, 0, 25) as $error) {
                $this->line('  - '.$error);
            }
            if (count($errors) > 25) {
                $this->line('  … and '.(count($errors) - 25).' more');
            }
        }

        return count($errors) > 0 && $created === 0 && $skipped === 0
            ? self::FAILURE
            : self::SUCCESS;
    }

    /**
     * Fits varchar(64): "imported:" (9) + 55 hex chars from sha256(public_id).
     */
    protected function syntheticHash(string $publicId): string
    {
        return 'imported:'.substr(hash('sha256', $publicId), 0, 55);
    }

    protected function folderFromPublicId(string $publicId): string
    {
        $dirname = dirname($publicId);

        if ($dirname === '.' || $dirname === DIRECTORY_SEPARATOR) {
            return '';
        }

        return str_replace('\\', '/', $dirname);
    }

    protected function mimeFromFormat(?string $format): ?string
    {
        if (! $format) {
            return null;
        }

        return match (strtolower($format)) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg', 'svgz' => 'image/svg+xml',
            'bmp' => 'image/bmp',
            'tif', 'tiff' => 'image/tiff',
            'avif' => 'image/avif',
            'heic' => 'image/heic',
            'ico' => 'image/x-icon',
            default => 'image/'.strtolower($format),
        };
    }
}
