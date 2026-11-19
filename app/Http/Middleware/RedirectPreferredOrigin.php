<?php

namespace App\Http\Middleware;

use App\Support\PreferredOrigin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectPreferredOrigin
{
    public function handle(Request $request, Closure $next): Response
    {
        // ACME validation is normally served directly by the web server.
        if (! config('seo.origin_redirect_enabled') || ! PreferredOrigin::appliesTo($request) || $request->is('.well-known/acme-challenge/*')) {
            return $next($request);
        }

        // Compare the full origin, not just the host: an http:// or custom-port
        // canonical URL would otherwise redirect to itself forever.
        $parts = parse_url(PreferredOrigin::url());
        $preferredHost = strtolower((string) ($parts['host'] ?? ''));
        $preferredScheme = strtolower((string) ($parts['scheme'] ?? 'https'));
        $preferredPort = (int) ($parts['port'] ?? ($preferredScheme === 'https' ? 443 : 80));

        $requestScheme = strtolower($request->getScheme());
        $requestPort = (int) ($request->getPort() ?: ($requestScheme === 'https' ? 443 : 80));

        // Reverse proxies commonly forward the scheme but not the port. Treat
        // the other protocol's default port as "unspecified" to avoid looping.
        if ($requestScheme === 'https' && $requestPort === 80) {
            $requestPort = 443;
        } elseif ($requestScheme === 'http' && $requestPort === 443) {
            $requestPort = 80;
        }

        if (
            $preferredHost !== ''
            && strtolower($request->getHost()) === $preferredHost
            && $requestScheme === $preferredScheme
            && $requestPort === $preferredPort
        ) {
            return $next($request);
        }

        // The raw request URI preserves encoded path and query. Never build the target from Host.
        // 308 preserves methods and bodies for form submissions and webhooks.
        return redirect()->away(PreferredOrigin::url().$request->getRequestUri(), $request->isMethodSafe() ? 301 : 308);
    }
}
