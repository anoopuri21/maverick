<?php

namespace App\Services;

use App\Models\MediaAsset;
use App\Models\MediaRecycleLog;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use Throwable;

class MediaCleanService
{
    public function __construct(
        protected CloudinaryService $cloudinary,
        protected MediaUsageService $usage,
    ) {}

    /**
     * @return array{
     *     usage: array{used: int, unused: int, duplicates: int, referenced_ids: list<int>},
     *     unused_assets: list<array<string, mixed>>,
     *     cloudinary_orphans: list<array<string, mixed>>,
     *     cloudinary_error: string|null
     * }
     */
    public function preview(): array
    {
        $usage = $this->usage->refresh();
        $referenced = array_fill_keys($usage['referenced_ids'], true);

        $unused = MediaAsset::query()
            ->where('used', false)
            ->orderBy('id')
            ->get()
            ->filter(fn (MediaAsset $asset) => ! isset($referenced[$asset->id]))
            ->map(fn (MediaAsset $asset) => $this->summarizeAsset($asset))
            ->values()
            ->all();

        $cloudinary = $this->inspectCloudinary($referenced);

        return [
            'usage' => $usage,
            'unused_assets' => $unused,
            'cloudinary_orphans' => $cloudinary['orphans'],
            'cloudinary_error' => $cloudinary['error'],
        ];
    }

    /**
     * Soft-delete unused media_assets. Cloudinary destroy is opt-in.
     *
     * @return array{dry_run: bool, deleted: int, cloudinary_deleted: int, skipped: int, orphans: int, errors: list<string>}
     */
    public function execute(bool $dryRun = true, bool $deleteFromCloudinary = false, bool $includeOrphans = false): array
    {
        $preview = $this->preview();
        $errors = [];
        $deleted = 0;
        $cloudinaryDeleted = 0;
        $skipped = 0;

        foreach ($preview['unused_assets'] as $row) {
            if ($dryRun) {
                $deleted++;

                continue;
            }

            try {
                $result = $this->recycleAsset((int) $row['id'], $deleteFromCloudinary);
                if (! $result) {
                    $skipped++;

                    continue;
                }

                $deleted++;
                if ($result['deleted_from_cloudinary']) {
                    $cloudinaryDeleted++;
                }
            } catch (Throwable $e) {
                $errors[] = ($row['public_id'] ?? $row['id']).': '.$e->getMessage();
            }
        }

        $orphanCount = 0;
        if ($includeOrphans) {
            foreach ($preview['cloudinary_orphans'] as $orphan) {
                $orphanCount++;
                if ($dryRun || ! $deleteFromCloudinary) {
                    continue;
                }

                $publicId = $orphan['public_id'] ?? null;
                if (! $publicId) {
                    continue;
                }

                if ($this->cloudinary->deleteByPublicId($publicId)) {
                    $cloudinaryDeleted++;
                    MediaRecycleLog::create([
                        'media_asset_id' => null,
                        'cloudinary_public_id' => $publicId,
                        'url' => $orphan['url'] ?? '',
                        'deleted_from_cloudinary' => true,
                        'payload' => ['source' => 'cloudinary-orphan'],
                    ]);
                }
            }
        }

        Log::info('[media-clean] Completed', [
            'dry_run' => $dryRun,
            'deleted' => $deleted,
            'cloudinary_deleted' => $cloudinaryDeleted,
            'include_orphans' => $includeOrphans,
            'errors' => count($errors),
        ]);

        return [
            'dry_run' => $dryRun,
            'deleted' => $deleted,
            'cloudinary_deleted' => $cloudinaryDeleted,
            'skipped' => $skipped,
            'orphans' => $orphanCount,
            'errors' => $errors,
            'preview' => $preview,
        ];
    }

    /**
     * @return array{deleted_from_cloudinary: bool}|null
     */
    protected function recycleAsset(int $id, bool $deleteFromCloudinary): ?array
    {
        $asset = MediaAsset::query()->find($id);
        if (! $asset) {
            return null;
        }

        $stillUsed = $this->usage->collectReferencedAssetIds();
        if (isset($stillUsed[$asset->id]) || $asset->used) {
            $asset->forceFill(['used' => true])->saveQuietly();

            return null;
        }

        $destroyed = false;
        if ($deleteFromCloudinary && filled($asset->cloudinary_public_id)) {
            $destroyed = $this->cloudinary->deleteByPublicId($asset->cloudinary_public_id);
        }

        MediaRecycleLog::create([
            'media_asset_id' => $asset->id,
            'cloudinary_public_id' => $asset->cloudinary_public_id,
            'url' => $asset->url,
            'hash' => $asset->hash,
            'folder' => $asset->folder,
            'disk_env' => $asset->disk_env,
            'original_name' => $asset->original_name,
            'deleted_from_cloudinary' => $destroyed,
            'payload' => [
                'mime_type' => $asset->mime_type,
                'size_bytes' => $asset->size_bytes,
            ],
        ]);

        $asset->delete();

        return ['deleted_from_cloudinary' => $destroyed];
    }

    /**
     * @param  array<int, true>  $referenced
     * @return array{orphans: list<array<string, mixed>>, error: string|null}
     */
    protected function inspectCloudinary(array $referenced): array
    {
        if (! $this->cloudinary->hasCredentials()) {
            return ['orphans' => [], 'error' => 'Cloudinary credentials are not configured; skipped remote compare.'];
        }

        try {
            $known = MediaAsset::withoutGlobalScopes([SoftDeletingScope::class])
                ->whereNotNull('cloudinary_public_id')
                ->pluck('id', 'cloudinary_public_id');

            $referencedPublicIds = MediaAsset::withoutGlobalScopes([SoftDeletingScope::class])
                ->whereIn('id', array_keys($referenced))
                ->pluck('cloudinary_public_id')
                ->filter()
                ->flip();

            $orphans = [];
            foreach ($this->cloudinary->listPrefixes() as $prefix) {
                $nextCursor = null;
                do {
                    $page = $this->cloudinary->listImagesByPrefix($prefix, $nextCursor);
                    foreach ($page['resources'] as $resource) {
                        $publicId = $resource['public_id'] ?? null;
                        if (! $publicId) {
                            continue;
                        }

                        $assetId = $known[$publicId] ?? null;
                        if ($assetId && isset($referenced[$assetId])) {
                            continue;
                        }
                        if (isset($referencedPublicIds[$publicId])) {
                            continue;
                        }

                        $orphans[$publicId] = [
                            'public_id' => $publicId,
                            'url' => $resource['secure_url'] ?? null,
                            'in_database' => $assetId !== null,
                            'asset_id' => $assetId,
                        ];
                    }
                    $nextCursor = $page['next_cursor'];
                    if ($nextCursor) {
                        usleep(250000);
                    }
                } while ($nextCursor);
            }

            return ['orphans' => array_values($orphans), 'error' => null];
        } catch (Throwable $e) {
            return ['orphans' => [], 'error' => $e->getMessage()];
        }
    }

    /**
     * @return array<string, mixed>
     */
    protected function summarizeAsset(MediaAsset $asset): array
    {
        return [
            'id' => $asset->id,
            'name' => $asset->original_name,
            'folder' => $asset->folder,
            'disk_env' => $asset->disk_env,
            'public_id' => $asset->cloudinary_public_id,
            'url' => $asset->url,
            'size_bytes' => $asset->size_bytes,
            'is_duplicate' => (bool) $asset->is_duplicate,
        ];
    }
}
