<?php

namespace Tests\Feature;

use App\Mail\GenericFormMail;
use App\Services\FormMailer;
use App\Settings\SiteSettings;
use App\Settings\ZohoSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class FormMailerTest extends TestCase
{
    use RefreshDatabase;

    public function test_normalize_rows_skips_empty_and_honeypot_fields(): void
    {
        $rows = app(FormMailer::class)->normalizeRows([
            'Name' => 'Ada',
            'Phone' => '',
            'website' => 'http://spam.test',
            '_token' => 'abc',
            'Message' => '<b>Hello</b> world',
        ]);

        $this->assertSame([
            ['label' => 'Name', 'value' => 'Ada'],
            ['label' => 'Message', 'value' => 'Hello world'],
        ], $rows);
    }

    public function test_send_uses_default_mailer_when_zoho_disabled(): void
    {
        Mail::fake();

        $ok = app(FormMailer::class)->send([
            'Email' => 'visitor@example.com',
            'Note' => '',
        ], 'Test subject');

        $this->assertTrue($ok);
        Mail::assertSent(GenericFormMail::class, function (GenericFormMail $mail) {
            $values = collect($mail->rows)->pluck('value', 'label');

            return $mail->hasTo('admissions@mbalondon.org.uk')
                && $values->get('Email') === 'visitor@example.com'
                && ! $values->has('Note');
        });
    }

    public function test_send_bccs_unique_valid_additional_recipients(): void
    {
        Mail::fake();

        $zoho = app(ZohoSettings::class);
        $zoho->default_recipient = 'primary@example.com';
        $zoho->additional_recipients = [
            ['email' => 'ops@example.com'],
            ['email' => 'ops@example.com'],
            ['email' => 'primary@example.com'],
            ['email' => 'not-an-email'],
            ['email' => str_repeat('a', 255).'@example.com'],
        ];
        $zoho->save();

        $this->assertTrue(app(FormMailer::class)->send([
            'Name' => 'Ada',
        ], 'Test subject'));

        Mail::assertSent(GenericFormMail::class, function (GenericFormMail $mail) {
            return $mail->hasTo('primary@example.com')
                && $mail->hasBcc('ops@example.com')
                && count($mail->bcc) === 1
                && ! $mail->hasBcc('primary@example.com')
                && ! $mail->hasBcc('not-an-email')
                && ! $mail->hasBcc(str_repeat('a', 255).'@example.com');
        });
    }

    public function test_form_recipient_override_takes_precedence_while_additional_recipients_remain_bcc(): void
    {
        Mail::fake();

        $zoho = app(ZohoSettings::class);
        $zoho->default_recipient = 'primary@example.com';
        $zoho->additional_recipients = [
            ['email' => 'ops@example.com'],
            ['email' => 'form@example.com'],
        ];
        $zoho->save();

        $this->assertTrue(app(FormMailer::class)->send([
            'Name' => 'Ada',
        ], 'Test subject', [
            'to' => 'form@example.com',
        ]));

        Mail::assertSent(GenericFormMail::class, function (GenericFormMail $mail) {
            return $mail->hasTo('form@example.com')
                && ! $mail->hasTo('primary@example.com')
                && $mail->hasBcc('ops@example.com')
                && ! $mail->hasBcc('form@example.com');
        });
    }

    public function test_send_uses_fazil_as_final_fallback_when_other_recipients_are_empty(): void
    {
        Mail::fake();

        $zoho = app(ZohoSettings::class);
        $zoho->default_recipient = null;
        $zoho->additional_recipients = [];
        $zoho->save();

        $site = app(SiteSettings::class);
        $site->email = null;
        $site->save();

        $this->assertTrue(app(FormMailer::class)->send([
            'Name' => 'Ada',
        ], 'Test subject'));

        Mail::assertSent(GenericFormMail::class, fn (GenericFormMail $mail): bool =>
            $mail->hasTo('fazil@mbalondon.org.uk')
        );
    }

    public function test_send_never_throws_on_empty_fields(): void
    {
        $this->assertFalse(app(FormMailer::class)->send([], 'Empty'));
    }
}
