<?php

namespace Tests\Feature;

use App\Models\MediaAsset;
use App\Models\MediaMigrationMap;
use App\Services\CloudinaryService;
use App\Services\MediaMigrationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FakeDestCloudinaryService extends CloudinaryService
{
    /** @var list<array{remote: string, pid: string}> */
    public array $uploads = [];

    /** @var array<string, array{secure_url: string, bytes: int, public_id: string}> */
    public array $resources = [];

    /** @var list<string> public_ids whose upload must throw */
    public array $failPids = [];

    public function uploadRemoteImage(string $remoteUrl, string $publicId): array
    {
        $this->uploads[] = ['remote' => $remoteUrl, 'pid' => $publicId];

        if (in_array($publicId, $this->failPids, true)) {
            throw new \RuntimeException('boom-'.$publicId);
        }

        $url = 'https://res.cloudinary.com/'.($this->destCloudName() ?? 'demo-dest')
            .'/image/upload/v777/'.$publicId.'.jpg';

        $this->resources[$publicId] = ['secure_url' => $url, 'bytes' => 1234, 'public_id' => $publicId];

        return $this->resources[$publicId];
    }

    public function destResource(string $publicId): ?array
    {
        return $this->resources[$publicId] ?? null;
    }

    public function listDestImagesByPrefix(string $prefix, ?string $nextCursor = null): array
    {
        $out = [];

        foreach ($this->resources as $pid => $resource) {
            if (str_starts_with($pid, $prefix)) {
                $out[] = $resource;
            }
        }

        return ['resources' => $out, 'next_cursor' => null];
    }
}

class MediaMigrationTest extends TestCase
{
    use RefreshDatabase;

    protected FakeDestCloudinaryService $fake;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.cloudinary.source_cloud_name', 'demo-source');
        config()->set('services.cloudinary.dest_cloud_name', 'demo-dest');
        config()->set('services.cloudinary.dest_api_key', 'test-key');
        config()->set('services.cloudinary.dest_api_secret', 'test-secret');
        config()->set('services.cloudinary.env_folder', false);
        config()->set('services.cloudinary.disk_env', 'shared');
        config()->set('media.migration_throttle_ms', 0);

