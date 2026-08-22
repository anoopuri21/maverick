<?php

namespace Tests\Feature;

use App\Models\MediaAsset;
use App\Models\PartnerLogo;
use App\Services\MediaFolderNormalizer;
use App\Services\MediaUsageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MediaLibraryMaintenanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_usage_service_marks_referenced_assets_as_used(): void
    {
        $used = MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('a', 64),
            'cloudinary_public_id' => 'maverick-academy/library/used',
            'url' => 'https://res.cloudinary.com/demo/image/upload/v1/maverick-academy/library/used.jpg',
            'original_name' => 'used.jpg',
        ]));

        $unused = MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('b', 64),
            'cloudinary_public_id' => 'maverick-academy/library/unused',
            'url' => 'https://res.cloudinary.com/demo/image/upload/v1/maverick-academy/library/unused.jpg',
            'original_name' => 'unused.jpg',
        ]));

        PartnerLogo::query()->create([
            'name' => 'Partner',
            'type' => 'alumni',
            'logo_url' => $used->url,
            'logo_url_asset_id' => $used->id,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $result = app(MediaUsageService::class)->refresh();

        $this->assertTrue($used->fresh()->used);
        $this->assertFalse($unused->fresh()->used);
        $this->assertContains($used->id, $result['referenced_ids']);
        $this->assertNotContains($unused->id, $result['referenced_ids']);
    }

    public function test_normalize_folders_repoints_legacy_local_paths(): void
    {
        config()->set('services.cloudinary.env_folder', false);
        config()->set('services.cloudinary.disk_env', 'shared');

        $asset = MediaAsset::query()->create($this->assetAttrs([
            'folder' => 'maverick-academy-local/library',
            'disk_env' => 'local',
            'cloudinary_public_id' => 'maverick-academy-local/library/logo',
            'url' => 'https://res.cloudinary.com/demo/image/upload/v1/maverick-academy-local/library/logo.jpg',
        ]));

        $result = app(MediaFolderNormalizer::class)->normalize(false);

        $asset->refresh();

        $this->assertSame('maverick-academy/library', $asset->folder);
        $this->assertSame('shared', $asset->disk_env);
        $this->assertSame($asset->url, 'https://res.cloudinary.com/demo/image/upload/v1/maverick-academy-local/library/logo.jpg');
        $this->assertGreaterThanOrEqual(1, $result['updated']);
    }

    public function test_media_clean_dry_run_does_not_delete_rows(): void
    {
        MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('c', 64),
            'cloudinary_public_id' => 'maverick-academy/library/orphan',
            'url' => 'https://res.cloudinary.com/demo/image/upload/v1/maverick-academy/library/orphan.jpg',
        ]));

        $this->artisan('media:clean')
            ->assertSuccessful();

        $this->assertSame(1, MediaAsset::query()->count());
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    protected function assetAttrs(array $overrides = []): array
    {
        return array_merge([
            'hash' => str_repeat('d', 64),
            'original_name' => 'file.jpg',
            'mime_type' => 'image/jpeg',
            'size_bytes' => 100,
            'width' => 10,
            'height' => 10,
            'cloudinary_public_id' => 'maverick-academy/library/file',
            'url' => 'https://res.cloudinary.com/demo/image/upload/v1/maverick-academy/library/file.jpg',
            'folder' => 'maverick-academy/library',
            'disk_env' => 'shared',
            'used' => false,
        ], $overrides);
    }
}
