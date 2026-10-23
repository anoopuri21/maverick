<?php

namespace Tests\Feature;

use App\Filament\Resources\OurStoryTestimonialResource;
use App\Models\OurStoryTestimonial;
use App\Services\VoicesTestimonialsService;
use App\Settings\MbaMastersTestimonialsSettings;
use App\Settings\VoicesTestimonialsSettings;
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
    }

    public function test_all_ten_verified_testimonials_exist_in_db_table_and_render_on_both_pages(): void
    {
        $records = OurStoryTestimonial::query()->orderBy('sort_order')->get();

        $this->assertCount(10, $records);
        $this->assertSame('Christopher Kinene', $records->first()->name);
        $this->assertSame('Ramkumar Dilli', $records->last()->name);
        $this->assertSame([], app(MbaMastersTestimonialsSettings::class)->items);

        $mlpResponse = $this->get('/online-mba-masters-uae');
        $mlpResponse->assertOk();
        $mlpResponse->assertSee('Christopher Kinene');
        $mlpResponse->assertSee('Ramkumar Dilli');
        $mlpResponse->assertSee('01-christopher-kinene.jpg', false);

        $ourStoryResponse = $this->get('/our-story');
        $ourStoryResponse->assertOk();
        $ourStoryResponse->assertSee('Christopher Kinene');
        $ourStoryResponse->assertSee('Ramkumar Dilli');
        $ourStoryResponse->assertSee('01-christopher-kinene.jpg', false);
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
        OurStoryTestimonial::withTrashed()->forceDelete();

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
        $mlpResponse->assertSee('Aisha Al Mansoori');
        $mlpResponse->assertDontSee('Rohan Deshmukh');

        $ourStoryResponse = $this->get('/our-story');
        $ourStoryResponse->assertOk();
        $ourStoryResponse->assertSee('Rohan Deshmukh');
        $ourStoryResponse->assertDontSee('Aisha Al Mansoori');
    }

    public function test_multi_page_selection_displays_shared_voices_section_on_both_pages(): void
    {
        OurStoryTestimonial::withTrashed()->forceDelete();

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

        $this->get('/online-mba-masters-uae')
            ->assertOk()
            ->assertSee('Nadia El-Khoury');

        $this->get('/our-story')
            ->assertOk()
            ->assertSee('Nadia El-Khoury');
    }

    public function test_admin_panel_has_exclusive_control_with_zero_hardcoded_fallbacks(): void
    {
        OurStoryTestimonial::withTrashed()->forceDelete();

        // When DB table has 0 active testimonials, neither page renders any hardcoded testimonial
        $this->get('/online-mba-masters-uae')
            ->assertOk()
            ->assertDontSee('id="mlp-testimonials"', false);

        $this->get('/our-story')
            ->assertOk()
            ->assertDontSee('id="mlp-testimonials"', false);

        // When Admin adds a future page dynamically via DB settings and leaves caption blank,
        // no hardcoded caption or fallback image path appears
        $globalSettings = app(VoicesTestimonialsSettings::class);
        $globalSettings->stack_caption = '';
        $globalSettings->pages = [
            ['key' => 'dual-mba', 'label' => 'Dual MBA Page (/dual-mba-online)'],
        ];
        $globalSettings->save();

        $this->assertArrayHasKey('dual-mba', TestimonialPages::options());
        $this->assertSame('Dual MBA Page (/dual-mba-online)', TestimonialPages::label('dual-mba'));

        OurStoryTestimonial::create([
            'name' => 'Karim Haddad',
            'position' => 'Dual MBA Cohort',
            'organisation' => 'Maverick Business Academy',
            'testimonial' => 'Admin-controlled dynamic page quote.',
            'photo' => null,
            'pages' => ['dual-mba'],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->assertCount(1, app(VoicesTestimonialsService::class)->itemsForPage('dual-mba'));
        $renderedHtml = Blade::render('<x-voices-testimonials page="dual-mba" heading="" label="" intro="" stack-caption="" />');

        $this->assertStringContainsString('Karim Haddad', $renderedHtml);
        $this->assertStringNotContainsString('The experience, in their words.', $renderedHtml);
        $this->assertStringNotContainsString('mlp-campus.jpg', $renderedHtml);
    }
}
