<?php

namespace Tests\Unit;

use App\Providers\AppServiceProvider;
use ReflectionMethod;
use Tests\TestCase;

class UrlHostPolicyTest extends TestCase
{
    public function test_only_configured_hosts_can_override_the_root_url(): void
    {
        config(['app.allowed_url_hosts' => ['localhost', '*.e2b.app']]);

        $method = new ReflectionMethod(AppServiceProvider::class, 'isAllowedUrlHost');
        $method->setAccessible(true);
        $provider = $this->app->make(AppServiceProvider::class);

        $this->assertTrue($method->invoke($provider, 'localhost:8000'));
        $this->assertTrue($method->invoke($provider, '8080-sandbox.e2b.app'));
        $this->assertFalse($method->invoke($provider, 'evil.example.com'));
        $this->assertFalse($method->invoke($provider, 'javascript:alert(1)'));
    }
}
