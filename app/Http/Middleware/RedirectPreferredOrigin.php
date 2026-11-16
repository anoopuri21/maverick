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

        if (strtolower($request->getHost()) === 'mbalondon.org.uk' && $request->isSecure()) {
            return $next($request);
        }

        // The raw request URI preserves encoded path and query. Never build the target from Host.
        // 308 preserves methods and bodies for form submissions and webhooks.
        return redirect()->away(PreferredOrigin::URL.$request->getRequestUri(), $request->isMethodSafe() ? 301 : 308);
    }
}
