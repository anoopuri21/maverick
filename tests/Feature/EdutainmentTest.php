<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EdutainmentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the redesigned Edutainment page renders correctly with all SOP content intact.
     */
    public function test_edutainment_page_renders_with_exact_sop_content(): void
    {
        $response = $this->get('/educational-tours-edutainment');

        $response->assertStatus(200);

        // Section 1: Intro
        $response->assertSee('Educational Tours That Bring Learning to Life', false);
        $response->assertSee('Explore the World.', false);
        $response->assertSee('Experience <em>New Cultures</em>.', false);
        $response->assertSee('Learn Beyond the <em>Classroom</em>.', false);
        $response->assertSee('Education does not have to remain inside a classroom. Maverick Edutainment creates educational tours and international study trips that combine learning, exploration, culture and entertainment in one meaningful experience.', false);

        // Section 2: What is Edutainment
        $response->assertSee('Understanding Edutainment', false);
        $response->assertSee('What Is <em>Edutainment?</em>', false);
        $response->assertSee('Edutainment is the combination of <strong>education and entertainment</strong>.', false);
        $response->assertSee('A Maverick Edutainment programme may combine:', false);
        $response->assertSee('Educational institution visits', false);
        $response->assertSee('University and campus experiences', false);

        // Section 3: Learning Beyond the Classroom
        $response->assertSee('Learning Beyond', false);
        $response->assertSee('the <em>Classroom</em>', false);
        $response->assertSee('Greater cultural awareness', false);
        $response->assertSee('Wider global exposure', false);

        // Section 4: Who are tours designed for
        $response->assertSee('Who Are Our Educational Tours', false);
        $response->assertSee('<em>Designed For?</em>', false);
        $response->assertSee('School Students', false);
        $response->assertSee('College and University Students', false);

        // Section 5: Our Edutainment Programmes
        $response->assertSee('Our Edutainment', false);
        $response->assertSee('<em>Programmes</em>', false);
        $response->assertSee('UAE Educational Tours for School Students', false);
        $response->assertSee('China Educational and Business Study Tour', false);

        // Section 6: Tour Themes
        $response->assertSee('Educational Tour', false);
        $response->assertSee('<em>Themes</em>', false);
        $response->assertSee('Business and Entrepreneurship', false);
        $response->assertSee('Artificial Intelligence and Technology', false);

        // Section 7: Experiences
        $response->assertSee('What Students', false);
        $response->assertSee('<em>Can Experience</em>', false);
        $response->assertSee('Academic Experiences', false);
        $response->assertSee('Professional Experiences', false);

        // Section 8: Why Choose Maverick Edutainment
        $response->assertSee('Why Choose Maverick', false);
        $response->assertSee('<em>Edutainment?</em>', false);
        $response->assertSee('Education-Led Programme Design', false);

        // Section 9: Package Inclusions
        $response->assertSee('What Can Be Included in an', false);
        $response->assertSee('<em>Edutainment Package?</em>', false);
        $response->assertSee('Educational itinerary planning', false);

        // Section 10: Institutions
        $response->assertSee('Educational Tours for Schools', false);
        $response->assertSee('<em>and Institutions</em>', false);
        $response->assertSee('School educational trips', false);

        // Section 11: FAQ
        $response->assertSee('Frequently Asked <em>Questions</em>', false);
        $response->assertSee('What does Edutainment mean?', false);
        $response->assertSee('Is Edutainment the same as a normal tour?', false);

        // Section 12: Final CTA
        $response->assertSee('Transform a Student Trip into a', false);
        $response->assertSee('<em>Learning Journey</em>', false);
    }
}
