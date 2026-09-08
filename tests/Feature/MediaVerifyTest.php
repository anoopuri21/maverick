<?php

namespace Tests\Feature;

use App\Models\MediaAsset;
use App\Models\PartnerLogo;
use App\Services\MediaVerifyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MediaVerifyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('media.verify_throttle_ms', 0);
        config()->set('media.verify_skip_hosts', []);
    }

    public function test_all_ok_when_every_url_reachable(): void
    {
        Http::fake(['*' => Http::response('', 200)]);

        $u1 = 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/a.jpg';
        $u2 = 'https://images.pexels.com/photos/99999999/verify-ok.jpg';
        $u3 = 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/site/s.jpg';

        MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('a', 64),
            'cloudinary_public_id' => 'maverick-academy/lib/a',
            'url' => $u1,
        ]));

        PartnerLogo::query()->create([
            'name' => 'External partner', 'type' => 'alumni', 'logo_url' => $u2,
            'is_active' => true, 'sort_order' => 1,
        ]);

        $this->createSetting('verify-test', 'hero', ['image' => $u3]);

        $result = app(MediaVerifyService::class)->verify();

        $this->assertSame(0, $result['failed_live_total']);
        $this->assertSame(0, $result['failed_trashed_total']);
        $this->assertGreaterThanOrEqual(3, $result['checked']);
        $this->assertSame($result['checked'], $result['ok']);

        Http::assertSent(fn (Request $request) => $request->url() === $u1);
        Http::assertSent(fn (Request $request) => $request->url() === $u2);
        Http::assertSent(fn (Request $request) => $request->url() === $u3);
    }

    public function test_live_failure_reported_with_source(): void
    {
        $bad = 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/bad.jpg';

        Http::fake(function (Request $request) use ($bad) {
            return $request->url() === $bad
                ? Http::response('', 404)
                : Http::response('', 200);
        });

        $asset = MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('b', 64),
            'cloudinary_public_id' => 'maverick-academy/lib/bad',
            'url' => $bad,
        ]));

        $result = app(MediaVerifyService::class)->verify();

        $this->assertSame(1, $result['failed_live_total']);
        $this->assertSame($bad, $result['failed_live'][0]['url']);
        $this->assertSame('media_assets#'.$asset->id, $result['failed_live'][0]['source']);
        $this->assertSame(404, $result['failed_live'][0]['status']);
    }

    public function test_trashed_only_failure_warns_without_failing_command(): void
    {
        $url = 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/gone.jpg';

        Http::fake(function (Request $request) use ($url) {
            return $request->url() === $url
                ? Http::response('', 404)
                : Http::response('', 200);
        });

        $trashed = MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('c', 64),
            'cloudinary_public_id' => 'maverick-academy/lib/gone',
            'url' => $url,
        ]));
        $trashed->delete();

        $result = app(MediaVerifyService::class)->verify();

        $this->assertSame(0, $result['failed_live_total']);
        $this->assertSame(1, $result['failed_trashed_total']);

        $this->artisan('media:verify-urls')
            ->assertSuccessful();
    }

    public function test_videos_are_skipped_not_requested(): void
    {
        Http::fake(['*' => Http::response('', 200)]);

        $videoUrl = 'https://res.cloudinary.com/demo-source/video/upload/v1/maverick-academy/vids/v.mp4';

        MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('d', 64),
            'cloudinary_public_id' => 'maverick-academy/vids/v',
            'url' => $videoUrl,
            'mime_type' => 'video/mp4',
        ]));

        $mp4 = 'https://cdn.example.com/clip.mp4';

        PartnerLogo::query()->create([
            'name' => 'Clip partner', 'type' => 'alumni', 'logo_url' => $mp4,
            'is_active' => true, 'sort_order' => 1,
        ]);

        $result = app(MediaVerifyService::class)->verify();

        $this->assertGreaterThanOrEqual(2, $result['skipped']['video']);
        Http::assertNotSent(fn (Request $request) => $request->url() === $videoUrl);
        Http::assertNotSent(fn (Request $request) => $request->url() === $mp4);
    }

    public function test_allow_host_skips_matching_hosts(): void
    {
        Http::fake(['*' => Http::response('', 200)]);

        config()->set('media.verify_skip_hosts', ['skipme.example']);

        $pexels = 'https://images.pexels.com/photos/99999999/allowed.jpg';
        $other = 'https://skipme.example/pic.jpg';

        PartnerLogo::query()->create([
            'name' => 'Pexels partner', 'type' => 'alumni', 'logo_url' => $pexels,
            'is_active' => true, 'sort_order' => 1,
        ]);
        PartnerLogo::query()->create([
            'name' => 'Skipme partner', 'type' => 'alumni', 'logo_url' => $other,
            'is_active' => true, 'sort_order' => 2,
        ]);

        $result = app(MediaVerifyService::class)->verify(['images.pexels.com']);

        $this->assertGreaterThanOrEqual(2, $result['skipped']['allowed_host']);
        $this->assertEqualsCanonicalizing(
            ['skipme.example', 'images.pexels.com'],
            $result['allowed_hosts']
        );
        Http::assertNotSent(fn (Request $request) => $request->url() === $pexels);
        Http::assertNotSent(fn (Request $request) => $request->url() === $other);
    }

    public function test_head_405_falls_back_to_get(): void
    {
        Http::fake(function (Request $request) {
            return $request->method() === 'HEAD'
                ? Http::response('', 405)
                : Http::response('', 200);
        });

        $url = 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/nohead.jpg';

        MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('e', 64),
            'cloudinary_public_id' => 'maverick-academy/lib/nohead',
            'url' => $url,
        ]));

        $result = app(MediaVerifyService::class)->verify();

        $this->assertSame(0, $result['failed_live_total']);
        Http::assertSent(fn (Request $request) => $request->url() === $url && $request->method() === 'HEAD');
        Http::assertSent(fn (Request $request) => $request->url() === $url && $request->method() === 'GET');
    }

    public function test_connection_errors_reported_as_failures(): void
    {
        $bad = 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/timeout.jpg';

        Http::fake(function (Request $request) use ($bad) {
            if ($request->url() === $bad) {
                throw new ConnectionException('cURL error 28: timeout');
            }

            return Http::response('', 200);
        });

        MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('f', 64),
            'cloudinary_public_id' => 'maverick-academy/lib/timeout',
            'url' => $bad,
        ]));

        $result = app(MediaVerifyService::class)->verify();

        $this->assertSame(1, $result['failed_live_total']);
        $this->assertNull($result['failed_live'][0]['status']);
        $this->assertStringContainsString('timeout', $result['failed_live'][0]['error']);
    }

    public function test_unparseable_url_fails_with_reason(): void
    {
        Http::fake(['*' => Http::response('', 200)]);

        PartnerLogo::query()->create([
            'name' => 'Broken partner', 'type' => 'alumni', 'logo_url' => 'http:///broken-path',
            'is_active' => true, 'sort_order' => 1,
        ]);

        $result = app(MediaVerifyService::class)->verify();

        $this->assertSame(1, $result['failed_live_total']);
        $this->assertSame('unparseable url', $result['failed_live'][0]['error']);
        Http::assertNotSent(fn (Request $request) => str_contains($request->url(), 'broken-path'));
    }

    public function test_distinct_urls_requested_once(): void
    {
        Http::fake(['*' => Http::response('', 200)]);

        $url = 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/shared.jpg';

        MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('0', 64),
            'cloudinary_public_id' => 'maverick-academy/lib/shared',
            'url' => $url,
        ]));

        PartnerLogo::query()->create([
            'name' => 'Shared partner', 'type' => 'alumni', 'logo_url' => $url,
            'is_active' => true, 'sort_order' => 1,
        ]);

        $this->createSetting('verify-test', 'shared', ['image' => $url]);

        app(MediaVerifyService::class)->verify();

        $hits = collect(Http::recorded())
            ->filter(fn (array $pair) => $pair[0]->url() === $url)
            ->count();

        $this->assertSame(1, $hits);
    }

    public function test_command_fails_on_live_failure(): void
    {
        $bad = 'https://res.cloudinary.com/demo-source/image/upload/v1/maverick-academy/lib/missing.jpg';

        Http::fake(function (Request $request) use ($bad) {
            return $request->url() === $bad
                ? Http::response('', 404)
                : Http::response('', 200);
        });

        MediaAsset::query()->create($this->assetAttrs([
            'hash' => str_repeat('1', 64),
            'cloudinary_public_id' => 'maverick-academy/lib/missing',
            'url' => $bad,
        ]));

        $this->assertSame(1, Artisan::call('media:verify-urls'));
    }

    protected function createSetting(string $group, string $name, array $payload): void
    {
        DB::table('settings')->insert([
            'group' => $group,
            'name' => $name,
            'locked' => false,
            'payload' => json_encode($payload),
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
            'hash' => str_repeat('2', 64),
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
