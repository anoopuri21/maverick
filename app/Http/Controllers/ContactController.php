<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Mail\ContactFormSubmitted;
use App\Settings\ContactPageSettings;
use App\Settings\ContactSeoSettings;
use App\Settings\SiteSettings;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Display the Contact Us page.
     */
    public function index()
    {
        $site = safe_settings(SiteSettings::class);
        $contactPage = safe_settings(ContactPageSettings::class);
        $contactSeo = safe_settings(ContactSeoSettings::class);

        return view('pages.contact', compact('site', 'contactPage', 'contactSeo'));
    }

    /**
     * Handle contact form submission.
     */
    public function submit(ContactFormRequest $request)
    {
        $validated = $request->validated();

        // Honeypot anti-spam check:
        // Visually-hidden field 'website' should remain empty.
        // If filled, silently drop/redirect as if successful.
        if (!empty($validated['website'])) {
            return back()->with('success', 'Thank you! We\'ll get back to you within 24 hours.');
        }

        $site = safe_settings(SiteSettings::class);
        $recipient = $site->email ?? config('mail.contact_recipient') ?? 'admissions@mbalondon.org.uk';

        try {
            Mail::to($recipient)->send(new ContactFormSubmitted($validated));
        } catch (\Exception $e) {
            // Silently log or continue to ensure the user receives the success state in case of SMTP misconfig in dev.
            logger()->error('Failed to send contact email: ' . $e->getMessage());
        }

        // Zapier integration (non-blocking)
        if ($webhookUrl = config('services.zapier.contact_webhook_url')) {
            try {
                \Illuminate\Support\Facades\Http::timeout(5)->post($webhookUrl, $validated);
            } catch (\Throwable $e) {
                report($e); // log failure, do not block the user-facing flow
            }
        }

        $contactPage = safe_settings(ContactPageSettings::class);
        $successMessage = $contactPage->success_message ?? 'Thank you! We\'ll get back to you within 24 hours.';

        return back()->with('success', $successMessage);
    }
}
