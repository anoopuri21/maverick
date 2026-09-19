<?php

namespace Tests\Feature;

use App\Models\MediaAsset;
use App\Models\MediaRecycleLog;
use App\Models\PartnerLogo;
use App\Services\MediaMergeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MediaMergeTest extends TestCase
{
    use RefreshDatabase;

    public function test_dry_run_reports_without_changing_data(): void
    {
        [$canonical, $dup] = $this->createPairWithReferences();

        $result = app(MediaMergeService::class)->merge(dryRun: true);

        $this->assertTrue($result['dry_run']);
        $this->assertSame(1, $result['merged']);
        $this->assertSame([], $result['errors']);
        $this->assertSame($canonical->id, $result['details'][0]['canonical_id']);
        $this->assertSame($dup->id, $result['details'][0]['dup_id']);
        $this->assertGreaterThanOrEqual(1, $result['details'][0]['refs']['fk']);

        // Nothing changed.
        $this->assertFalse($dup->fresh()->trashed());
        $this->assertSame($dup->id, PartnerLogo::query()->where('name', 'Linked partner')->first()->logo_url_asset_id);
    }

    public function test_confirm_repoints_all_reference_types_and_soft_deletes_dup(): void
    {
        [$canonical, $dup] = $this->createPairWithReferences();

        $result = app(MediaMergeService::class)->merge(dryRun: false);

        $this->assertSame(1, $result['merged']);
        $this->assertSame([], $result['errors']);
        $this->assertTrue($dup->fresh()->trashed());
        $this->assertFalse($canonical->fresh()->trashed());

        // FK column + denormalized URL column repointed.
        $logo = PartnerLogo::query()->where('name', 'Linked partner')->first();
        $this->assertSame($canonical->id, $logo->logo_url_asset_id);
        $this->assertSame($canonical->url, $logo->logo_url);

        // Rich-text embeds: exact URL swapped, transformed variant rebuilt on
        // the canonical public_id (version dropped, transform kept).
        $rebuilt = 'https://res.cloudinary.com/demo-old/image/upload/w_500/maverick-academy/lib/a.jpg';
        $text = PartnerLogo::query()->where('name', 'Rich text partner')->first()->description;
        $this->assertStringContainsString($canonical->url, $text);
        $this->assertStringContainsString($rebuilt, $text);
        $this->assertStringNotContainsString($dup->url, $text);
        $this->assertStringNotContainsString('maverick-academy-local', $text);

        // Settings JSON: asset id + URL + nested transformed URL repointed.
        $payload = json_decode(DB::table('settings')->where('group', 'merge-test')->value('payload'), true);
        $this->assertSame($canonical->url, $payload['image']);
        $this->assertSame($canonical->id, $payload['image_asset_id']);
        $this->assertSame($rebuilt, $payload['nested']['thumb']);
        $this->assertSame('plain', $payload['note']);

        // Reference counts for our fixtures (ID-based counts use >= : future
        // seeds could add numeric asset_ids; URL-based counts are exact).
        $refs = $result['details'][0]['refs'];
        $this->assertGreaterThanOrEqual(1, $refs['fk']);
        $this->assertSame(2, $refs['urls']);
        $this->assertSame(2, $refs['transformed']);
        $this->assertGreaterThanOrEqual(1, $refs['json_fk']);
        $this->assertSame(1, $refs['json_urls']);
    }

    public function test_skipped_tables_and_media_assets_self_rows_are_never_touched(): void
    {
        // Regression: schema-qualified table names ("main.settings" on SQLite)
        // once bypassed the skip list, so media_assets scanned itself (+1 urls)
        // and skipped tables like media_recycle_logs got rewritten.
        [$canonical, $dup] = $this->createPairWithReferences();
        $dupUrl = $dup->url;

        $log = MediaRecycleLog::query()->create([
            'media_asset_id' => $dup->id,
            'cloudinary_public_id' => 'maverick-academy/lib/recycled',
            'url' => $dupUrl,
            'hash' => str_repeat('9', 64),
            'disk_env' => 'shared',
        ]);

        $result = app(MediaMergeService::class)->merge(dryRun: false);

        $this->assertSame(1, $result['merged']);
        $this->assertSame([], $result['errors']);

        // Skipped table untouched (neither its FK nor its URL rewritten).
        $this->assertSame($dupUrl, $log->fresh()->url);
        $this->assertSame($dup->id, $log->fresh()->media_asset_id);

        // The dup's own media_assets row keeps its URL when soft-deleted.
        $this->assertTrue($dup->fresh()->trashed());
        $this->assertSame($dupUrl, $dup->fresh()->url);
        $this->assertFalse($canonical->fresh()->trashed());

        // URL counts stay exact (no self-scan, no skipped-table leakage).
        $this->assertSame(2, $result['details'][0]['refs']['urls']);
    }

    public function test_canonical_prefers_shared_disk_env_over_lower_id(): void
    {
        // Local row created FIRST (lower id) — shared row must still win.
        $local = MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('f', 64),
            'cloudinary_public_id' => 'maverick-academy-local/lib/f',
            'url' => 'https://res.cloudinary.com/demo-old/image/upload/v1/maverick-academy-local/lib/f.jpg',
            'folder' => 'maverick-academy-local/lib',
            'disk_env' => 'local',
        ]));

        $shared = MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('f', 64),
            'cloudinary_public_id' => 'maverick-academy/lib/f',
            'url' => 'https://res.cloudinary.com/demo-old/image/upload/v1/maverick-academy/lib/f.jpg',
            'folder' => 'maverick-academy/lib',
            'disk_env' => 'shared',
        ]));

        $this->assertLessThan($shared->id, $local->id);

        PartnerLogo::query()->create([
            'name' => 'Local ref',
            'type' => 'alumni',
            'logo_url' => $local->url,
            'logo_url_asset_id' => $local->id,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $result = app(MediaMergeService::class)->merge(dryRun: false);

        $this->assertSame(1, $result['merged']);
        $this->assertSame($shared->id, $result['details'][0]['canonical_id']);
        $this->assertTrue($local->fresh()->trashed());
        $this->assertFalse($shared->fresh()->trashed());
        $this->assertSame($shared->id, PartnerLogo::query()->where('name', 'Local ref')->first()->logo_url_asset_id);
    }

    public function test_merge_is_idempotent(): void
    {
        $this->createPairWithReferences();

        $this->assertSame(1, app(MediaMergeService::class)->merge(dryRun: false)['merged']);
        $this->assertSame(0, app(MediaMergeService::class)->merge(dryRun: false)['merged']);
    }

    public function test_merge_command_dry_run_and_confirm(): void
    {
        [$canonical, $dup] = $this->createPairWithReferences();

        $this->artisan('media:merge-duplicates')
            ->assertSuccessful();

        $this->assertFalse($dup->fresh()->trashed());

        $this->artisan('media:merge-duplicates', ['--confirm' => true])
            ->assertSuccessful();

        $this->assertTrue($dup->fresh()->trashed());
        $this->assertFalse($canonical->fresh()->trashed());
    }

    /**
     * Canonical (shared) + duplicate (local, -local public_id) + one reference
     * of every kind: FK column, URL column, rich-text embeds (exact +
     * transformed), settings JSON (id + URL + nested transformed).
     *
     * @return array{0: MediaAsset, 1: MediaAsset}
     */
    protected function createPairWithReferences(): array
    {
        $canonical = MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('a', 64),
            'cloudinary_public_id' => 'maverick-academy/lib/a',
            'url' => 'https://res.cloudinary.com/demo-old/image/upload/v1/maverick-academy/lib/a.jpg',
            'disk_env' => 'shared',
        ]));

        $dup = MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('a', 64),
            'cloudinary_public_id' => 'maverick-academy-local/lib/b',
            'url' => 'https://res.cloudinary.com/demo-old/image/upload/v1/maverick-academy-local/lib/b.jpg',
            'folder' => 'maverick-academy-local/lib',
            'disk_env' => 'local',
        ]));

        $variant = 'https://res.cloudinary.com/demo-old/image/upload/w_500/v3/maverick-academy-local/lib/b.jpg';

        PartnerLogo::query()->create([
            'name' => 'Linked partner',
            'type' => 'alumni',
            'logo_url' => $dup->url,
            'logo_url_asset_id' => $dup->id,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        PartnerLogo::query()->create([
            'name' => 'Rich text partner',
            'type' => 'alumni',
            'logo_url' => $canonical->url,
            'description' => '<p><img src="'.$dup->url.'"></p><p><img src="'.$variant.'"></p>',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        DB::table('settings')->insert([
            'group' => 'merge-test',
            'name' => 'hero',
            'locked' => false,
            'payload' => json_encode([
                'image' => $dup->url,
                'image_asset_id' => $dup->id,
                'nested' => ['thumb' => $variant],
                'note' => 'plain',
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [$canonical, $dup];
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
            'url' => 'https://res.cloudinary.com/demo-old/image/upload/v1/maverick-academy/lib/file.jpg',
            'folder' => 'maverick-academy/lib',
            'disk_env' => 'shared',
            'used' => false,
        ], $overrides);
    }
}
