<?php

namespace Tests\Feature;

use App\Mail\GenericFormMail;
use App\Models\ZapierWebhook;
use App\Support\ZapierEvents;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ProgramEnquiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_enquiry_allows_empty_optional_message(): void
    {
        Mail::fake();

        $response = $this->from('/programs/sample')->post('/programs/enquire', [
            'programme' => 'Executive MBA',
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '+971 50 000 0000',
            'country' => 'UAE',
            'study_mode' => 'Online',
            'qualification' => 'bachelor',
            'message' => '',
        ]);

        $response->assertRedirect('/programs/sample');
        $response->assertSessionHas('success');

        Mail::assertSent(GenericFormMail::class, function (GenericFormMail $mail) {
            $values = collect($mail->rows)->pluck('value', 'label');

            return $values->get('Programme') === 'Executive MBA'
                && $values->get('Country') === 'UAE'
                && $values->get('Study mode') === 'Online'
                && $values->get('Qualification') === "Bachelor's Degree"
                && ! $values->has('Message');
        });
    }

    public function test_enquiry_requires_core_fields(): void
    {
        $response = $this->post('/programs/enquire', []);

        $response->assertSessionHasErrors(['name', 'email', 'phone']);
    }

    public function test_enquiry_dispatches_zapier_webhook_when_configured(): void
    {
        Mail::fake();
        Http::fake([
            'hooks.zapier.com/*' => Http::response('ok', 200),
        ]);

        ZapierWebhook::create([
            'event_key' => ZapierEvents::PROGRAM_ENQUIRY_SUBMITTED,
            'url' => 'https://hooks.zapier.com/hooks/catch/enquiry',
            'is_enabled' => true,
        ]);

        $response = $this->from('/programs/sample')->post('/programs/enquire', [
            'programme' => 'Executive MBA',
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '+971 50 000 0000',
            'country' => 'UAE',
            'study_mode' => 'Online',
            'qualification' => 'bachelor',
            'message' => 'Interested in intake dates.',
        ]);

        $response->assertRedirect('/programs/sample');

        Http::assertSent(function ($request) {
            return $request->url() === 'https://hooks.zapier.com/hooks/catch/enquiry'
                && $request['name'] === 'Jane Doe'
                && $request['programme'] === 'Executive MBA'
                && $request['_event'] === ZapierEvents::PROGRAM_ENQUIRY_SUBMITTED;
        });
    }
}
