<?php

namespace Tests\Feature;

use App\Models\Insight;
use App\Models\MediaAsset;
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
        $response->assertSee('international-students-university-campus-1.jpg', false);
        $response->assertSee('data-news-thumbnail', false);
    }

    /**
     * The listing renders article thumbnails lazily and serves at most ten cards per page.
     */
    public function test_news_listing_loads_ten_articles_at_a_time_with_lazy_thumbnails(): void
    {
        $cover = MediaAsset::create([
            'hash' => hash('sha256', 'news-cover-test'),
            'cloudinary_public_id' => 'news-cover-test',
            'url' => 'https://images.example.test/news-cover.webp',
            'folder' => 'news',
            'alt' => 'Campus ceremony',
        ]);

        for ($i = 1; $i <= 11; $i++) {
            Insight::factory()->create([
                'title' => "Paginated News {$i}",
                'slug' => "paginated-news-{$i}",
                'categories' => ['news'],
                'featured_image_url' => null,
                'featured_image_url_asset_id' => $i === 1 ? $cover->id : null,
                'featured_image_alt' => null,
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
        $this->assertSame(10, substr_count($firstPage->getContent(), 'data-news-thumbnail'));
        $firstPage->assertSee('data-news-list', false);
        $firstPage->assertSee('data-news-next', false);
        $firstPage->assertSee('data-news-sentinel', false);
        $firstPage->assertDontSee('Paginated News 11');
        $firstPage->assertSee('Older articles');

        $this->get(route('news.index', ['page' => 2]))
            ->assertOk()
            ->assertSee('Paginated News 11');

        $autoLoadResponse = $this->getJson(route('news.index', ['page' => 2]));
        $autoLoadResponse->assertOk()->assertJsonStructure([
            'html',
            'count',
            'current_page',
            'next_url',
            'has_more',
        ]);
        $this->assertSame(3, $autoLoadResponse->json('count'));
        $this->assertFalse($autoLoadResponse->json('has_more'));
        $this->assertNull($autoLoadResponse->json('next_url'));
        $this->assertStringContainsString('Paginated News 11', $autoLoadResponse->json('html'));
        $this->assertStringContainsString('international-students-university-campus-1.jpg', $autoLoadResponse->json('html'));
        $this->assertStringContainsString('data-news-thumbnail', $autoLoadResponse->json('html'));
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
