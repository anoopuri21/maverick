<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Route paths in Laravel are case-sensitive: /Programs/bsc-psychology 404s
 * while /programs/bsc-psychology works. The RedirectUppercasePaths middleware
 * turns any mixed-case path into a 301 to its lowercase form.
 */
class UrlCaseRedirectTest extends TestCase
{
    public function test_uppercase_paths_redirect_permanently_to_lowercase(): void
    {
        $response = $this->get('/Programs/bsc-psychology');

        $response->assertStatus(301);
        $response->assertRedirect('/programs/bsc-psychology');
    }

    public function test_any_casing_is_normalised(): void
    {
        $this->get('/PROGRAMS/BSc-Psychology')
            ->assertStatus(301)
            ->assertRedirect('/programs/bsc-psychology');
    }

    public function test_query_string_is_preserved(): void
    {
        $this->get('/Programs?Page=2&Sort=ASC')
            ->assertStatus(301)
            ->assertRedirect('/programs?Page=2&Sort=ASC');
    }

    public function test_lowercase_paths_pass_through_untouched(): void
    {
        $middleware = new \App\Http\Middleware\RedirectUppercasePaths();

        $response = $middleware->handle(
            \Illuminate\Http\Request::create('/programs/bsc-psychology'),
            fn () => response('ok')
        );

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_non_get_requests_are_not_redirected(): void
    {
        $response = $this->post('/Programs/bsc-psychology');

        $this->assertNotSame(301, $response->getStatusCode());
    }
}
