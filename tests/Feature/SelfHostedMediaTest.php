<?php

namespace Tests\Feature;

use App\Models\MediaAsset;
use App\Models\PartnerLogo;
use App\Services\MediaLibraryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SelfHostedMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_library_upload_is_stored_on_the_public_disk(): void
    {
        Storage::fake('public');

        $asset = app(MediaLibraryService::class)->store(
            UploadedFile::fake()->image('campus.png', 120, 80),
            'programs/hero',
            'campus.png',
        );

        $this->assertSame('self-hosted', $asset->disk_env);
        $this->assertSame('public', $asset->storage_disk);
        $this->assertNotNull($asset->storage_path);
        $this->assertStringStartsWith('media/programs/hero/', $asset->storage_path);
        $this->assertSame('/storage/'.$asset->storage_path, $asset->url);
        $this->assertNull($asset->cloudinary_public_id);
        Storage::disk('public')->assertExists($asset->storage_path);
    }

    public function test_cloudinary_migration_rewrites_regular_and_json_database_urls_once(): void
    {
        Storage::fake('public');

        $legacyUrl = 'https://res.cloudinary.com/demo/image/upload/v1/maverick-academy/hero.png';
        $asset = $this->legacyAsset($legacyUrl, str_repeat('a', 64), 'maverick-academy/hero');

        PartnerLogo::query()->create([
            'name' => 'Partner',
            'type' => 'alumni',
            'logo_url' => $legacyUrl,
            'logo_url_asset_id' => $asset->id,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        DB::table('settings')->insert([
            'group' => 'recovery_test',
            'name' => 'images',
            'locked' => false,
            'payload' => json_encode(['image' => $legacyUrl]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Http::fake([
            $legacyUrl => Http::response($this->tinyPng(), 200, ['Content-Type' => 'image/png']),
        ]);

        $this->artisan('media:migrate-cloudinary', [
            '--confirm' => true,
            '--skip-remote' => true,
            '--skip-static' => true,
        ])->assertSuccessful();

        $asset->refresh();
        $this->assertSame($legacyUrl, $asset->source_url);
        $this->assertSame('/storage/media/cloudinary/'.hash('sha256', $legacyUrl).'.png', $asset->url);
        $this->assertSame('public', $asset->storage_disk);
        Storage::disk('public')->assertExists($asset->storage_path);

        $this->assertSame($asset->url, PartnerLogo::query()->firstOrFail()->logo_url);
        $payload = json_decode((string) DB::table('settings')->where('group', 'recovery_test')->value('payload'), true);
        $this->assertSame($asset->url, $payload['image']);
        Http::assertSentCount(1);
    }

    public function test_cloudinary_migration_reuses_one_local_asset_when_legacy_urls_have_identical_bytes(): void
    {
        Storage::fake('public');

        $firstUrl = 'https://res.cloudinary.com/old-account/image/upload/v1/maverick-academy/first.png';
        $secondUrl = 'https://res.cloudinary.com/old-account/image/upload/v2/maverick-academy/second.png';
        $first = $this->legacyAsset($firstUrl, str_repeat('a', 64), 'maverick-academy/first');
        $second = $this->legacyAsset($secondUrl, str_repeat('b', 64), 'maverick-academy/second');

        $logo = PartnerLogo::query()->create([
            'name' => 'Duplicate partner',
            'type' => 'alumni',
            'logo_url' => $secondUrl,
            'logo_url_asset_id' => $second->id,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Http::fake([
            $firstUrl => Http::response($this->tinyPng(), 200, ['Content-Type' => 'image/png']),
            $secondUrl => Http::response($this->tinyPng(), 200, ['Content-Type' => 'image/png']),
        ]);

        $this->artisan('media:migrate-cloudinary', [
            '--confirm' => true,
            '--skip-remote' => true,
            '--skip-static' => true,
        ])->assertSuccessful();

        $first->refresh();
        $logo->refresh();
        $second = MediaAsset::withTrashed()->findOrFail($second->id);

        $this->assertNotNull($second->deleted_at);
        $this->assertSame($secondUrl, $second->source_url);
        $this->assertSame($first->id, $logo->logo_url_asset_id);
        $this->assertSame($first->url, $logo->logo_url);
        $this->assertSame($first->url, $logo->getMediaUrl('logo_url'));
        Storage::disk('public')->assertExists($first->storage_path);
        Http::assertSentCount(2);
    }

    private function legacyAsset(string $url, string $hash, string $publicId): MediaAsset
    {
        return MediaAsset::query()->create([
            'hash' => $hash,
            'original_name' => basename(parse_url($url, PHP_URL_PATH) ?: 'legacy-image.png'),
            'mime_type' => 'image/png',
            'size_bytes' => 1,
            'width' => 1,
            'height' => 1,
            'cloudinary_public_id' => $publicId,
            'url' => $url,
            'folder' => 'maverick-academy',
            'disk_env' => 'shared',
            'used' => false,
            'is_duplicate' => false,
        ]);
    }

    private function tinyPng(): string
    {
        return base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQIHWP4z8DwHwAFgAI/ScL6NwAAAABJRU5ErkJggg==');
    }
}
