<?php

namespace App\Support;

use Illuminate\Http\Request;

class PreferredOrigin
{
    public const URL = 'https://mbalondon.org.uk';

    public static function appliesTo(Request $request): bool
    {
        return in_array(strtolower($request->getHost()), ['mbalondon.org.uk', 'www.mbalondon.org.uk'], true);
    }

    /** Normalize only known production origins; retain deliberate other-domain overrides. */
    public static function normalize(?string $url, Request $request): ?string
    {
        if (! $url || ! self::appliesTo($request)) {
            return $url;
        }

        $normalized = preg_replace('~^https?://(?:www\.)?mbalondon\.org\.uk(?=[/?#]|$)~i', self::URL, $url);

        // Laravel's url()->current() omits the slash for the homepage, while
        // route('home') includes it. Keep both signals consistent.
        return preg_replace('~^https://mbalondon\.org\.uk(?=[?#]|$)~', self::URL.'/', $normalized);
    }
}
