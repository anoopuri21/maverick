<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Services\FormMailer;
use App\Services\ZapierWebhookDispatcher;
use App\Settings\ContactPageSettings;
use App\Settings\ContactSeoSettings;
use App\Settings\SiteSettings;
use App\Support\ZapierEvents;

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

        app(FormMailer::class)->send([
            'Name' => $validated['name'] ?? '',
            'Email' => $validated['email'] ?? '',
            'Phone' => $validated['phone'] ?? '',
            'Subject' => $validated['subject'] ?? '',
            'Message' => $validated['message'] ?? '',
        ], 'New Contact Form Submission from '.($validated['name'] ?? 'Guest'), [
            'reply_to' => $validated['email'] ?? null,
        ]);

        app(ZapierWebhookDispatcher::class)->dispatch(ZapierEvents::CONTACT_SUBMITTED, $validated);

        $contactPage = safe_settings(ContactPageSettings::class);
        $successMessage = $contactPage->success_message ?? 'Thank you! We\'ll get back to you within 24 hours.';

        return back()->with('success', $successMessage);
    }
}
