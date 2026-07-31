<?php

namespace Tests\Feature;

use App\Models\Insight;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed database settings
        $this->artisan('db:seed');

        Insight::factory()->create([
            'title' => 'Academy Launches New Global Doctorate Initiative',
            'slug' => 'academy-launches-new-global-doctorate-initiative',
            'content' => '<h2>Institutional Breakthrough</h2><p>The academic council announced the launch today.</p>',
            'categories' => ['news'],
            'author_name' => 'Academic Council',
            'published_at' => now()->subDays(1),
        ]);

        Insight::factory()->create([
            'title' => 'Sharjah Campus Welcomes Fall Cohort',
            'slug' => 'sharjah-campus-welcomes-fall-cohort',
            'categories' => ['news'],
            'published_at' => now(),
        ]);
    }

    /**
     * Test news listing page works perfectly.
     */
    public function test_news_listing_page(): void
    {
        $response = $this->get('/news');

        $response->assertStatus(200);
        $response->assertSee('News & Announcements', false);
        $response->assertSee('Academy Launches New Global Doctorate Initiative');
        $response->assertSee('Sharjah Campus Welcomes Fall Cohort');
    }

    /**
     * Test news detail page works.
     */
    public function test_news_detail_page(): void
    {
        $response = $this->get('/academy-launches-new-global-doctorate-initiative');

        $response->assertStatus(200);
        $response->assertSee('Academy Launches New Global Doctorate Initiative');
        $response->assertSee('Academic Council');
        $response->assertSee('Institutional Breakthrough');
    }
}
