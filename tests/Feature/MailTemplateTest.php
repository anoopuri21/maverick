<?php

namespace Tests\Feature;

use App\Mail\GenericFormMail;
use App\Settings\MailTemplateSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MailTemplateTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_renders_maverick_branding_and_default_footer(): void
    {
        $mail = new GenericFormMail('New enquiry', [
            ['label' => 'Name', 'value' => 'Ada Lovelace'],
            ['label' => 'Email', 'value' => 'ada@example.com'],
        ]);

        $html = $mail->render();

        // Maverick branding (never Laravel)
        $this->assertStringContainsString('Maverick Business Academy', $html);
        $this->assertStringContainsString('logo-white.png', $html);
        $this->assertStringNotContainsString('Laravel', $html);

        // Migration default footer
        $this->assertStringContainsString('Regards,', $html);

        // Form details
        $this->assertStringContainsString('Ada Lovelace', $html);
        $this->assertStringContainsString('ada@example.com', $html);
    }

    public function test_email_omits_header_and_footer_when_settings_empty(): void
    {
        $settings = app(MailTemplateSettings::class);
        $settings->header_html = null;
        $settings->footer_html = null;
        $settings->save();

        $mail = new GenericFormMail('Subject', [
            ['label' => 'Name', 'value' => 'Ada'],
        ]);

        $html = $mail->render();

        $this->assertStringContainsString('Ada', $html);
        $this->assertStringNotContainsString('Regards,', $html);
        $this->assertStringNotContainsString('Thanks', $html);
    }

    public function test_email_renders_admin_rich_text_header_and_footer(): void
    {
        $settings = app(MailTemplateSettings::class);
        $settings->header_html = '<p>New website enquiry below.</p>';
        $settings->footer_html = '<p>Warm regards,<br><strong>Admissions Team</strong></p>';
        $settings->save();

        $mail = new GenericFormMail('Subject', [
            ['label' => 'Name', 'value' => 'Ada'],
        ]);

        $html = $mail->render();

        $this->assertStringContainsString('New website enquiry below.', $html);
        $this->assertStringContainsString('Warm regards,', $html);
        $this->assertStringContainsString('Admissions Team', $html);
    }
}
