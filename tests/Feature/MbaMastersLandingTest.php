<?php

namespace Tests\Feature;

use App\Mail\GenericFormMail;
use App\Settings\MbaMastersCareerSettings;
use App\Settings\MbaMastersMbaSettings;
use App\Settings\MbaMastersTestimonialsSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MbaMastersLandingTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_renders_hero_and_trust(): void
    {
        $response = $this->get('/online-mba-masters-uae');

        $response->assertOk();
        $this->assertSame(1, substr_count($response->getContent(), 'name="description"'));
        $response->assertSee('rel="canonical" href="'.route('mba-masters-landing', absolute: true).'"', false);
        $response->assertSee('property="og:url" content="'.route('mba-masters-landing', absolute: true).'"', false);
        $response->assertSee('"@type":"FAQPage"', false);
        $response->assertSee('Online MBA', false);
        $response->assertSee('Master&#039;s Degrees for UAE and GCC Professionals', false);
        $response->assertSee('Maverick Business Academy', false);
        $response->assertSee('How soon you want to start?', false);
        $response->assertSee('name="start_timeline"', false);
        $response->assertSee('id="mlp-enquiry-', false);
        $response->assertSee('name="country"', false);
        $response->assertDontSee('name="specialization"', false);
        $response->assertDontSee('name="qualification"', false);
        $response->assertSee('prospectus-cover', false);
        $response->assertSee('prospectus-cover__statement', false);
        $response->assertSee('data-hero-assembly', false);
        $response->assertSee('prospectus-cover__enquiry', false);
        $response->assertSee('prospectus-enquiry-title', false);
        $response->assertSee('mba-masters-landing.js', false);
        $response->assertSee('signal-atlas', false);
        $response->assertSee('signal-atlas__record', false);
        $response->assertSee('data-signal-record', false);
        $response->assertDontSee('prospectus-insert', false);
        $response->assertDontSee('prospectus-admissions', false);
        $response->assertDontSee('mlp-hero__form-wrap', false);
        $response->assertDontSee('mlp-trust__fan-svg', false);
        $response->assertSee('Rated 4.9 out of 5 on Google and Edarabia', false);
        $response->assertDontSee('5 things, in plain language', false);
        $response->assertSee('Why Maverick Fits a Working Life in the UAE', false);
        $response->assertSee('MBA, Rushford Business School (Switzerland)</button>', false);
        $response->assertSee('EMBA, Girne American University (North Cyprus)</button>', false);
        $response->assertSee('MBA in International Business, University of the West of Scotland (UK)</button>', false);
        $response->assertDontSee('Executive MBA, Girne American University (North Cyprus)', false);
        $this->assertSame(3, substr_count($response->getContent(), 'data-mlp-mba-tab='));
        $response->assertSee('mlp-mba__uni-count">14 specializations', false);
        $response->assertSee('mlp-mba__program-title">Master of Business Administration (MBA)', false);
        $response->assertSee('mlp-mba__program-title">Artificial Intelligence', false);
        $response->assertSee('mlp-mba__program-title">Educational Leadership', false);
        $response->assertSee('mlp-mba__program-title">International Business<', false);
        $response->assertSee('Learners', false);
        $response->assertSee('professionals', false);
        $response->assertSee('Rajesh Menon', false);
        $response->assertSee('Learners Empowered', false);
        $response->assertDontSee('Trustpilot', false);
        $response->assertSee('Flexible international study for UAE and GCC professionals', false);
        $response->assertSee('Get the program and fee guide', false);
        $response->assertDontSee('A prospectus for your next move', false);
        $response->assertSee('"@type":"BreadcrumbList"', false);
        $response->assertSee('"@type":"Organization"', false);
        $response->assertSee('MBA in UAE guide', false);
        $response->assertDontSee('AggregateRating', false);
        $response->assertSee('Apply Now', false);
        $response->assertSee('MBA in Dubai: What You Actually Get', false);
        $response->assertSee('ASK Quotient Development', false);
        $response->assertSee('How to Start: 5 Steps', false);
        $response->assertSee('id="mlp-journey"', false);
        $response->assertSee('MBA Specializations in the UAE: Pick Your MBA', false);
        $response->assertSee('Rushford Business School', false);
        $response->assertSee('mlp-mba__uni-photo', false);
        $response->assertSee('Master&#039;s Degrees Beyond the MBA', false);
        $response->assertSee('MSc in Counseling Psychology', false);
        $response->assertDontSee('MSc in Counselling Psychology', false);
        $response->assertSee('Request Fee Plan', false);
        $response->assertSee('Head Office: Sharjah and London', false);
        $response->assertSee('Pay in AED installments', false);
        $response->assertSee('About 50% of the cohort', false);
        $response->assertDontSee('4.9/5', false);
        $response->assertSee('University of Wolverhampton', false);
        $response->assertSee('University of the West of Scotland', false);
        $response->assertSee('mlp-masters__split', false);
        $response->assertSee('mlp-trending', false);
        $response->assertSee('Trending', false);
        $response->assertSee('Affordable MBA in Finance', false);
        $response->assertSee('mlp-masters__ledger', false);
        $response->assertSee('MBA Fees in the UAE: What You Actually Pay', false);
        $response->assertSee('pricing-cards', false);
        $response->assertSee('pricing-card', false);
        $response->assertSee('pricing-cards__base', false);
        $response->assertSee('pricing-cards__base-price', false);
        $response->assertSee('Fee structure starts from', false);
        $response->assertSee('AED 16,000–40,000*', false);
        $response->assertDontSee('Confirmed in writing.', false);
        $response->assertDontSee('pricing-card__price', false);
        $this->assertSame(7, substr_count($response->getContent(), 'class="pricing-card"'));
        $response->assertSee('pricing-cards__blocks', false);
        $response->assertSee('MBA, Rushford — 14 specializations', false);
        $response->assertSee('EMBA, Girne American University', false);
        $response->assertSee('Master&#039;s degrees, Rushford and Girne', false);
        $response->assertSee('Master of Laws, Wolverhampton', false);
        $response->assertDontSee('What your written quotation shows', false);
        $response->assertDontSee('The dates and refund terms are part of the written quotation.', false);
        $response->assertSee('Program route', false);
        $response->assertSee('100% Online', false);
        $response->assertSee('AED installments may be available', false);
        $response->assertSee('A group project puts you in a room with people who already run teams across the Gulf.', false);
        $response->assertDontSee('archive-investment__records', false);
        $response->assertDontSee('<table', false);
        $response->assertSee('Request Fee Details', false);
        $response->assertSee('Get Scholarship Eligibility Check', false);
        $response->assertSee('Cohort', false);
        $response->assertDontSee('Class Snapshot', false);
        $response->assertSee('Class of 2025:', false);
        $response->assertSee('MBA Students', false);
        $response->assertSee('979', false);
        $response->assertSee('Countries Represented', false);
        $response->assertSee('98.70%', false);
        $response->assertSee('Average Age', false);
        $response->assertSee('33.7', false);
        $response->assertSee('Average Years of Professional Experience', false);
        $response->assertSee('11.2', false);
        $this->assertDoesNotMatchRegularExpression('/this page/i', $response->getContent());
        $response->assertSee('Built for the GCC Region', false);
        $response->assertDontSee('MoHESR', false);
        $response->assertSee('Your Maverick Cohort: Professionals Across the UAE and GCC', false);
        $response->assertSee('Global Cohorts', false);
        $response->assertSee('Classmates join from the Gulf and from countries well beyond it.', false);
        $response->assertDontSee('mlp-class-snapshot__audience', false);
        $response->assertDontSee('Employed full time while studying', false);
        $response->assertSee('Online MBA for professionals across the GCC', false);
        $response->assertSee('is-marquee', false);
        $response->assertSee('mlp-class-snapshot', false);
        $response->assertSee('mlp-class-snapshot__metric', false);
        $response->assertSee('mlp-class-snapshot__country', false);
        $response->assertSee('Median age in the current cohort', false);
        $response->assertSee('What UAE Graduates Did Next', false);
        $response->assertSee('archive-career', false);
        $response->assertSee('archive-career__stack', false);
        $response->assertSee('archive-career__dossier', false);
        $response->assertSee('Alumni in the UAE and across the GCC', false);
        $response->assertSee('archive-alumni', false);
        $response->assertSee('archive-alumni__ribbon', false);
        $response->assertSee('How Online Learning Actually Works', false);
        $response->assertSee('archive-learning', false);
        $response->assertSee('archive-learning__stack', false);
        $response->assertSee('Live evening classes', false);
        $response->assertSee('Dedicated success coach', false);
        $response->assertSee('id="mlp-learning"', false);
        $response->assertSee('International MBA Degrees from Our University Partners', false);
        $response->assertSee('archive-partners', false);
        $response->assertSee('archive-partners__wall', false);
        $response->assertSee('archive-partners__checklist', false);
        $response->assertSee('id="mlp-video-proof"', false);
        $response->assertSee('archive-video-proof__player', false);
        $response->assertSee('data-inline-youtube', false);
        $response->assertDontSee('videoModal', false);
        $response->assertSee('archive-voices', false);
        $response->assertSee('luxury-testimonial', false);
        $response->assertSee('What students say in their own words', false);
        $response->assertSee('Online MBA vs Classroom MBA: A Fair Comparison', false);
        $response->assertSee('archive-parallel', false);
        $response->assertSee('archive-parallel__row', false);
        $response->assertSee('archive-parallel__blocks', false);
        $response->assertSee('id="mlp-compare"', false);
        $response->assertSee('Frequently Asked Questions', false);
        $response->assertSee('archive-fieldnotes', false);
        $response->assertSee('Do I need a student visa?', false);
        $response->assertSee('Choose Your Program With the Facts in Writing', false);
        $response->assertDontSee('September 2026 intake is open', false);
        $response->assertSee('id="mlp-overview"', false);
        $response->assertSee('blueprint-overview', false);
        $this->assertGreaterThanOrEqual(2, substr_count($response->getContent(), 'name="start_timeline"'));
        $response->assertSee('archive-closing', false);
        $response->assertSee('archive-closing__form', false);
        $response->assertSee('<h3 class="footer__newsletter-title">Stay Updated</h3>', false);
    }

    public function test_career_and_testimonials_sections_can_be_hidden(): void
    {
        $career = app(MbaMastersCareerSettings::class);
        $career->show_section = false;
        $career->save();

        $testimonials = app(MbaMastersTestimonialsSettings::class);
        $testimonials->show_section = false;
        $testimonials->save();

        $response = $this->get('/online-mba-masters-uae');

        $response->assertOk();
        $response->assertDontSee('id="mlp-career"', false);
        $response->assertDontSee('id="mlp-testimonials"', false);
        $response->assertSee('id="mlp-video-proof"', false);
        $response->assertSee('Learners', false);
        $response->assertSee('professionals', false);
    }

    public function test_admin_mba_specializations_including_global_mba_are_visible(): void
    {
        $settings = app(MbaMastersMbaSettings::class);
        $tabs = $settings->tabs;
        $tabs[] = [
            'key' => 'uca-global-mba',
            'label' => 'Global MBA, University for the Creative Arts (UK)',
            'universities' => [[
                'name' => 'University for the Creative Arts (UCA), UK',
                'programs' => [
                    ['title' => 'Global MBA'],
                ],
            ]],
        ];
        $settings->tabs = $tabs;
        $settings->save();

        $response = $this->get('/online-mba-masters-uae');

        $response->assertOk();
        $response->assertSee('Global MBA, University for the Creative Arts (UK)</button>', false);
        $response->assertSee('mlp-mba__program-title">Global MBA', false);
        $this->assertSame(4, substr_count($response->getContent(), 'data-mlp-mba-tab='));
    }

    public function test_mba_section_uses_images_configured_in_admin_settings(): void
    {
        $settings = app(MbaMastersMbaSettings::class);
        $tabs = $settings->tabs;
        $tabs[0]['universities'][0]['image'] = 'https://cdn.example.com/admin-campus.jpg';
        $tabs[0]['universities'][0]['image_asset_id'] = null;

        $settings->stage_image = 'https://cdn.example.com/admin-stage.jpg';
        $settings->stage_image_asset_id = null;
        $settings->tabs = $tabs;
        $settings->save();

        $response = $this->get('/online-mba-masters-uae');

        $response->assertOk();
        $response->assertSee('src="https://cdn.example.com/admin-stage.jpg"', false);
        $response->assertSee('src="https://cdn.example.com/admin-campus.jpg"', false);
    }

    public function test_enquiry_validation_errors_redirect_with_errors(): void
    {
        $response = $this->from('/online-mba-masters-uae')->post('/online-mba-masters-uae/enquire', [
            'name' => '',
            'email' => 'not-an-email',
            'phone' => '',
            'website' => '',
        ]);

        $response->assertRedirect('/online-mba-masters-uae');
        $response->assertSessionHasErrors(['name', 'email', 'phone']);
    }

    public function test_filament_chunk_pages_register_under_landing_pages(): void
    {
        $pages = [
            \App\Filament\Pages\MbaMastersLanding\ManageHeroTrust::class,
            \App\Filament\Pages\MbaMastersLanding\ManagePrograms::class,
            \App\Filament\Pages\MbaMastersLanding\ManageAudience::class,
            \App\Filament\Pages\MbaMastersLanding\ManageProof::class,
            \App\Filament\Pages\MbaMastersLanding\ManageFaqClose::class,
            \App\Filament\Pages\MbaMastersLanding\ManageSeo::class,
        ];

        foreach ($pages as $page) {
            $this->assertTrue($page::shouldRegisterNavigation());
            $this->assertSame('Landing Pages', $page::getNavigationGroup());
        }
    }

    public function test_enquiry_sends_zoho_mail_notification(): void
    {
        Mail::fake();

        $response = $this->from('/online-mba-masters-uae')->post('/online-mba-masters-uae/enquire', [
            'name' => 'Alex Student',
            'email' => 'alex@example.com',
            'phone' => '+971500000000',
            'country' => 'GCC',
            'program' => 'MBA',
            'specialization' => 'Finance',
            'qualification' => 'bachelor',
            'start_timeline' => '1-3-months',
            'website' => '',
        ]);

        $response->assertRedirect('/online-mba-masters-uae');
        $response->assertSessionHas('success');

        Mail::assertSent(GenericFormMail::class, function (GenericFormMail $mail) {
            $values = collect($mail->rows)->pluck('value', 'label');

            return str_contains($mail->emailSubject, 'MBA/Master')
                && $values->get('Email') === 'alex@example.com'
                && $values->get('Country') === 'GCC'
                && $values->get('Preferred specialization') === 'Finance'
                && $values->get('Highest qualification') === "Bachelor's Degree"
                && $values->get('How soon you want to start') === '1–3 months';
        });
    }

    public function test_enquiry_honeypot_skips_mail(): void
    {
        Mail::fake();

        $response = $this->from('/online-mba-masters-uae')->post('/online-mba-masters-uae/enquire', [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'phone' => '123',
            'website' => 'http://spam.test',
        ]);

        $response->assertRedirect('/online-mba-masters-uae');
        Mail::assertNothingSent();
    }

    public function test_enquiry_surfaces_error_when_mail_fails(): void
    {
        Mail::shouldReceive('mailer')->andThrow(new \RuntimeException('SMTP down'));

        $response = $this->from('/online-mba-masters-uae')->post('/online-mba-masters-uae/enquire', [
            'name' => 'Alex Student',
            'email' => 'alex@example.com',
            'phone' => '+971500000000',
            'program' => 'MBA',
            'start_timeline' => '1-3-months',
            'website' => '',
        ]);

        $response->assertRedirect('/online-mba-masters-uae');
        $response->assertSessionHas('error');
        $response->assertSessionMissing('success');
    }

    public function test_masters_settings_class_resolves_with_trending_defaults(): void
    {
        $masters = app(\App\Settings\MbaMastersMastersSettings::class)->toArray();

        $this->assertSame('Trending|Picks', $masters['trending_title']);
        $this->assertCount(8, $masters['trending']);
        $this->assertSame('Affordable MBA in Finance', $masters['trending'][0]['label']);
        $this->assertSame(78, $masters['trending'][0]['percent']);
    }

    public function test_empty_trending_hides_panel_and_ledger_spans_full_width(): void
    {
        $settings = app(\App\Settings\MbaMastersMastersSettings::class);
        $settings->trending = [];
        $settings->save();

        $response = $this->get('/online-mba-masters-uae');

        $response->assertOk();
        $response->assertSee('mlp-masters__split mlp-masters__split--full', false);
        $response->assertSee('mlp-masters__ledger', false);
        $response->assertDontSee('mlp-trending__row', false);
        $response->assertDontSee('mlp-trending__track', false);
    }

    public function test_two_tone_title_falls_back_to_trending_when_first_part_empty(): void
    {
        $settings = app(\App\Settings\MbaMastersMastersSettings::class);
        $settings->trending_title = '|Exam Hot Picks';
        $settings->save();

        $response = $this->get('/online-mba-masters-uae');

        $response->assertOk();
        $response->assertSee('mlp-trending__title-dark">Trending</span>', false);
        $response->assertSee('Exam Hot Picks', false);
    }

    public function test_masters_trending_renders_admin_configured_values(): void
    {
        $settings = app(\App\Settings\MbaMastersMastersSettings::class);
        $settings->trending = [
            ['label' => 'MBA in FinTech', 'percent' => 91],
            ['label' => 'MBA in Data Science', 'percent' => 87],
        ];
        $settings->trending_title = 'Hot|MBA Picks';
        $settings->save();

        $response = $this->get('/online-mba-masters-uae');

        $response->assertOk();
        $response->assertSee('MBA in FinTech', false);
        $response->assertSee('91%', false);
        $response->assertSee('MBA in Data Science', false);
        $response->assertSee('87%', false);
        $response->assertSee('Hot', false);
        $response->assertSee('MBA Picks', false);
        $response->assertDontSee('Affordable MBA in Finance', false);
        $response->assertSee('mlp-trending__title-dark">Hot</span>', false);
    }
}
