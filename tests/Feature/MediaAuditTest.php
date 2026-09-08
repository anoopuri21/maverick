<?php

namespace Tests\Feature;

use App\Models\MediaAsset;
use App\Models\PartnerLogo;
use App\Services\MediaAuditService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MediaAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_guard_passes_in_shared_mode(): void
    {
        config()->set('services.cloudinary.env_folder', false);
        config()->set('services.cloudinary.disk_env', 'shared');

        $report = app(MediaAuditService::class)->audit();

        $this->assertTrue($report['guard']['pass']);
        $this->assertSame('maverick-academy', $report['guard']['base_folder']);
        $this->assertSame('shared', $report['guard']['disk_env']);
    }

    public function test_guard_fails_when_env_folder_is_on(): void
    {
        config()->set('services.cloudinary.env_folder', true);

        $report = app(MediaAuditService::class)->audit();

        $this->assertFalse($report['guard']['pass']);
    }

    public function test_audit_collects_urls_and_flags_transformed_legacy_and_duplicates(): void
    {
        // NOTE: Spatie settings migrations (database/settings/) seed ~26 URLs
        // on migrate, so this test asserts DELTAS, not absolute counts. Test
        // URLs use unique hosts/paths that can never collide with seeds.
        $before = app(MediaAuditService::class)->audit();

        $assetA = MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('a', 64),
            'cloudinary_public_id' => 'maverick-academy/lib/a',
            'url' => 'https://res.cloudinary.com/demo-old/image/upload/v1/maverick-academy/lib/a.jpg',
            'size_bytes' => 100,
        ]));

        // NOTE: (hash, disk_env) is UNIQUE, so a same-hash pair can only exist
        // across different disk_env values — exactly the legacy local+prod
        // double-upload scenario the migration has to handle.
        $assetB = MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('a', 64),
            'cloudinary_public_id' => 'maverick-academy/lib/b',
            'url' => 'https://res.cloudinary.com/demo-old/image/upload/v1/maverick-academy/lib/b.jpg',
            'size_bytes' => 100,
            'disk_env' => 'local',
        ]));

        MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('c', 64),
            'cloudinary_public_id' => 'maverick-academy-local/lib/c',
            'url' => 'https://res.cloudinary.com/demo-old/image/upload/v1/maverick-academy-local/lib/c.jpg',
            'folder' => 'maverick-academy-local/lib',
            'size_bytes' => 100,
        ]));

        $trashed = MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('d', 64),
            'cloudinary_public_id' => 'maverick-academy/lib/old',
            'url' => 'https://res.cloudinary.com/demo-old/image/upload/v1/maverick-academy/lib/old.jpg',
            'size_bytes' => null,
        ]));
        $trashed->delete();

        PartnerLogo::query()->create([
            'name' => 'Linked partner',
            'type' => 'alumni',
            'logo_url' => $assetA->url,
            'logo_url_asset_id' => $assetA->id,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        PartnerLogo::query()->create([
            'name' => 'External partner',
            'type' => 'alumni',
            'logo_url' => 'https://images.pexels.com/photos/99999999/audit-test-photo.jpg',
            'description' => '<p><img src="https://res.cloudinary.com/demo-old/image/upload/v9/maverick-academy/lib/emb.jpg"></p>',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $transformedUrl = 'https://res.cloudinary.com/demo-old/image/upload/w_500/v3/maverick-academy/lib/t.jpg';

        DB::table('settings')->insert([
            'group' => 'audit-test',
            'name' => 'hero',
            'locked' => false,
            'payload' => json_encode([
                'image' => $transformedUrl,
                'image_asset_id' => null,
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $after = app(MediaAuditService::class)->audit();

        // Library volume deltas (trashed rows included — scope is ALL).
        $this->assertSame($before['assets']['total'] + 4, $after['assets']['total']);
        $this->assertSame($before['assets']['trashed'] + 1, $after['assets']['trashed']);
        $this->assertSame($before['assets']['missing_url'], $after['assets']['missing_url']);
        $this->assertSame($before['assets']['missing_public_id'], $after['assets']['missing_public_id']);
        $this->assertSame($before['assets']['total_bytes'] + 300, $after['assets']['total_bytes']);
        $this->assertSame($before['assets']['unknown_bytes'] + 1, $after['assets']['unknown_bytes']);

        // Distinct URLs delta: 4 asset URLs + pexels + embedded + transformed.
        // Logo1 reuses asset A's URL, so it adds nothing.
        $this->assertSame($before['scan']['distinct_urls'] + 7, $after['scan']['distinct_urls']);
        $this->assertSame(
            ($before['cloud_names']['demo-old'] ?? 0) + 6,
            $after['cloud_names']['demo-old'] ?? 0
        );
        $this->assertSame(
            ($before['external_hosts']['images.pexels.com'] ?? 0) + 1,
            $after['external_hosts']['images.pexels.com'] ?? 0
        );
        $this->assertSame($before['unparsed_urls'], $after['unparsed_urls']);

        // Stored transformation URL flagged with its settings source.
        $this->assertSame($before['transformed']['total'] + 1, $after['transformed']['total']);

        $foundSource = null;
        foreach ($after['transformed']['samples'] as $sample) {
            if ($sample['url'] === $transformedUrl) {
                $foundSource = $sample['source'];
            }
        }
        $this->assertNotNull($foundSource);
        $this->assertStringContainsString('settings:audit-test.hero', $foundSource);

        // Legacy env-prefixed public_id flagged (URL + row agree on one entry).
        $this->assertSame($before['legacy_public_ids']['total'] + 1, $after['legacy_public_ids']['total']);
        $this->assertContains(
            'maverick-academy-local/lib/c',
            array_column($after['legacy_public_ids']['samples'], 'public_id')
        );

        // Same-hash pair reported as one merge candidate group.
        $this->assertSame($before['duplicate_groups']['total'] + 1, $after['duplicate_groups']['total']);
        $this->assertContains(
            [$assetA->id, $assetB->id],
            array_map(static fn (array $group) => $group['ids'], $after['duplicate_groups']['samples'])
        );
    }

    public function test_audit_command_runs_successfully(): void
    {
        $this->artisan('media:audit-cloudinary-urls')
            ->assertSuccessful();
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
