<?php

namespace Tests\Feature;

use App\Mail\GenericFormMail;
use App\Settings\MbaMastersMbaSettings;
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
        $response->assertSee('Affordable Online MBA', false);
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
        $response->assertSee('mba-masters-hero-assembly.js', false);
        $response->assertSee('signal-atlas', false);
        $response->assertSee('signal-atlas__record', false);
        $response->assertSee('data-signal-record', false);
        $response->assertSee('mba-masters-trust.js', false);
        $response->assertDontSee('prospectus-insert', false);
        $response->assertDontSee('prospectus-admissions', false);
        $response->assertDontSee('mlp-hero__form-wrap', false);
        $response->assertDontSee('mlp-trust__fan-svg', false);
        $response->assertSee('4.8', false);
        $response->assertSee('400+ Reviews', false);
        $response->assertSee('Trusted by learners across the GCC', false);
        $response->assertSee("MBA & Master's Programs Designed for Working Professionals");
        $response->assertSee('Online & flexible study');
        $response->assertSee('Check Eligibility', false);
        $response->assertSee('Why choose Maverick', false);
        $response->assertSee('Flexible learning', false);
        $response->assertSee('International pathways', false);
        $response->assertSee('Admission journey', false);
        $response->assertSee('Admission counselling', false);
        $response->assertSee('Start live session', false);
        $response->assertSee('MBA specializations', false);
        $response->assertSee('MBA programmes from the listing', false);
        $response->assertSee('Specializations', false);
        $response->assertSee('Rushford Business School — MBA', false);
        $response->assertSee('mlp-mba__uni-photo', false);
        $response->assertSee('mba-stage.jpg', false);
        $response->assertSee('business-management-mba.jpg', false);
        $response->assertSee('specialized-mba.jpg', false);
        $response->assertSee('executive-mba.jpg', false);
        $response->assertSee('Master\'s programs', false);
        $response->assertSee('Master of Laws', false);
        $response->assertSee('MSc in Counselling Psychology', false);
        $response->assertSee('University of Wolverhampton', false);
        $response->assertDontSee('Global MBA', false);
        $response->assertDontSee('listed under Master', false);
        $response->assertDontSee('BA (Hons) in Global Business', false);
        $response->assertDontSee('BSc Business Management', false);
        $response->assertDontSee('Level 7 Diploma', false);
        $response->assertSee('mlp-masters__uni-photo', false);
        $response->assertSee('Online MBA & Master\'s Fees in GCC');
        $response->assertSee('pricing-cards', false);
        $response->assertSee('pricing-cards__base', false);
        $response->assertSee('pricing-cards__base-price', false);
        $response->assertSee('AED 16,000–40,000*', false);
        $response->assertDontSee('pricing-card__price', false);
        $response->assertDontSee('archive-investment__records', false);
        $response->assertDontSee('<table', false);
        $response->assertDontSee('Speak to advisor', false);
        $response->assertDontSee('Speak to an advisor', false);
        $response->assertSee('Request Fee Details', false);
        $response->assertSee('Get Scholarship Eligibility Check', false);
        $response->assertSee('The Executive MBA Class Profile 2025', false);
        $response->assertSee('cohort-room', false);
        $response->assertSee('cohort-room__board', false);
        $response->assertSee('cohort-room__metric', false);
        $response->assertSee('cohort-room__industry', false);
        $response->assertSee('mba-masters-archive.js', false);
        $response->assertSee('Executive MBA · 2025 cohort · Online format', false);
        $response->assertSee('Executive MBA in Public Health', false);
        $response->assertSee('281', false);
        $response->assertSee('13 years 2 months', false);
        $response->assertSee('20%', false);
        $response->assertSee('India', false);
        $response->assertSee('Netherlands', false);
        $response->assertSee('IT &amp; related fields', false);
        $response->assertSee('Financial Services', false);
        $response->assertSee('data-lucide="users"', false);
        $response->assertSee('data-lucide="globe"', false);
        $response->assertSee('data-lucide="briefcase"', false);
        $response->assertDontSee('cohort-portrait', false);
        $response->assertDontSee('cohort-room__industry-index', false);
        $response->assertDontSee('mlp-class__industry-frame', false);
        $response->assertSee('Career Growth After MBA', false);
        $response->assertSee('archive-career', false);
        $response->assertSee('archive-career__stack', false);
        $response->assertSee('archive-career__dossier', false);
        $response->assertSee('Finance and commercial management', false);
        $response->assertSee('Build from', false);
        $response->assertSee('An alumni network that travels with you', false);
        $response->assertSee('archive-alumni', false);
        $response->assertSee('archive-alumni__ribbon', false);
        $response->assertSee('archive-alumni__logo-frame', false);
        $response->assertSee('A professional network built through study', false);
        $response->assertSee('A flexible learning model', false);
        $response->assertSee('archive-learning', false);
        $response->assertSee('archive-learning__stack', false);
        $response->assertSee('Live + on-demand', false);
        $response->assertSee('Weekend-friendly rhythm', false);
        $response->assertSee('Check Eligibility', false);
        $response->assertSee('Awarded by recognised partner universities', false);
        $response->assertSee('archive-partners', false);
        $response->assertSee('archive-partners__wall', false);
        $response->assertSee('archive-partners__logo-item', false);
        $response->assertSee('id="mlp-video-proof"', false);
        $response->assertSee('archive-video-proof__player', false);
        $response->assertSee('data-inline-youtube', false);
        $response->assertSee('data-inline-youtube-trigger', false);
        $response->assertSee('4p0rsCEljgo', false);
        $response->assertSee('mba-masters-video-proof.js', false);
        $response->assertDontSee('videoModal', false);
        $response->assertSee('mba-masters-archive.js', false);
        $response->assertSee('archive-voices', false);
        $response->assertSee('luxury-testimonial', false);
        $response->assertSee('Hear from Maverick alumni', false);
        $response->assertSee('Mohammad Taha', false);
        $response->assertDontSee('Placeholders', false);
        $response->assertDontSee('XX,XXX', false);
        $response->assertSee('Why working professionals choose online', false);
        $response->assertSee('archive-parallel', false);
        $response->assertSee('archive-parallel__row', false);
        $response->assertSee('Career continuity', false);
        $response->assertSee('Questions applicants ask first', false);
        $response->assertSee('archive-fieldnotes', false);
        $response->assertSee('archive-fieldnotes__note', false);
        $response->assertSee('mlp-prose', false);
        $response->assertSee('Are the degrees internationally recognised?', false);
        $response->assertSee('Ready to shape your future?', false);
        $response->assertSee('id="mlp-overview"', false);
        $response->assertSee('blueprint-overview', false);
        $response->assertSee('blueprint-overview__system', false);
        $response->assertSee('blueprint-overview__foundation', false);
        $response->assertSee('mba-masters-overview.js', false);
        $response->assertDontSee('mlp-overview__split', false);
        // Shared enquire partial rendered twice (hero + final)
        $this->assertGreaterThanOrEqual(2, substr_count($response->getContent(), 'name="start_timeline"'));
        $response->assertSee('archive-closing', false);
        $response->assertSee('archive-closing__form', false);
        $response->assertSee('Second chance to enquire', false);
        $response->assertSee('<h3 class="footer__newsletter-title">Stay Updated</h3>', false);
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
}
