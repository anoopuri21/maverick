<?php

namespace Tests\Unit;

use App\Services\CloudinaryService;
use Tests\TestCase;

class CloudinaryFolderTest extends TestCase
{
    public function test_shared_folder_is_used_by_default_in_non_production(): void
    {
        config()->set('services.cloudinary.upload_folder', 'maverick-academy');
        config()->set('services.cloudinary.env_folder', false);

        $service = app(CloudinaryService::class);

        $this->assertFalse($service->usesEnvFolder());
        $this->assertSame('maverick-academy', $service->resolveBaseFolder());
        $this->assertSame('maverick-academy/library', $service->resolveUploadFolder('library'));
        $this->assertSame('shared', $service->diskEnv());
    }

    public function test_env_folder_flag_restores_legacy_suffix(): void
    {
        config()->set('services.cloudinary.upload_folder', 'maverick-academy');
        config()->set('services.cloudinary.env_folder', true);
        config()->set('services.cloudinary.env_prefix', null);

        $service = app(CloudinaryService::class);

        $this->assertTrue($service->usesEnvFolder());
        $this->assertSame('maverick-academy-'.app()->environment(), $service->resolveBaseFolder());
        $this->assertSame(
            'maverick-academy-'.app()->environment().'/site',
            $service->resolveUploadFolder('site')
        );
        $this->assertSame(app()->environment(), $service->diskEnv());
    }

    public function test_normalize_folder_path_strips_legacy_env_suffix(): void
    {
        config()->set('services.cloudinary.upload_folder', 'maverick-academy');
        config()->set('services.cloudinary.env_folder', false);
        config()->set('services.cloudinary.legacy_env_suffixes', ['local', 'testing']);

        $service = app(CloudinaryService::class);

        $this->assertSame(
            'maverick-academy/library',
            $service->normalizeFolderPath('maverick-academy-local/library')
        );
    }
}
