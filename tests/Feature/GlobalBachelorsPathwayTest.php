<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlobalBachelorsPathwayTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the Global Bachelor's Pathway page renders correctly and contains the Pathway Comparison section.
     */
    public function test_global_bachelors_pathway_page_renders_with_comparison_section(): void
    {
        $response = $this->get('/global-bachelors-pathway');

        $response->assertStatus(200);

        // Section Title
        $response->assertSee('Compare the Traditional Route with', false);

        // Card 1
        $response->assertSee('Traditional Route', false);
        $response->assertSee('4 Years', false);
        $response->assertSee('Higher overall tuition', false);
        $response->assertSee('living costs', false);
        $response->assertSee('Full overseas study from Year 1', false);
        $response->assertSee('Longer time to graduation', false);
        $response->assertSee('MORE EXPENSIVE', false);
        $response->assertSee('Total Estimated Fees', false);
        $response->assertSee('EUR 58,000', false);

        // Card 2
        $response->assertSee('Maverick Hybrid / Online Route', false);
        $response->assertSee('Approx 3 Years', false);
        $response->assertSee('Stage 1', false);
        $response->assertSee('2 at Maverick', false);
        $response->assertSee('Final University Progression in Europe', false);
        $response->assertSee('SMART', false);
        $response->assertSee('COST-EFFECTIVE', false);
        $response->assertSee('Hungary', false);
        $response->assertSee('EUR 22,750', false);
        $response->assertSee('Romania / Moldova', false);
        $response->assertSee('EUR 11,250', false);

        // Card 3
        $response->assertSee('Maverick On-Campus Route', false);
        $response->assertSee('Approx 2 Years', false);
        $response->assertSee('Stage 1', false);
        $response->assertSee('Structured campus learning', false);
        $response->assertSee('PREMIUM', false);
        $response->assertSee('STRUCTURED', false);
        $response->assertSee('EUR 25,750', false);
        $response->assertSee('EUR 14,250', false);

        // Bottom Callout
        $response->assertSee('TIME SAVING', false);
        $response->assertSee('Up to 12 Months (Hungary)', false);
        $response->assertSee('A smarter pathway for students and parents seeking lower cost, reduced duration.', false);
    }
}
