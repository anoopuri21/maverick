<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class EmptyAdminContentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
        $this->emptyAllSettings();
    }

    public static function publicPages(): array
    {
        return [
            'home' => ['/'],
            'our-story' => ['/our-story'],
            'leadership' => ['/leadership-board'],
            'accreditations' => ['/accreditations'],
            'csr' => ['/csr-community-impact'],
            'media-gallery' => ['/media-gallery'],
            'global-partners' => ['/global-university-partners'],
            'pathway-programs' => ['/pathway-programs'],
            'global-opportunities' => ['/global-opportunities'],
            'global-bachelors' => ['/global-bachelors-pathway'],
            'masters-pathways' => ['/masters-pathways'],
            'edutainment' => ['/educational-tours-edutainment'],
            'dual-mba' => ['/dual-mba-online'],
            'contact' => ['/contact'],
            'blogs' => ['/blogs'],
            'news' => ['/news'],
            'programs' => ['/programs'],
            'events' => ['/events'],
            'student-success' => ['/student-success'],
            'student-success-stories' => ['/student-success/stories'],
            'student-success-videos' => ['/student-success/videos'],
            'about-us-redirect' => ['/about-us'],
        ];
    }

    #[DataProvider('publicPages')]
    public function test_public_pages_return_ok_when_admin_settings_are_empty(string $uri): void
    {
        $response = $this->get($uri);

        if ($uri === '/about-us') {
            $response->assertRedirect(route('our-story'));

            return;
        }

        $response->assertSuccessful();
        $this->assertStringNotContainsString('Whoops', $response->getContent());
        $this->assertStringNotContainsString('Illuminate\\View\\ViewException', $response->getContent());
    }

    private function emptyAllSettings(): void
    {
        $rows = \Illuminate\Support\Facades\DB::table('settings')->get();

        foreach ($rows as $row) {
            $decoded = json_decode($row->payload, true);
            $empty = match (true) {
                is_array($decoded) => [],
                is_int($decoded), is_float($decoded) => 0,
                is_bool($decoded) => false,
                default => null,
            };

            \Illuminate\Support\Facades\DB::table('settings')
                ->where('id', $row->id)
                ->update(['payload' => json_encode($empty)]);
        }

        \Illuminate\Support\Facades\Cache::flush();
    }

    protected function tearDown(): void
    {
        \Illuminate\Support\Facades\Cache::flush();
        parent::tearDown();
    }
}
