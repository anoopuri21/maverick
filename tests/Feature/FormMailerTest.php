<?php

namespace Tests\Feature;

use App\Mail\GenericFormMail;
use App\Services\FormMailer;
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

    public function test_send_never_throws_on_empty_fields(): void
    {
        $this->assertFalse(app(FormMailer::class)->send([], 'Empty'));
    }
}
