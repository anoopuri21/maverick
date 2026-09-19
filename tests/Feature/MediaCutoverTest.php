<?php

namespace Tests\Feature;

use App\Models\MediaAsset;
use App\Models\MediaMigrationMap;
use App\Models\PartnerLogo;
use App\Services\MediaCutoverService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MediaCutoverTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.cloudinary.source_cloud_name', 'demo-source');
        config()->set('services.cloudinary.env_folder', false);
        config()->set('services.cloudinary.disk_env', 'shared');
        // NOTE: no DEST credentials on purpose — cutover makes zero API
        // calls, so it must work without them (see test below).
    }

    public function test_dry_run_changes_nothing(): void
    {
        [$normal] = $this->createLibrary();
        $beforeUrl = $normal->url;

        $logo = PartnerLogo::query()->create([
            'name' => 'Linked partner',
            'type' => 'alumni',
            'logo_url' => $normal->url,
            'logo_url_asset_id' => $normal->id,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $result = app(MediaCutoverService::class)->cutover(dryRun: true);

        $this->assertTrue($result['dry_run']);
        $this->assertNull($result['residue']);
        $this->assertGreaterThan(0, $result['assets']['updated']);
        $this->assertGreaterThan(0, $result['fields']['cells_updated']);

        $this->assertSame($beforeUrl, $normal->fresh()->url);
        $this->assertSame($beforeUrl, $logo->fresh()->logo_url);
    }

    public function test_confirm_updates_asset_urls_and_r2_pid_folder(): void
    {
        [$normal, $legacy, $shared] = $this->createLibrary();

        $result = app(MediaCutoverService::class)->cutover(dryRun: false);

        $this->assertSame(3, $result['assets']['updated']);
        $this->assertSame(0, $result['assets']['skipped']);

        // Normal row: url only, pid + folder untouched.
        $normal->refresh();
        $this->assertSame('https://res.cloudinary.com/demo-dest/image/upload/v777/maverick-academy/lib/n.jpg', $normal->url);
        $this->assertSame('maverick-academy/lib/n', $normal->cloudinary_public_id);
        $this->assertSame('maverick-academy/lib', $normal->folder);

        // [R2] row: url + pid + folder all follow the mapping.
        $legacy->refresh();
        $this->assertSame('https://res.cloudinary.com/demo-dest/image/upload/v777/maverick-academy/lib/r.jpg', $legacy->url);
        $this->assertSame('maverick-academy/lib/r', $legacy->cloudinary_public_id);
        $this->assertSame('maverick-academy/lib', $legacy->folder);

        // Shared (R3) row: url follows the canonical file, but the pid column
        // stays its own — pointing it at the canonical pid would violate
        // UNIQUE(cloudinary_public_id).
        $shared->refresh();
        $this->assertSame('https://res.cloudinary.com/demo-dest/image/upload/v777/maverick-academy/lib/n.jpg', $shared->url);
        $this->assertSame('maverick-academy-local/lib/d', $shared->cloudinary_public_id);
    }

    public function test_unmapped_assets_skipped_with_reasons(): void
    {
        $unmapped = MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('1', 64),
            'cloudinary_public_id' => 'maverick-academy/lib/nomap',
            'url' => 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/nomap.jpg',
        ]));

        $failed = $this->makeAssetAndMap(
            ['hash' => str_repeat('2', 64), 'cloudinary_public_id' => 'maverick-academy/lib/fail',
                'url' => 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/fail.jpg'],
            ['status' => 'failed', 'reason' => 'upload-error', 'new_public_id' => null, 'new_url' => null, 'attempts' => 1]
        );

        $skipped = $this->makeAssetAndMap(
            ['hash' => str_repeat('3', 64), 'cloudinary_public_id' => 'maverick-academy/lib/skip',
                'url' => 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/skip.jpg'],
            ['status' => 'skipped', 'reason' => 'collision', 'new_public_id' => null, 'new_url' => null]
        );

        $result = app(MediaCutoverService::class)->cutover(dryRun: false);

        $this->assertSame($unmapped->url, $unmapped->fresh()->url);
        $this->assertSame($failed->url, $failed->fresh()->url);
        $this->assertSame($skipped->url, $skipped->fresh()->url);

        $this->assertSame(1, $result['assets']['reasons']['unmapped'] ?? 0);
        $this->assertSame(1, $result['assets']['reasons']['mapping-failed'] ?? 0);
        $this->assertSame(1, $result['assets']['reasons']['mapping-skipped:collision'] ?? 0);
    }

    public function test_fields_updated_via_linkage(): void
    {
        [$normal] = $this->createLibrary();
        $newUrl = 'https://res.cloudinary.com/demo-dest/image/upload/v777/maverick-academy/lib/n.jpg';
        $variant = 'https://res.cloudinary.com/demo-source/image/upload/w_500/v3/maverick-academy/lib/n.jpg';
        $other = 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/other.jpg';
        $unmappedEmbed = 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/ghost.jpg';

        PartnerLogo::query()->create(['name' => 'Plain linked', 'type' => 'alumni',
            'logo_url' => $normal->url, 'logo_url_asset_id' => $normal->id, 'is_active' => true, 'sort_order' => 1]);
        PartnerLogo::query()->create(['name' => 'Variant linked', 'type' => 'alumni',
            'logo_url' => $variant, 'logo_url_asset_id' => $normal->id, 'is_active' => true, 'sort_order' => 2]);
        PartnerLogo::query()->create(['name' => 'Already current', 'type' => 'alumni',
            'logo_url' => $newUrl, 'logo_url_asset_id' => $normal->id, 'is_active' => true, 'sort_order' => 3]);
        PartnerLogo::query()->create(['name' => 'Null id mappable', 'type' => 'alumni',
            'logo_url' => $normal->url, 'logo_url_asset_id' => null, 'is_active' => true, 'sort_order' => 4]);
        PartnerLogo::query()->create(['name' => 'Mismatch', 'type' => 'alumni',
            'logo_url' => $other, 'logo_url_asset_id' => $normal->id, 'is_active' => true, 'sort_order' => 5]);
        PartnerLogo::query()->create(['name' => 'External kept', 'type' => 'alumni',
            'logo_url' => 'https://images.pexels.com/photos/99999999/keep.jpg', 'logo_url_asset_id' => $normal->id,
            'is_active' => true, 'sort_order' => 6]);
        PartnerLogo::query()->create(['name' => 'Foreign kept', 'type' => 'alumni',
            'logo_url' => 'https://res.cloudinary.com/demo-foreign/image/upload/v1/x/y.jpg', 'logo_url_asset_id' => $normal->id,
            'is_active' => true, 'sort_order' => 7]);
        PartnerLogo::query()->create(['name' => 'Blank filled', 'type' => 'alumni',
            'logo_url' => '', 'logo_url_asset_id' => $normal->id, 'is_active' => true, 'sort_order' => 8]);
        PartnerLogo::query()->create(['name' => 'Embeds', 'type' => 'alumni',
            'logo_url' => $newUrl,
            'description' => '<p><img src="'.$normal->url.'"></p><p><img src="'.$unmappedEmbed.'"></p>',
            'is_active' => true, 'sort_order' => 9]);

        $result = app(MediaCutoverService::class)->cutover(dryRun: false);

        $byName = PartnerLogo::query()->whereIn('name', [
            'Plain linked', 'Variant linked', 'Already current', 'Null id mappable',
            'Mismatch', 'External kept', 'Foreign kept', 'Blank filled', 'Embeds',
        ])->pluck('logo_url', 'name');

        $this->assertSame($newUrl, $byName['Plain linked']);
        // Transform preserved, rebuilt on the new pid (version dropped).
        $this->assertSame(
            'https://res.cloudinary.com/demo-dest/image/upload/w_500/maverick-academy/lib/n.jpg',
            $byName['Variant linked']
        );
        $this->assertSame($newUrl, $byName['Already current']);
        $this->assertSame($newUrl, $byName['Null id mappable']);
        $this->assertSame($newUrl, $byName['Mismatch']);
        $this->assertSame('https://images.pexels.com/photos/99999999/keep.jpg', $byName['External kept']);
        $this->assertSame('https://res.cloudinary.com/demo-foreign/image/upload/v1/x/y.jpg', $byName['Foreign kept']);
        $this->assertSame($newUrl, $byName['Blank filled']);

        $desc = PartnerLogo::query()->where('name', 'Embeds')->first()->description;
        $this->assertStringContainsString($newUrl, $desc);
        $this->assertStringContainsString($unmappedEmbed, $desc);
        $this->assertStringNotContainsString($normal->url, $desc);

        // 4 logo updates + blank fill + 1 embed = 6 cells; linkage-only
        // counters are exact (seeds carry no numeric asset ids).
        $this->assertSame(6, $result['fields']['cells_updated']);
        $this->assertSame(1, $result['fields']['repaired']);
        $this->assertSame(1, $result['fields']['filled']);
        $this->assertGreaterThanOrEqual(1, $result['fields']['current']);
        $this->assertGreaterThanOrEqual(1, $result['fields']['reasons']['external-preserved'] ?? 0);
        $this->assertGreaterThanOrEqual(1, $result['fields']['reasons']['foreign-preserved'] ?? 0);
        $this->assertGreaterThanOrEqual(1, $result['fields']['reasons']['unmapped-url'] ?? 0);

        // Regression: the mapping table itself is never scanned/rewritten
        // (its old_url audit trail survives the cutover).
        $map = MediaMigrationMap::query()->where('old_public_id', 'maverick-academy/lib/n')->first();
        $this->assertStringContainsString('demo-source', $map->old_url);
        $this->assertStringContainsString('demo-dest', $map->new_url);
    }

    public function test_settings_json_linkage_and_legacy(): void
    {
        [$normal] = $this->createLibrary();
        $newA = 'https://res.cloudinary.com/demo-dest/image/upload/v777/maverick-academy/lib/n.jpg';

        $assetB = $this->makeAssetAndMap(
            ['hash' => str_repeat('4', 64), 'cloudinary_public_id' => 'maverick-academy/lib/b',
                'url' => 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/b.jpg'],
            ['new_url' => 'https://res.cloudinary.com/demo-dest/image/upload/v777/maverick-academy/lib/b.jpg']
        );
        $newB = 'https://res.cloudinary.com/demo-dest/image/upload/v777/maverick-academy/lib/b.jpg';

        // Legacy direct URL (settings-source mapping, no asset row).
        MediaMigrationMap::query()->create([
            'old_public_id' => 'maverick-academy/site/legacy',
            'new_public_id' => 'maverick-academy/site/legacy',
            'old_url' => 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/site/legacy.jpg',
            'new_url' => 'https://res.cloudinary.com/demo-dest/image/upload/v777/maverick-academy/site/legacy.jpg',
            'bytes' => 1234,
            'media_asset_id' => null,
            'source' => 'settings',
            'source_ref' => 'settings:cutover-test.page',
            'status' => 'migrated',
            'attempts' => 1,
        ]);

        DB::table('settings')->insert([
            'group' => 'cutover-test',
            'name' => 'page',
            'locked' => false,
            'payload' => json_encode([
                'image' => $normal->url,
                'image_asset_id' => $normal->id,
                'nested' => [
                    'thumb' => 'https://res.cloudinary.com/demo-source/image/upload/w_300/v2/maverick-academy/lib/b.jpg',
                    'image_asset_id' => $assetB->id,
                ],
                'legacy' => 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/site/legacy.jpg',
                'ext' => 'https://images.pexels.com/photos/99999999/keep.jpg',
                'ghost' => 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/ghost.jpg',
                'note' => 'plain',
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $result = app(MediaCutoverService::class)->cutover(dryRun: false);

        $payload = json_decode(DB::table('settings')->where('group', 'cutover-test')->value('payload'), true);

        $this->assertSame($newA, $payload['image']);
        $this->assertSame($normal->id, $payload['image_asset_id']);
        $this->assertSame(
            'https://res.cloudinary.com/demo-dest/image/upload/w_300/maverick-academy/lib/b.jpg',
            $payload['nested']['thumb']
        );
        $this->assertSame($assetB->id, $payload['nested']['image_asset_id']);
        $this->assertSame(
            'https://res.cloudinary.com/demo-dest/image/upload/v777/maverick-academy/site/legacy.jpg',
            $payload['legacy']
        );
        $this->assertSame('https://images.pexels.com/photos/99999999/keep.jpg', $payload['ext']);
        $this->assertSame(
            'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/ghost.jpg',
            $payload['ghost']
        );
        $this->assertSame('plain', $payload['note']);

        $this->assertSame(1, $result['settings']['rows_updated']);
        $this->assertSame(3, $result['settings']['urls_updated']);
        $this->assertSame(1, $result['settings']['reasons']['unmapped-url'] ?? 0);
    }

    public function test_residue_zero_when_fully_mapped_and_videos_excluded(): void
    {
        $this->createLibrary();

        // Videos never migrate — their source urls stay and must NOT fail
        // the cutover (residue tracks them separately).
        $video = MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('5', 64),
            'cloudinary_public_id' => 'maverick-academy/vids/v',
            'url' => 'https://res.cloudinary.com/demo-source/video/upload/v1/maverick-academy/vids/v.mp4',
            'mime_type' => 'video/mp4',
        ]));

        $result = app(MediaCutoverService::class)->cutover(dryRun: false);

        $this->assertSame(0, $result['residue']['image_total']);
        $this->assertSame(1, $result['residue']['video_total']);
        $this->assertSame($video->url, $video->fresh()->url);
    }

    public function test_command_succeeds_when_residue_clean_and_fails_on_images(): void
    {
        $this->createLibrary();

        $this->artisan('media:cutover-account')
            ->assertSuccessful();

        $code = Artisan::call('media:cutover-account', ['--confirm' => true]);

        $this->assertSame(0, $code);

        // Now strand a source-cloud image with no mapping.
        MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('6', 64),
            'cloudinary_public_id' => 'maverick-academy/lib/stranded',
            'url' => 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/stranded.jpg',
        ]));

        $code = Artisan::call('media:cutover-account', ['--confirm' => true]);

        $this->assertSame(1, $code);
    }

    public function test_rerun_is_idempotent(): void
    {
        $this->createLibrary();

        $first = app(MediaCutoverService::class)->cutover(dryRun: false);

        $this->assertSame(3, $first['assets']['updated']);

        $second = app(MediaCutoverService::class)->cutover(dryRun: false);

        $this->assertSame(0, $second['assets']['updated']);
        $this->assertSame(3, $second['assets']['current']);
        $this->assertSame(0, $second['assets']['skipped']);
        $this->assertSame(0, $second['fields']['cells_updated']);
        $this->assertSame(0, $second['residue']['image_total']);
    }

    public function test_guard_fails_when_env_folder_enabled(): void
    {
        config()->set('services.cloudinary.env_folder', true);

        try {
            app(MediaCutoverService::class)->cutover();
            $this->fail('Expected R1 guard RuntimeException.');
        } catch (\RuntimeException $e) {
            $this->assertStringContainsString('R1 guard', $e->getMessage());
        }
    }

    public function test_works_without_dest_credentials(): void
    {
        config()->set('services.cloudinary.dest_cloud_name', null);
        config()->set('services.cloudinary.dest_api_key', null);
        config()->set('services.cloudinary.dest_api_secret', null);

        $this->createLibrary();

        $result = app(MediaCutoverService::class)->cutover(dryRun: true);

        $this->assertTrue($result['dry_run']);
        $this->assertSame(3, $result['assets']['updated']);
    }

    /**
     * Normal (same pid) + legacy R2 (normalized pid) + shared R3 dup
     * (canonical's new url, own pid) — each with its mapping row.
     *
     * @return array{0: MediaAsset, 1: MediaAsset, 2: MediaAsset}
     */
    protected function createLibrary(): array
    {
        $normal = $this->makeAssetAndMap(
            ['hash' => str_repeat('a', 64), 'cloudinary_public_id' => 'maverick-academy/lib/n',
                'url' => 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/n.jpg'],
            ['new_url' => 'https://res.cloudinary.com/demo-dest/image/upload/v777/maverick-academy/lib/n.jpg']
        );

        $legacy = $this->makeAssetAndMap(
            ['hash' => str_repeat('b', 64), 'cloudinary_public_id' => 'maverick-academy-local/lib/r',
                'url' => 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy-local/lib/r.jpg',
                'folder' => 'maverick-academy-local/lib', 'disk_env' => 'local'],
            ['new_public_id' => 'maverick-academy/lib/r',
                'new_url' => 'https://res.cloudinary.com/demo-dest/image/upload/v777/maverick-academy/lib/r.jpg']
        );

        $shared = $this->makeAssetAndMap(
            ['hash' => str_repeat('a', 64), 'cloudinary_public_id' => 'maverick-academy-local/lib/d',
                'url' => 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy-local/lib/d.jpg',
                'folder' => 'maverick-academy-local/lib', 'disk_env' => 'local'],
            ['status' => 'shared', 'reason' => 'same-hash:maverick-academy/lib/n',
                'new_public_id' => 'maverick-academy/lib/n',
                'new_url' => 'https://res.cloudinary.com/demo-dest/image/upload/v777/maverick-academy/lib/n.jpg']
        );

        return [$normal, $legacy, $shared];
    }

    protected function makeAssetAndMap(array $asset, array $map): MediaAsset
    {
        $row = MediaAsset::query()->create($this->assetAttrs($asset));

        MediaMigrationMap::query()->create(array_merge([
            'old_public_id' => $row->cloudinary_public_id,
            'new_public_id' => $row->cloudinary_public_id,
            'old_url' => $row->url,
            'new_url' => 'https://res.cloudinary.com/demo-dest/image/upload/v777/'.$row->cloudinary_public_id.'.jpg',
            'bytes' => 1234,
            'media_asset_id' => $row->id,
            'source' => 'asset',
            'source_ref' => 'media_assets#'.$row->id,
            'status' => 'migrated',
            'reason' => null,
            'attempts' => 1,
        ], $map));

        return $row;
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
