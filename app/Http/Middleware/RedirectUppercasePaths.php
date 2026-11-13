<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Permanently redirect mixed-case URLs to their lowercase form.
 *
 * Laravel matches route paths case-sensitively, so /Programs/bsc-psychology
 * misses the /programs/{slug} route and returns 404 while the lowercase URL
 * works. Instead of duplicating every route in both cases (and still missing
 * /PROGRAMS, /Programs/..., etc.), any safe request whose path contains an
 * uppercase ASCII letter is 301'd to the lowercase equivalent. The query
 * string is passed through untouched.
 */
class RedirectUppercasePaths
{
    public function handle(Request $request, Closure $next): Response
    {
        // Only GET/HEAD/etc. — never turn a POST into a redirect.
        if (! $request->isMethodSafe()) {
            return $next($request);
        }

        $path = $request->getPathInfo();
        $lowercasePath = strtolower($path);

        if ($path === $lowercasePath) {
            return $next($request);
        }

        $query = $request->getQueryString();

        return redirect($lowercasePath.($query !== null && $query !== '' ? '?'.$query : ''), 301);
    }
}
