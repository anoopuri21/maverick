<?php

namespace Tests\Feature;

use App\Mail\GenericFormMail;
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
        $response->assertSee('name="specialization"', false);
        $response->assertSee('name="qualification"', false);
        $response->assertDontSee('mlp-trust__hub', false);
        $response->assertSee('mlp-trust__featured', false);
        $response->assertSee('mlp-trust__score', false);
        $response->assertSee('4.8', false);
        $response->assertSee('400+ Reviews', false);
        $response->assertSee('Trusted by learners across the UAE', false);
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
        $response->assertSee('General MBA', false);
        $response->assertSee('Rushford Business School', false);
        $response->assertSee('mlp-mba__uni-photo', false);
        $response->assertSee('Master\'s programs', false);
        $response->assertSee('Master of Laws (LLM)', false);
        $response->assertSee('University of Wolverhampton', false);
        $response->assertSee('mlp-masters__uni-photo', false);
        $response->assertSee('Online MBA & Master\'s Fees in UAE');
        $response->assertSee('Request Fee Details', false);
        $response->assertSee('Get Scholarship Eligibility Check', false);
        $response->assertSee('Built for working professionals', false);
        $response->assertSee('mlp-class__industry-frame', false);
        $response->assertSee('Marketing', false);
        $response->assertSee('Cyber Security', false);
        $response->assertSee('mlp-class__industry-photo', false);
        $response->assertSee('Southeast Asia', false);
        $response->assertSee('Career Growth After MBA', false);
        $response->assertSee('mlp-career__frame', false);
        $response->assertSee('mlp-career__photo', false);
        $response->assertSee('Aisha Rahman', false);
        $response->assertSee('Previous', false);
        $response->assertSee('Our Alumni Work at Top Companies', false);
        $response->assertSee('mlp-alumni__rail', false);
        $response->assertSee('mlp-alumni__frame', false);
        $response->assertSee('mlp-alumni__logo', false);
        $response->assertSee('Join our growing network', false);
        $response->assertSee('Built for working professionals', false);
        $response->assertSee('mlp-learning__plate', false);
        $response->assertSee('Live + on-demand', false);
        $response->assertSee('Weekend-friendly rhythm', false);
        $response->assertSee('Check Eligibility', false);
        $response->assertSee('Awarded by recognised partner universities', false);
        $response->assertSee('mlp-partners__stage', false);
        $response->assertSee('mlp-partners__frame', false);
        $response->assertSee('What working professionals say', false);
        $response->assertSee('mlp-testimonials__rail', false);
        $response->assertSee('Sara Al Maktoum', false);
        $response->assertSee('Why working professionals choose online', false);
        $response->assertSee('mlp-compare__matrix', false);
        $response->assertSee('Career continuity', false);
        $response->assertSee('Questions applicants ask first', false);
        $response->assertSee('mlp-faq__list', false);
        $response->assertSee('mlp-prose', false);
        $response->assertSee('Are the degrees internationally recognised?', false);
        $response->assertSee('Ready to shape your future?', false);
        $response->assertSee('id="mlp-overview"', false);
        $response->assertSee('mlp-overview__plate', false);
        $response->assertSee('mlp-overview__plate-img', false);
        $response->assertSee('mlp-overview__split', false);
        // Shared enquire partial rendered twice (hero + final)
        $this->assertGreaterThanOrEqual(2, substr_count($response->getContent(), 'name="start_timeline"'));
        $response->assertSee('mlp-final__stage', false);
        $response->assertSee('Second chance to enquire', false);
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

        $this->assertFalse(\App\Filament\Pages\ManageMbaMastersLanding::shouldRegisterNavigation());
    }

    public function test_enquiry_sends_zoho_mail_notification(): void
    {
        Mail::fake();

        $response = $this->from('/online-mba-masters-uae')->post('/online-mba-masters-uae/enquire', [
            'name' => 'Alex Student',
            'email' => 'alex@example.com',
            'phone' => '+971500000000',
            'country' => 'UAE',
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
                && $values->get('Country') === 'UAE'
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
