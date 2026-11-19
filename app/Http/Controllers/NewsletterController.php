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
        $email = $request->validated('email');

        // Hidden honeypot field: bots fill it, real users never see it.
        if (filled($request->validated('website'))) {
            return response()->json([
                'ok' => true,
                'message' => 'Thank you for subscribing.',
            ]);
        }

        // Notification first: if it fails, do not subscribe or dispatch the
        // lead and then tell the visitor to try again (which could duplicate it).
        $sent = $formMailer->send([
            'Email' => $email,
            'Submitted at' => now()->toDateTimeString(),
        ], 'New newsletter signup');

        if (! $sent) {
            return response()->json([
                'ok' => false,
                'message' => 'We could not record your subscription right now. Please try again shortly.',
            ], 503);
        }

        $synced = $campaigns->subscribe($email);

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
