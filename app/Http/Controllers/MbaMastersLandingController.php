<?php

namespace App\Http\Controllers;

use App\Http\Requests\MbaMastersLandingEnquiryRequest;
use App\Services\FormMailer;

class MbaMastersLandingController extends Controller
{
    public function enquire(MbaMastersLandingEnquiryRequest $request, FormMailer $formMailer)
    {
        $data = $request->validated();

        if (filled($data['website'] ?? null)) {
            return back()->with('success', 'Thank you! Our admissions team will contact you shortly.');
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

        if (! $sent) {
            return back()
                ->withInput()
                ->with('error', 'We could not send your enquiry right now. Please try again or contact us directly.');
        }

        return back()->with('success', 'Thank you! Our admissions team will guide you on eligibility, fees and next steps.');
    }
}
