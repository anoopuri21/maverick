<?php

namespace Tests\Feature;

use App\Models\Insight;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InsightAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    /**
     * Test creating a single record with both categories and verifying it appears on both.
     */
    public function test_single_insight_appears_on_both_categories(): void
    {
        $insight = Insight::create([
            'title' => 'Unified Strategic Announcement',
            'slug' => 'unified-strategic-announcement',
            'content' => '<p>This is a combined update.</p>',
            'categories' => ['blogs', 'news'],
            'published_at' => now(),
        ]);

        // Verifying it appears on both scopes
        $this->assertTrue(Insight::category('blogs')->where('id', $insight->id)->exists());
        $this->assertTrue(Insight::category('news')->where('id', $insight->id)->exists());

        // Verifying it shows up on public blogs listing
        $responseBlog = $this->get('/blogs');
        $responseBlog->assertStatus(200);
        $responseBlog->assertSee('Unified Strategic Announcement');

        // Verifying it shows up on public news listing
        $responseNews = $this->get('/news');
        $responseNews->assertStatus(200);
        $responseNews->assertSee('Unified Strategic Announcement');
    }

    /**
     * Test that featuring an item in one category does not un-feature an item in another category.
     */
    public function test_independent_per_category_featuring(): void
    {
        // 1. Create a featured post in 'blogs'
        $blogPost = Insight::create([
            'title' => 'Featured Blog',
            'slug' => 'featured-blog',
            'content' => '<p>Blog content.</p>',
            'categories' => ['blogs'],
            'is_featured' => true,
            'published_at' => now(),
        ]);

        // 2. Create a featured post in 'news'
        $newsPost = Insight::create([
            'title' => 'Featured News',
            'slug' => 'featured-news',
            'content' => '<p>News content.</p>',
            'categories' => ['news'],
            'is_featured' => true,
            'published_at' => now(),
        ]);

        // Verify BOTH are featured since they are in different categories!
        $this->assertTrue($blogPost->fresh()->is_featured);
        $this->assertTrue($newsPost->fresh()->is_featured);

        // 3. Create a NEW featured post in 'blogs'
        $newBlogPost = Insight::create([
            'title' => 'New Featured Blog',
            'slug' => 'new-featured-blog',
            'content' => '<p>New blog content.</p>',
            'categories' => ['blogs'],
            'is_featured' => true,
            'published_at' => now(),
        ]);

        // Verify the OLD blog post is UN-featured, but the featured NEWS post remains featured!
        $this->assertFalse($blogPost->fresh()->is_featured);
        $this->assertTrue($newBlogPost->fresh()->is_featured);
        $this->assertTrue($newsPost->fresh()->is_featured);
    }
}
