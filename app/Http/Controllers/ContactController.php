<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Models\Program;
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

        $allPrograms = Program::where('is_active', true)
            ->hasPublicSlug()
            ->with('programCategory:id,name,slug')
            ->orderBy('title')
            ->get(['id', 'title', 'slug', 'program_category_id']);

        return view('pages.contact', compact('site', 'contactPage', 'contactSeo', 'allPrograms'));
    }

    /**
     * Handle contact form submission.
     */
    public function submit(ContactFormRequest $request)
    {
        $validated = $request->validated();

        // Honeypot anti-spam check:
        // Visually-hidden field 'website' should remain empty.
        // If filled, silently drop and behave as if the submission succeeded.
        if (!empty($validated['website'])) {
            return redirect()->route('thank-you', ['return' => url()->previous()]);
        }

        $preferredProgram = '';
        if (filled($validated['preferred_program'] ?? null)) {
            $preferredProgram = Program::where('id', $validated['preferred_program'])->value('title') ?? '';
        }

        app(FormMailer::class)->send([
            'Name' => $validated['name'] ?? '',
            'Email' => $validated['email'] ?? '',
            'Phone' => $validated['phone'] ?? '',
            'Preferred Program' => $preferredProgram,
            'Message' => $validated['message'] ?? '',
        ], 'New Contact Form Submission from '.($validated['name'] ?? 'Guest'), [
            'reply_to' => $validated['email'] ?? null,
        ]);

        app(ZapierWebhookDispatcher::class)->dispatch(ZapierEvents::CONTACT_SUBMITTED, array_merge($validated, [
            'preferred_program' => $preferredProgram,
        ]));

        return redirect()->route('thank-you', ['return' => url()->previous()]);
    }
}
