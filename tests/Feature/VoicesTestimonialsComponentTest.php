<?php

declare(strict_types=1);

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
use Illuminate\Support\Facades\DB;
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
        $paginator = OurStoryTestimonial::query()->orderBy('sort_order')->paginate(10);

        $this->assertSame(10, $paginator->total());
        $this->assertCount(10, $paginator->items());
        $this->assertSame('Christopher Kinene', $paginator->items()[0]->name);
        $this->assertSame('Ramkumar Dilli', $paginator->items()[9]->name);
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
        $this->assertSame(
            'Program Detail Pages (/programs/{slug})',
            TestimonialPages::options()[TestimonialPages::PROGRAM_DETAIL] ?? null,
        );

        $adminPages = json_decode(
            (string) DB::table('settings')
                ->where('group', 'voices_testimonials')
                ->where('name', 'pages')
                ->value('payload'),
            true,
        );

        $this->assertIsArray($adminPages);
        $this->assertContains([
            'key' => TestimonialPages::PROGRAM_DETAIL,
            'label' => 'Program Detail Pages (/programs/{slug})',
        ], $adminPages);
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

    public function test_model_sanitizes_html_xss_entities_and_normalizes_attributes_on_save(): void
    {
        $record = OurStoryTestimonial::create([
            'name' => '  Zayd Al-Farsi  ',
            'position' => '  Executive MBA  ',
            'organisation' => '   ',
            'country' => '  Oman ',
            'rating' => 99,
            'testimonial' => ' “<p>Transformative <strong>leadership</strong> journey &amp; growth.</p><script>alert(1)</script>” ',
            'pages' => ['MBA Masters Landing!', 'our-story', 'our-story', ''],
            'is_active' => true,
        ]);

        $this->assertSame('Zayd Al-Farsi', $record->name);
        $this->assertSame('Executive MBA', $record->position);
        $this->assertNull($record->organisation);
        $this->assertSame('Oman', $record->country);
        $this->assertSame(5, $record->rating);
        $this->assertSame('Transformative leadership journey & growth.', $record->testimonial);
        $this->assertSame(['mba-masters-landing', 'our-story'], $record->pages);
        $this->assertSame('Executive MBA, Oman', $record->display_role);
    }

    public function test_admin_panel_has_exclusive_control_with_zero_hardcoded_fallbacks(): void
    {
        OurStoryTestimonial::withTrashed()->forceDelete();

        $this->get('/online-mba-masters-uae')
            ->assertOk()
            ->assertDontSee('id="mlp-testimonials"', false);

        $this->get('/our-story')
            ->assertOk()
            ->assertDontSee('id="mlp-testimonials"', false);

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
