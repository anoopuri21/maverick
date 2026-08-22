<?php

namespace App\Services;

use App\Models\MediaAsset;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class MediaFolderNormalizer
{
    public function __construct(
        protected CloudinaryService $cloudinary,
        protected MediaUsageService $usage,
    ) {}

    /**
     * Re-point folder + disk_env to the shared-folder reality. Idempotent.
     *
     * @return array{updated: int, merged: int, skipped: int}
     */
    public function normalize(bool $dryRun = false): array
    {
        $targetDisk = $this->cloudinary->diskEnv();
        $updated = 0;
        $merged = 0;
        $skipped = 0;

        MediaAsset::withoutGlobalScopes([SoftDeletingScope::class])
            ->orderBy('id')
            ->chunkById(100, function ($assets) use ($targetDisk, $dryRun, &$updated, &$merged, &$skipped) {
                foreach ($assets as $asset) {
                    $folder = $this->cloudinary->normalizeFolderPath((string) $asset->folder);
                    if (filled($asset->cloudinary_public_id)) {
                        $fromPublicId = $this->cloudinary->normalizeFolderPath(
                            $this->cloudinary->folderFromPublicId($asset->cloudinary_public_id)
                        );
                        if ($fromPublicId !== '') {
                            $folder = $fromPublicId;
                        }
                    }

                    $diskEnv = $this->cloudinary->usesEnvFolder()
                        ? ($asset->disk_env ?: $targetDisk)
                        : $targetDisk;

                    $folderChanged = $folder !== (string) $asset->folder;
                    $diskChanged = $diskEnv !== (string) $asset->disk_env;

                    if (! $folderChanged && ! $diskChanged) {
                        $skipped++;

                        continue;
                    }

                    if ($dryRun) {
                        $updated++;

                        continue;
                    }

                    $conflict = MediaAsset::withoutGlobalScopes([SoftDeletingScope::class])
                        ->where('id', '!=', $asset->id)
                        ->where('hash', $asset->hash)
                        ->where('disk_env', $diskEnv)
                        ->orderBy('id')
                        ->first();

                    if ($conflict) {
                        $this->repointReferences((int) $asset->id, (int) $conflict->id);
                        $asset->delete();
                        $merged++;

                        continue;
                    }

                    $asset->forceFill([
                        'folder' => $folder,
                        'disk_env' => $diskEnv,
                    ])->saveQuietly();
                    $updated++;
                }
            });

        Log::info('[media-normalize-folders] Completed', [
            'dry_run' => $dryRun,
            'updated' => $updated,
            'merged' => $merged,
            'skipped' => $skipped,
        ]);

        return compact('updated', 'merged', 'skipped');
    }

    protected function repointReferences(int $fromId, int $toId): void
    {
        if ($fromId === $toId) {
            return;
        }

        $tables = method_exists(Schema::getFacadeRoot(), 'getTableListing')
            ? Schema::getTableListing()
            : Schema::getAllTables();

        foreach ($tables as $table) {
            if (in_array($table, config('media.schema_skip_tables', []), true)) {
                continue;
            }

            $columns = method_exists(Schema::getFacadeRoot(), 'getColumns')
                ? Schema::getColumns($table)
                : array_map(fn ($name) => ['name' => $name], Schema::getColumnListing($table));

            foreach ($columns as $column) {
                $name = $column['name'];
                if ($name !== 'media_asset_id' && ! str_ends_with($name, '_asset_id')) {
                    continue;
                }

                DB::table($table)->where($name, $fromId)->update([$name => $toId]);
            }
        }
    }
}
