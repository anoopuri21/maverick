<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterSubscribeRequest;
use App\Services\FormMailer;

class NewsletterController extends Controller
{
    public function subscribe(NewsletterSubscribeRequest $request, FormMailer $formMailer)
    {
        $email = $request->validated('email');

        $formMailer->send([
            'Email' => $email,
            'Submitted at' => now()->toDateTimeString(),
        ], 'New newsletter signup');

        return response()->json([
            'ok' => true,
            'message' => 'Thank you for subscribing.',
        ]);
    }
}
