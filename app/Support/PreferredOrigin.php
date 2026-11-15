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

        return preg_replace('~^https?://(?:www\.)?mbalondon\.org\.uk(?=[/?#]|$)~i', self::URL, $url);
    }
}
