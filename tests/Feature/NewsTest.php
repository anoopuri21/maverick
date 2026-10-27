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
        $response->assertSee('News &amp; <em class="news-masthead__title-em">Announcements</em>', false);
        $response->assertSee('Academy Launches New Global Doctorate Initiative');
        $response->assertSee('Sharjah Campus Welcomes Fall Cohort');
    }

    /**
     * The listing renders article thumbnails lazily and serves at most ten cards per page.
     */
    public function test_news_listing_loads_ten_articles_at_a_time_with_lazy_thumbnails(): void
    {
        for ($i = 1; $i <= 11; $i++) {
            Insight::factory()->create([
                'title' => "Paginated News {$i}",
                'slug' => "paginated-news-{$i}",
                'categories' => ['news'],
                'featured_image_url' => $i === 1 ? 'https://images.example.test/news-cover.webp' : null,
                'featured_image_alt' => $i === 1 ? 'Campus ceremony' : null,
                'published_at' => now()->subMinutes($i),
            ]);
        }

        $firstPage = $this->get(route('news.index'));

        $firstPage->assertOk();
        $this->assertSame(10, substr_count($firstPage->getContent(), 'news-row news-row--listing'));
        $firstPage->assertSee('https://images.example.test/news-cover.webp', false);
        $firstPage->assertSee('alt="Campus ceremony"', false);
        $firstPage->assertSee('loading="lazy"', false);
        $firstPage->assertSee('decoding="async"', false);
        $firstPage->assertDontSee('Paginated News 11');
        $firstPage->assertSee('Older articles');

        $this->get(route('news.index', ['page' => 2]))
            ->assertOk()
            ->assertSee('Paginated News 11');
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
