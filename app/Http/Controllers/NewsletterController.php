<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterSubscribeRequest;
use App\Services\FormMailer;
use App\Services\ZapierWebhookDispatcher;
use App\Services\ZohoCampaignsService;
use App\Support\ZapierEvents;

class NewsletterController extends Controller
{
    public function subscribe(
        NewsletterSubscribeRequest $request,
        FormMailer $formMailer,
        ZohoCampaignsService $campaigns,
    ) {
        if (filled($request->input('website'))) {
            return response()->json([
                'ok' => true,
                'message' => 'Thank you for subscribing.',
            ]);
        }

        $email = $request->validated('email');
        $synced = $campaigns->subscribe($email);

        $formMailer->send([
            'Email' => $email,
            'Submitted at' => now()->toDateTimeString(),
        ], 'New newsletter signup');

        app(ZapierWebhookDispatcher::class)->dispatch(ZapierEvents::NEWSLETTER_SUBSCRIBED, [
            'email' => $email,
            'submitted_at' => now()->toDateTimeString(),
        ]);

        return response()->json([
            'ok' => true,
            'message' => $synced
                ? 'Almost there — please check your inbox and confirm your subscription.'
                : 'Thank you for subscribing.',
        ]);
    }
}
