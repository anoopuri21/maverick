<?php

namespace Tests\Feature;

use App\Models\OurStoryGalleryImage;
use App\Models\OurStoryTimeline;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class OurStoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Run the sync commands to populate settings and timeline
        Artisan::call('our-story:sync-content');
        Artisan::call('our-story:sync-timeline');
    }

    /** @test */
    public function test_our_story_page_renders_with_exact_sop_content()
    {
        $response = $this->get('/our-story');

        $response->assertStatus(200);

        // Section 1 Hero
        $response->assertSee('Our Story');
        $response->assertSee('Empowering learners, professionals, and future leaders');

        // Section 2 How It Started
        $response->assertSee('How It Started');
        $response->assertSee('Where It All Began');
        $response->assertSee('Founded with a commitment to making quality education accessible');

        // Section 3 What We Do Today
        $response->assertSee('What We Do Today');
        $response->assertSee('Building Global Learning Opportunities');
        $response->assertSee('Today, Maverick Business Academy serves a diverse community');

        // Section 4 Our Impact
        $response->assertSee('Transforming Careers Across Borders');
        $response->assertSee('Over the years, Maverick has supported learners');

        // Section 5 Vision for the Future
        $response->assertSee('Looking Ahead');
        $response->assertSee('As we continue to expand our global network of academic');

        // Section 6 Timeline Milestones
        $response->assertSee('Our Journey');
        $response->assertSee('2018');
        $response->assertSee('Maverick Business Academy Established');
        $response->assertSee('Today');
        $response->assertSee('Empowering Learners Worldwide Through Global Education Pathways');

        // Section 7 CEO Message
        $response->assertSee('A Message from');
        $response->assertSee('Our Founder & CEO', false);

        // Section 8 Gallery empty (should NOT render proof of activity)
        $response->assertDontSee('Proof of Activity');
    }

    /** @test */
    public function test_our_story_page_renders_gallery_when_images_present()
    {
        OurStoryGalleryImage::create([
            'image_url' => 'https://cloudinary.com/maverick/graduation.jpg',
            'caption' => 'Maverick graduation highlight of the year',
            'category' => 'Graduation Ceremony',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->get('/our-story');

        $response->assertStatus(200);
        $response->assertSee('Proof of Activity');
        $response->assertSee('Maverick graduation highlight of the year');
        $response->assertSee('Graduation Ceremony');
        $response->assertSee('https://cloudinary.com/maverick/graduation.jpg');
    }

    /** @test */
    public function test_awards_and_accreditations_are_removed_from_our_story_page()
    {
        $response = $this->get('/our-story');

        // Ensure award and accreditation indicators from older templates are gone
        $response->assertDontSee('Awards &amp; Recognition');
        $response->assertDontSee('id="awards"');
    }
}
