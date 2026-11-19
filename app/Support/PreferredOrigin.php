<?php

namespace App\Support;

use Illuminate\Http\Request;

class PreferredOrigin
{
    /** Kept as the historical default; use url()/hosts() for configuration. */
    public const URL = 'https://mbalondon.org.uk';

    public static function url(): string
    {
        return rtrim((string) config('site.preferred_origin.url', self::URL), '/');
    }

    /** @return list<string> */
    public static function hosts(): array
    {
        $hosts = config('site.preferred_origin.hosts', ['mbalondon.org.uk', 'www.mbalondon.org.uk']);
        $values = is_array($hosts) ? $hosts : [];

        // Always include the canonical host, even if only PREFERRED_ORIGIN_URL
        // was changed; otherwise canonical/redirect logic silently stops.
        $values[] = (string) parse_url(self::url(), PHP_URL_HOST);

        return array_values(array_unique(array_filter(array_map(
            static fn (mixed $host): string => strtolower(trim((string) $host)),
            $values
        ))));
    }

    public static function appliesTo(Request $request): bool
    {
        return in_array(strtolower($request->getHost()), self::hosts(), true);
    }

    /** Normalize only known production origins; retain deliberate other-domain overrides. */
    public static function normalize(?string $url, Request $request): ?string
    {
        if (! $url || ! self::appliesTo($request)) {
            return $url;
        }

        $origin = self::url();
        $parts = parse_url($origin);
        $host = strtolower((string) ($parts['host'] ?? ''));

        if ($host === '') {
            return $url;
        }

        // Include the port in both the match and the replacement: an origin
        // such as http://localhost:8000 must not become localhost:8000:8000.
        $authority = $host;
        if (isset($parts['port'])) {
            $authority .= ':'.$parts['port'];
        }

        $wwwPrefix = str_starts_with($host, 'www.') ? '' : '(?:www\\.)?';

        $normalized = preg_replace(
            '~^https?://'.$wwwPrefix.preg_quote($authority, '~').'(?=[/?#]|$)~i',
            $origin,
            $url
        ) ?? $url;

        // Laravel's url()->current() omits the slash for the homepage, while
        // route('home') includes it. Keep both signals consistent.
        return preg_replace(
            '~^'.preg_quote($origin, '~').'(?=[?#]|$)~',
            $origin.'/',
            $normalized
        ) ?? $normalized;
    }
}
