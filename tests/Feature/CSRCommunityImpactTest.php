<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CSRCommunityImpactTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    /** @test */
    public function test_csr_community_impact_page_renders_with_exact_sop_content()
    {
        $response = $this->get('/csr-community-impact');

        $response->assertStatus(200);

        // PAGE BANNER (HERO)
        $response->assertSee('CSR &amp; Community Impact', false);
        $response->assertSee('Community Impact', false);
        $response->assertSee('Creating Positive Impact Through Education, Community Engagement, and Social Responsibility.');

        // SECTION 1: OUR COMMITMENT
        $response->assertSee('Our <span class="csr-text-accent">Commitment</span>', false);
        $response->assertSee('At Maverick Business Academy, we believe education extends beyond classrooms.');

        // SECTION 2: CSR FOCUS AREAS
        $response->assertSee('Education &amp; Skill Development', false);
        $response->assertSee('Community Engagement');
        $response->assertSee('Sustainability &amp; Environment', false);
        $response->assertSee('Inclusion &amp; Accessibility', false);
        $response->assertSee('Free educational workshops');
        $response->assertSee('Equal learning opportunities');

        // SECTION 3: CSR ACTIVITIES GALLERY
        $response->assertSee('Teachers Training Workshop 2026');
        $response->assertSee('Student Career Development Sessions');
        $response->assertSee('Community Education Initiatives');
        $response->assertSee('Sustainability Awareness Campaign');

        // SECTION 4: IMPACT NUMBERS
        $response->assertSee('Educators Trained');
        $response->assertSee('Learners Supported');
        $response->assertSee('Community Activities');
        $response->assertSee('CSR Initiatives Conducted');

        // SECTION 5: SCHOLARSHIP & EDUCATIONAL SUPPORT
        $response->assertSee('Educational Access', false);
        $response->assertSee('&amp; Scholarships', false);
        $response->assertSee('Maverick supports deserving learners through scholarship opportunities');
        $response->assertSee('Free Masterclasses');
        $response->assertSee('Women Leadership Initiatives');
        $response->assertSee('Youth Entrepreneurship Workshops');
    }
}
