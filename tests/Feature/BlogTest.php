<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Define hooks to run before each test method.
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Since the layout/views look for SiteSettings and other models,
        // we seed the database to avoid SQLITE "no such table/settings" issues.
        $this->artisan('db:seed');

        BlogPost::factory()->create([
            'title' => 'Unlocking Global Leadership: The Future of the Executive MBA',
            'slug' => 'unlocking-global-leadership-future-executive-mba',
            'content' => '<h2>The Shift in Global Executive Leadership</h2><p>Body copy.</p>',
            'category' => 'MBA Insights',
            'author_name' => 'Dr. Elizabeth Vance',
            'published_at' => now()->subDays(2),
        ]);

        BlogPost::factory()->create([
            'title' => 'Negotiation Masterclass: Strategies for High-Stakes Deals',
            'slug' => 'negotiation-masterclass-strategies-high-stakes-deals',
            'category' => 'Leadership',
            'published_at' => now()->subDays(1),
        ]);

        BlogPost::factory()->create([
            'title' => 'Demystifying Venture Capital: How Startups Raise Capital',
            'slug' => 'demystifying-venture-capital-how-startups-raise-capital',
            'category' => 'Industry Trends',
            'published_at' => now(),
        ]);
    }

    /**
     * Test the blog listing page works perfectly.
     */
    public function test_blog_listing_page(): void
    {
        $response = $this->get('/blogs');

        $response->assertStatus(200);
        $response->assertSee('Latest Articles &amp; Insights', false);
        $response->assertSee('Unlocking Global Leadership');
        $response->assertSee('MBA Insights');
    }

    /**
     * Test that searching works and displays results.
     */
    public function test_blog_search(): void
    {
        $response = $this->get('/blogs?search=Venture');

        $response->assertStatus(200);
        $response->assertSee('Demystifying Venture Capital');
    }

    /**
     * Test that filtering by category works.
     */
    public function test_blog_category_filter(): void
    {
        $response = $this->get('/blogs?category=Leadership');

        $response->assertStatus(200);
        $response->assertSee('Negotiation Masterclass');
    }

    /**
     * Test the blog detail page rendering at the new root-level permalink.
     */
    public function test_blog_detail_page(): void
    {
        $response = $this->get('/unlocking-global-leadership-future-executive-mba');

        $response->assertStatus(200);
        $response->assertSee('Unlocking Global Leadership: The Future of the Executive MBA');
        $response->assertSee('Dr. Elizabeth Vance');
        $response->assertSee('Table of Contents');
        // Check dynamic table of contents item matching H2 header
        $response->assertSee('The Shift in Global Executive Leadership');
    }

    /**
     * Test blog detail returns 404 for unknown slug.
     */
    public function test_blog_detail_not_found(): void
    {
        $response = $this->get('/invalid-article-slug-xyz');

        $response->assertStatus(404);
    }

    /**
     * Test that a post with no featured image renders the branded
     * typographic cover fallback instead of a broken image.
     */
    public function test_blog_falls_back_to_branded_cover_when_no_image(): void
    {
        $response = $this->get('/blogs');

        $response->assertStatus(200);
        $response->assertSee('blog-thumb--fallback', false);
    }
}
