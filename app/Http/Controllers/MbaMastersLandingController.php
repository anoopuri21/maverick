<?php

namespace App\Http\Controllers;

use App\Http\Requests\MbaMastersLandingEnquiryRequest;
use App\Services\FormMailer;
use App\Services\ZapierWebhookDispatcher;
use App\Support\ZapierEvents;

class MbaMastersLandingController extends Controller
{
    public function enquire(
        MbaMastersLandingEnquiryRequest $request,
        FormMailer $formMailer,
        ZapierWebhookDispatcher $zapierWebhookDispatcher
    ) {
        $data = $request->validated();

        if (filled($data['website'] ?? null)) {
            // Honeypot hit: silently drop, but behave as if the submission succeeded.
            return redirect()->route('thank-you', ['return' => url()->previous()]);
        }

        $qualifications = [
            'high-school' => 'High School / Secondary',
            'diploma' => 'Diploma',
            'bachelor' => "Bachelor's Degree",
            'master' => "Master's Degree",
            'other' => 'Other',
        ];

        $timelines = [
            '1-3-months' => '1–3 months',
            '3-6-months' => '3–6 months',
            'more-than-6-months' => 'More than 6 months',
            'not-decided' => 'Not decided',
        ];

        $qualification = $data['qualification'] ?? '';
        if (isset($qualifications[$qualification])) {
            $qualification = $qualifications[$qualification];
        }

        $timeline = $data['start_timeline'] ?? '';
        if (isset($timelines[$timeline])) {
            $timeline = $timelines[$timeline];
        }

        $sent = $formMailer->send([
            'Name' => $data['name'] ?? '',
            'Email' => $data['email'] ?? '',
            'Phone / WhatsApp' => $data['phone'] ?? '',
            'Country' => $data['country'] ?? '',
            'Program interested in' => $data['program'] ?? '',
            'Preferred specialization' => $data['specialization'] ?? '',
            'Highest qualification' => $qualification,
            'How soon you want to start' => $timeline,
        ], 'MBA/Master\'s landing enquiry'.(filled($data['name'] ?? null) ? ': '.$data['name'] : ''), [
            'reply_to' => $data['email'] ?? null,
        ]);

        // Keep CRM lead capture independent from the notification-email result.
        // The dispatcher handles delivery failures without changing the form response.
        $zapierWebhookDispatcher->dispatch(ZapierEvents::MBA_MASTERS_LANDING_ENQUIRY_SUBMITTED, [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'country' => $data['country'] ?? '',
            'program' => $data['program'] ?? '',
            'specialization' => $data['specialization'] ?? '',
            'qualification' => $qualification,
            'start_timeline' => $timeline,
        ]);

        if (! $sent) {
            return back()
                ->withInput()
                ->with('error', 'We could not send your enquiry right now. Please try again or contact us directly.');
        }

        return redirect()->route('thank-you', ['return' => url()->previous()]);
    }
}