        $this->fake = new FakeDestCloudinaryService();
        $this->app->instance(CloudinaryService::class, $this->fake);
    }

    public function test_guard_hard_fails_when_env_folder_enabled(): void
    {
        config()->set('services.cloudinary.env_folder', true);

        $this->assertGuardBlocks('R1 guard');
    }

    public function test_guard_requires_source_and_dest_config(): void
    {
        config()->set('services.cloudinary.source_cloud_name', null);
        $this->assertGuardBlocks('SOURCE_CLOUD_NAME');

        config()->set('services.cloudinary.source_cloud_name', 'demo-source');
        config()->set('services.cloudinary.dest_cloud_name', null);
        $this->assertGuardBlocks('DEST');

        config()->set('services.cloudinary.dest_cloud_name', 'demo-source');
        $this->assertGuardBlocks('identical');
    }

    public function test_dry_run_reports_without_uploads_or_writes(): void
    {
        $this->createPair();
        $this->createSetting(
            'migrate-test', 'logo',
            'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/site/logo.jpg'
        );
        MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('b', 64),
            'cloudinary_public_id' => 'maverick-academy/vids/v',
            'url' => 'https://res.cloudinary.com/demo-source/video/upload/v1/maverick-academy/vids/v.mp4',
            'mime_type' => 'video/mp4',
        ]));

        $result = app(MediaMigrationService::class)->migrate(dryRun: true);

        $this->assertTrue($result['dry_run']);
        $this->assertSame([], $this->fake->uploads);
        $this->assertSame(0, MediaMigrationMap::query()->count());
        $this->assertSame('skipped-dry-run', $result['dest_checks']);
        // Canonical + settings URL would upload; dup defers; video skips.
        $this->assertSame(2, $result['migrated']);
        $this->assertSame(1, $result['deferred']);
        $this->assertGreaterThanOrEqual(1, $result['skipped']);
    }

    public function test_migrate_uploads_and_records_mapping_without_touching_assets(): void
    {
        [$canonical, $dup] = $this->createPair();
        $canUrl = $canonical->url;
        $dupUrl = $dup->url;
        $this->createSetting(
            'migrate-test', 'logo',
            'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/site/logo.jpg'
        );

        $result = app(MediaMigrationService::class)->migrate();

        $this->assertSame(0, $result['failed']);
        $this->assertSame(2, $result['migrated']);
        $this->assertSame(1, $result['shared']);

        $pids = array_column($this->fake->uploads, 'pid');
        $this->assertEqualsCanonicalizing(
            ['maverick-academy/lib/a', 'maverick-academy/site/logo'],
            $pids
        );

        $can = MediaMigrationMap::query()->where('old_public_id', 'maverick-academy/lib/a')->first();
        $this->assertSame('migrated', $can->status);
        $this->assertSame('maverick-academy/lib/a', $can->new_public_id);
        $this->assertSame($canonical->id, $can->media_asset_id);
        $this->assertStringContainsString('demo-dest', $can->new_url);
        $this->assertSame(1234, $can->bytes);

        // Same-hash dup shares the canonical upload (no second upload).
        $dupMap = MediaMigrationMap::query()->where('old_public_id', 'maverick-academy-local/lib/b')->first();
        $this->assertSame('shared', $dupMap->status);
        $this->assertSame($can->new_public_id, $dupMap->new_public_id);
        $this->assertSame($can->new_url, $dupMap->new_url);
        $this->assertSame($dup->id, $dupMap->media_asset_id);

        // Phase 1 purity: library rows untouched, source account untouched
        // (fake records only DEST calls — uploads + lookups).
        $this->assertSame($canUrl, $canonical->fresh()->url);
        $this->assertSame($dupUrl, $dup->fresh()->url);
    }

    public function test_r2_legacy_pid_normalizes_and_collisions_skip(): void
    {
        MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('c', 64),
            'cloudinary_public_id' => 'maverick-academy-local/lib/leg',
            'url' => 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy-local/lib/leg.jpg',
            'folder' => 'maverick-academy-local/lib',
        ]));

        MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('d', 64),
            'cloudinary_public_id' => 'maverick-academy/lib/clash',
            'url' => 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/clash.jpg',
        ]));

        // Second legacy file normalizing onto the SAME shared path.
        MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('e', 64),
            'cloudinary_public_id' => 'maverick-academy-testing/lib/leg',
            'url' => 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy-testing/lib/leg.jpg',
            'folder' => 'maverick-academy-testing/lib',
        ]));

        // Someone else's file already sits at the clash target.
        $this->fake->resources['maverick-academy/lib/clash'] = [
            'secure_url' => 'https://res.cloudinary.com/demo-dest/image/upload/v1/maverick-academy/lib/clash.jpg',
            'bytes' => 999,
            'public_id' => 'maverick-academy/lib/clash',
        ];

        $result = app(MediaMigrationService::class)->migrate();

        $this->assertSame(0, $result['failed']);

        $leg = MediaMigrationMap::query()->where('old_public_id', 'maverick-academy-local/lib/leg')->first();
        $this->assertSame('migrated', $leg->status);
        $this->assertSame('maverick-academy/lib/leg', $leg->new_public_id);

        $clash = MediaMigrationMap::query()->where('old_public_id', 'maverick-academy/lib/clash')->first();
        $this->assertSame('skipped', $clash->status);
        $this->assertSame('collision', $clash->reason);

        $claimed = MediaMigrationMap::query()->where('old_public_id', 'maverick-academy-testing/lib/leg')->first();
        $this->assertSame('skipped', $claimed->status);
        $this->assertSame('new-pid-claimed', $claimed->reason);

        $pids = array_column($this->fake->uploads, 'pid');
        $this->assertContains('maverick-academy/lib/leg', $pids);
        $this->assertNotContains('maverick-academy/lib/clash', $pids);
    }

    public function test_same_old_pid_maps_once(): void
    {
        MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('f', 64),
            'cloudinary_public_id' => 'maverick-academy/lib/reused',
            'url' => 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/reused.jpg',
        ]));

        MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('0', 64),
            'cloudinary_public_id' => 'maverick-academy/lib/reused',
            'url' => 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/reused.jpg',
        ]));

        app(MediaMigrationService::class)->migrate();

        $this->assertCount(1, $this->fake->uploads);
        $this->assertSame(1, MediaMigrationMap::query()->where('old_public_id', 'maverick-academy/lib/reused')->count());
    }

    public function test_settings_url_without_asset_row_gets_settings_mapping(): void
    {
        $this->createSetting(
            'migrate-test', 'banner',
            'https://res.cloudinary.com/demo-source/image/upload/w_500/v3/maverick-academy/site/thumb.jpg'
        );

        app(MediaMigrationService::class)->migrate();

        $map = MediaMigrationMap::query()->where('old_public_id', 'maverick-academy/site/thumb')->first();
        $this->assertSame('migrated', $map->status);
        $this->assertSame('settings', $map->source);
        $this->assertSame('settings:migrate-test.banner', $map->source_ref);
        $this->assertNull($map->media_asset_id);

        // Transformed source URL is fetched untransformed (never bake the
        // transform into the migrated file).
        $this->assertSame(
            'https://res.cloudinary.com/demo-source/image/upload/maverick-academy/site/thumb.jpg',
            $this->fake->uploads[0]['remote']
        );
    }

    public function test_videos_and_foreign_clouds_are_skipped(): void
    {
        MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('1', 64),
            'cloudinary_public_id' => 'maverick-academy/vids/v',
            'url' => 'https://res.cloudinary.com/demo-source/video/upload/v1/maverick-academy/vids/v.mp4',
            'mime_type' => 'video/mp4',
        ]));

        MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('2', 64),
            'cloudinary_public_id' => 'maverick-academy/lib/foreign',
            'url' => 'https://res.cloudinary.com/demo-foreign/image/upload/v1/maverick-academy/lib/foreign.jpg',
        ]));

        app(MediaMigrationService::class)->migrate();

        $video = MediaMigrationMap::query()->where('old_public_id', 'maverick-academy/vids/v')->first();
        $this->assertSame('skipped', $video->status);
        $this->assertSame('video-excluded', $video->reason);

        $foreign = MediaMigrationMap::query()->where('old_public_id', 'maverick-academy/lib/foreign')->first();
        $this->assertSame('skipped', $foreign->status);
        $this->assertSame('foreign-cloud', $foreign->reason);

        $this->assertSame([], $this->fake->uploads);
    }

    public function test_failed_uploads_retry_on_next_run(): void
    {
        MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('3', 64),
            'cloudinary_public_id' => 'maverick-academy/lib/retry',
            'url' => 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/retry.jpg',
        ]));

        $this->fake->failPids = ['maverick-academy/lib/retry'];

        $first = app(MediaMigrationService::class)->migrate();

        $this->assertSame(1, $first['failed']);
        $failed = MediaMigrationMap::query()->where('old_public_id', 'maverick-academy/lib/retry')->first();
        $this->assertSame('failed', $failed->status);
        $this->assertSame(1, $failed->attempts);

        $this->fake->failPids = [];

        $second = app(MediaMigrationService::class)->migrate();

        $this->assertSame(0, $second['failed']);
        $this->assertSame(1, $second['migrated']);
        $retried = MediaMigrationMap::query()->where('old_public_id', 'maverick-academy/lib/retry')->first();
        $this->assertSame('migrated', $retried->status);
        $this->assertSame(2, $retried->attempts);
    }

    public function test_rerun_is_idempotent(): void
    {
        $this->createPair();

        $first = app(MediaMigrationService::class)->migrate();
        $uploads = count($this->fake->uploads);
        $maps = MediaMigrationMap::query()->count();

        $this->assertGreaterThan(0, $first['migrated']);

        $second = app(MediaMigrationService::class)->migrate();

        $this->assertSame($uploads, count($this->fake->uploads));
        $this->assertSame($maps, MediaMigrationMap::query()->count());
        $this->assertSame(0, $second['migrated']);
        $this->assertSame(0, $second['failed']);
    }

    public function test_limit_batches_processing(): void
    {
        MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('4', 64),
            'cloudinary_public_id' => 'maverick-academy/lib/one',
            'url' => 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/one.jpg',
        ]));

        MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('5', 64),
            'cloudinary_public_id' => 'maverick-academy/lib/two',
            'url' => 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/two.jpg',
        ]));

        $first = app(MediaMigrationService::class)->migrate(limit: 1);

        $this->assertSame(1, $first['processed']);
        $this->assertSame(1, $first['migrated']);
        $this->assertNull($first['remaining']);

        $second = app(MediaMigrationService::class)->migrate();

        $this->assertSame(1, $second['migrated']);
        $this->assertSame(0, $second['failed']);
    }

    public function test_verify_passes_then_reports_missing_and_mismatched(): void
    {
        [$canonical] = $this->createPair();

        MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('6', 64),
            'cloudinary_public_id' => 'maverick-academy/lib/solo',
            'url' => 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/solo.jpg',
        ]));

        app(MediaMigrationService::class)->migrate();

        $ok = app(MediaMigrationService::class)->verify();

        $this->assertTrue($ok['pass']);
        $this->assertSame(0, $ok['missing_total']);
        $this->assertSame(0, $ok['mismatched_total']);

        // Tamper with DEST: drop the canonical file (shared dup points at the
        // same pid, so both mappings go missing), corrupt solo's bytes.
        unset($this->fake->resources['maverick-academy/lib/a']);
        $this->fake->resources['maverick-academy/lib/solo']['bytes'] = 555;

        $bad = app(MediaMigrationService::class)->verify();

        $this->assertFalse($bad['pass']);
        $this->assertSame(2, $bad['missing_total']);
        $this->assertSame(1, $bad['mismatched_total']);
        $this->assertSame(1, $bad['dest_total']);
        $this->assertSame($canonical->id, MediaMigrationMap::query()
            ->where('old_public_id', 'maverick-academy/lib/a')->first()->media_asset_id);
    }

    public function test_command_dry_run_and_verify(): void
    {
        $this->artisan('media:migrate-account', ['--dry-run' => true])
            ->assertSuccessful();

        $this->artisan('media:migrate-account', ['--verify' => true])
            ->assertSuccessful();
    }

    protected function assertGuardBlocks(string $needle): void
    {
        try {
            app(MediaMigrationService::class)->migrate();
            $this->fail('Expected guard RuntimeException containing: '.$needle);
        } catch (\RuntimeException $e) {
            $this->assertStringContainsString($needle, $e->getMessage());
        }
    }

    /**
     * Canonical (shared, created first) + legacy local dup, same hash,
     * different public_ids — the double-upload scenario R3 handles.
     *
     * @return array{0: MediaAsset, 1: MediaAsset}
     */
    protected function createPair(): array
    {
        $canonical = MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('a', 64),
            'cloudinary_public_id' => 'maverick-academy/lib/a',
            'url' => 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/a.jpg',
            'disk_env' => 'shared',
        ]));

        $dup = MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('a', 64),
            'cloudinary_public_id' => 'maverick-academy-local/lib/b',
            'url' => 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy-local/lib/b.jpg',
            'folder' => 'maverick-academy-local/lib',
            'disk_env' => 'local',
        ]));

        return [$canonical, $dup];
    }

    protected function createSetting(string $group, string $name, string $url): void
    {
        DB::table('settings')->insert([
            'group' => $group,
            'name' => $name,
            'locked' => false,
            'payload' => json_encode(['image' => $url]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    protected function assetAttrs(array $overrides = []): array
    {
        return array_merge([
            'hash' => str_repeat('e', 64),
            'original_name' => 'file.jpg',
            'mime_type' => 'image/jpeg',
            'size_bytes' => 100,
            'width' => 10,
            'height' => 10,
            'cloudinary_public_id' => 'maverick-academy/lib/file',
            'url' => 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/file.jpg',
            'folder' => 'maverick-academy/lib',
            'disk_env' => 'shared',
            'used' => false,
        ], $overrides);
    }
}
