<?php

namespace Tests\Feature;

use App\Filament\Resources\OurStoryTestimonialResource;
use App\Models\OurStoryTestimonial;
use App\Services\VoicesTestimonialsService;
use App\Support\TestimonialPages;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class VoicesTestimonialsComponentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('our-story:sync-content');
        Artisan::call('our-story:sync-timeline');
        OurStoryTestimonial::withTrashed()->forceDelete();
    }

    public function test_global_content_testimonial_resource_registers_in_global_content_navigation(): void
    {
        $this->assertTrue(OurStoryTestimonialResource::shouldRegisterNavigation());
        $this->assertSame('Global Content', OurStoryTestimonialResource::getNavigationGroup());
        $this->assertArrayHasKey(TestimonialPages::MBA_MASTERS_LANDING, TestimonialPages::options());
        $this->assertArrayHasKey(TestimonialPages::OUR_STORY, TestimonialPages::options());
    }

    public function test_single_page_selection_displays_only_on_selected_page_and_not_other_pages(): void
    {
        OurStoryTestimonial::create([
            'name' => 'Aisha Al Mansoori',
            'position' => 'Director of Operations',
            'organisation' => 'ADNOC Partner Network',
            'country' => 'Abu Dhabi',
            'testimonial' => 'Exclusive landing page quote that must never show on Our Story.',
            'pages' => [TestimonialPages::MBA_MASTERS_LANDING],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        OurStoryTestimonial::create([
            'name' => 'Rohan Deshmukh',
            'position' => 'VP Strategy',
            'organisation' => 'MENA FinTech Group',
            'country' => 'Dubai',
            'testimonial' => 'Exclusive Our Story quote that must never show on Master Landing.',
            'pages' => [TestimonialPages::OUR_STORY],
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $mlpResponse = $this->get('/online-mba-masters-uae');
        $mlpResponse->assertOk();
        $mlpResponse->assertSee('id="mlp-testimonials"', false);
        $mlpResponse->assertSee('voices-testimonials.css', false);
        $mlpResponse->assertSee('voices-testimonials.js', false);
        $mlpResponse->assertSee('Aisha Al Mansoori');
        $mlpResponse->assertSee('Exclusive landing page quote that must never show on Our Story.');
        $mlpResponse->assertDontSee('Rohan Deshmukh');
        $mlpResponse->assertDontSee('Exclusive Our Story quote that must never show on Master Landing.');

        $ourStoryResponse = $this->get('/our-story');
        $ourStoryResponse->assertOk();
        $ourStoryResponse->assertSee('id="mlp-testimonials"', false);
        $ourStoryResponse->assertSee('voices-testimonials.css', false);
        $ourStoryResponse->assertSee('voices-testimonials.js', false);
        $ourStoryResponse->assertSee('Rohan Deshmukh');
        $ourStoryResponse->assertSee('Exclusive Our Story quote that must never show on Master Landing.');
        $ourStoryResponse->assertDontSee('Aisha Al Mansoori');
        $ourStoryResponse->assertDontSee('Exclusive landing page quote that must never show on Our Story.');
    }

    public function test_multi_page_selection_displays_shared_voices_section_on_both_pages(): void
    {
        OurStoryTestimonial::create([
            'name' => 'Nadia El-Khoury',
            'position' => 'Global MBA Graduate',
            'organisation' => 'University for the Creative Arts',
            'country' => 'UAE',
            'testimonial' => 'Multi-page selected testimonial rendered across both pages seamlessly.',
            'pages' => [
                TestimonialPages::MBA_MASTERS_LANDING,
                TestimonialPages::OUR_STORY,
            ],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $mlpResponse = $this->get('/online-mba-masters-uae');
        $mlpResponse->assertOk();
        $mlpResponse->assertSee('Nadia El-Khoury');
        $mlpResponse->assertSee('Multi-page selected testimonial rendered across both pages seamlessly.');

        $ourStoryResponse = $this->get('/our-story');
        $ourStoryResponse->assertOk();
        $ourStoryResponse->assertSee('Nadia El-Khoury');
        $ourStoryResponse->assertSee('Multi-page selected testimonial rendered across both pages seamlessly.');
    }

    public function test_section_hides_cleanly_when_no_active_testimonials_match_page(): void
    {
        OurStoryTestimonial::create([
            'name' => 'Inactive Student',
            'testimonial' => 'Inactive quote hidden everywhere.',
            'pages' => [TestimonialPages::MBA_MASTERS_LANDING, TestimonialPages::OUR_STORY],
            'is_active' => false,
        ]);

        $ourStoryResponse = $this->get('/our-story');
        $ourStoryResponse->assertOk();
        $ourStoryResponse->assertDontSee('id="mlp-testimonials"', false);
        $ourStoryResponse->assertDontSee('Inactive Student');
    }

    public function test_future_page_extensibility_works_with_zero_extra_code(): void
    {
        config()->set('testimonials.pages.dual-mba', 'Dual MBA Page (/dual-mba)');

        $this->assertArrayHasKey('dual-mba', TestimonialPages::options());
        $this->assertSame('Dual MBA Page (/dual-mba)', TestimonialPages::label('dual-mba'));

        OurStoryTestimonial::create([
            'name' => 'Karim Haddad',
            'position' => 'Dual MBA Cohort',
            'organisation' => 'Maverick Business Academy',
            'testimonial' => 'Future page testimonial rendered cleanly via component tag.',
            'pages' => ['dual-mba'],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->assertCount(1, app(VoicesTestimonialsService::class)->itemsForPage('dual-mba'));
        $this->assertCount(0, app(VoicesTestimonialsService::class)->itemsForPage(TestimonialPages::OUR_STORY));

        $renderedHtml = Blade::render('<x-voices-testimonials page="dual-mba" heading="Dual MBA Voices" />');

        $this->assertStringContainsString('data-voices-section="dual-mba"', $renderedHtml);
        $this->assertStringContainsString('Dual MBA Voices', $renderedHtml);
        $this->assertStringContainsString('Karim Haddad', $renderedHtml);
        $this->assertStringContainsString('Future page testimonial rendered cleanly via component tag.', $renderedHtml);
    }
}
