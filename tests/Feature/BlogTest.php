<?php

namespace Tests\Feature;

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
    }

    /**
     * Test the blog listing page works perfectly.
     */
    public function test_blog_listing_page(): void
    {
        $response = $this->get('/blogs');

        $response->assertStatus(200);
        $response->assertSee('Latest Articles & Insights', false);
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
     * Test the blog detail page rendering.
     */
    public function test_blog_detail_page(): void
    {
        $response = $this->get('/blogs/unlocking-global-leadership-future-executive-mba');

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
        $response = $this->get('/blogs/invalid-article-slug-xyz');

        $response->assertStatus(404);
    }
}
