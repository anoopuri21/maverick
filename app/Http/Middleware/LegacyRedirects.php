<?php

namespace App\Http\Middleware;

use App\Models\Insight;
use App\Models\Program;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * 301-redirect legacy WordPress URLs (mbalondon.org.uk) to the new Laravel site.
 *
 * Runs before routing, so it also catches URLs that no route matches.
 * Anything that is not listed in config/redirects.php falls straight through
 * to normal routing (and eventually the normal 404 page).
 */
class LegacyRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('redirects.enabled', false)) {
            return $next($request);
        }

        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            return $next($request);
        }

        $path = trim($request->path(), '/');

        if ($path === '' || $path === '/') {
            return $next($request);
        }

        foreach (['exactMatch', 'smartMatch', 'patternMatch'] as $matcher) {
            $target = $this->{$matcher}($path);

            if (blank($target)) {
                continue;
            }

            if ($response = $this->redirectTo($request, (string) $target)) {
                return $response;
            }
        }

        return $next($request);
    }

    private function exactMatch(string $path): ?string
    {
        $map = (array) config('redirects.exact', []);

        return isset($map[$path]) ? (string) $map[$path] : null;
    }

    /**
     * "{prefix}/{slug}" URLs: use the real new page when the slug still exists.
     */
    private function smartMatch(string $path): ?string
    {
        foreach ((array) config('redirects.smart', []) as $type => $config) {
            foreach ((array) ($config['prefixes'] ?? []) as $prefix) {
                $pattern = '#^'.preg_quote((string) $prefix, '#').'/([^/]+)/?$#i';

                if (preg_match($pattern, $path, $matches) !== 1) {
                    continue;
                }

                $slug = rawurldecode($matches[1]);

                return $this->lookup($type, $slug) ?? (string) ($config['fallback'] ?? '/');
            }
        }

        return null;
    }

    private function patternMatch(string $path): ?string
    {
        foreach ((array) config('redirects.patterns', []) as $pattern => $target) {
            if (preg_match('#'.$pattern.'#i', $path) !== 1) {
                continue;
            }

            return (string) preg_replace('#'.$pattern.'#i', (string) $target, $path);
        }

        return null;
    }

    private function lookup(string $type, string $slug): ?string
    {
        try {
            if ($type === 'programs') {
                $exists = Program::query()
                    ->where('is_active', true)
                    ->where('slug', $slug)
                    ->exists();

                return $exists ? route('programs.show', $slug, absolute: false) : null;
            }

            if (in_array($type, ['blogs', 'news'], true)) {
                $exists = Insight::published()
                    ->where('slug', $slug)
                    ->exists();

                return $exists ? '/'.$slug : null;
            }
        } catch (Throwable $e) {
            // Never break the page because of a redirect lookup problem.
            report($e);
        }

        return null;
    }

    /**
     * Build the 301 response, or null when the target would loop back on itself.
     */
    private function redirectTo(Request $request, string $target): ?Response
    {
        $target = trim($target);

        if ($target === '') {
            return null;
        }

        if ($target !== '/' && ! preg_match('#^https?://#i', $target)) {
            $target = '/'.ltrim($target, '/');
        }

        // Safety net: never redirect a URL to itself (infinite loop).
        if ($target === '/'.ltrim($request->path(), '/')) {
            return null;
        }

        // Preserve query strings (utm_*, tracking params, ...).
        $query = $request->getQueryString();
        if (filled($query) && ! str_contains($target, '?')) {
            $target .= '?'.$query;
        }

        return redirect()->to($target, 301);
    }
}
