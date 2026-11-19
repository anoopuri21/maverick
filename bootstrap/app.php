<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Trusted proxies can be enumerated per deployment; "*" is retained as
        // the default for shared hosting where the upstream IP is not stable.
        // Read the environment directly: this middleware callback runs before
        // the config repository is available during HTTP kernel resolution.
        $trusted = env('TRUSTED_PROXIES', '*');
        $middleware->trustProxies(
            at: $trusted === '*'
                ? '*'
                : array_values(array_filter(array_map('trim', explode(',', (string) $trusted)))),
            headers: Request::HEADER_X_FORWARDED_FOR
                | Request::HEADER_X_FORWARDED_HOST
                | Request::HEADER_X_FORWARDED_PROTO
                | Request::HEADER_X_FORWARDED_PORT
        );

        // Consolidate only the known production hosts; local and preview hosts are unaffected.
        $middleware->append(\App\Http\Middleware\RedirectPreferredOrigin::class);

        // /Programs/... -> /programs/... (Laravel routes are case-sensitive)
        $middleware->append(\App\Http\Middleware\RedirectUppercasePaths::class);

        // Redirect unauthenticated users to Filament admin login (not default 'login' route)
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('filament.admin.auth.login');
            }
            return '/';
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Custom friendly 500 page only for public frontend routes in production.
        // (Do not double-log here — Laravel's default reporter already writes to LOG_CHANNEL.)
        $exceptions->render(function (\Throwable $e, Request $request) {
            // Never touch admin routes — let Filament handle them
            if ($request->is('admin') || $request->is('admin/*')) {
                return null;
            }

            // Never touch API/AJAX/Livewire requests
            if ($request->expectsJson() || $request->hasHeader('X-Livewire')) {
                return null;
            }

            // Only show friendly page for real 500 errors in production
            if (app()->isProduction() && !config('app.debug')) {
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
                    return null; // Let Laravel handle 404, 403, etc.
                }
                return response()->view('errors.500', [], 500);
            }

            return null;
        });
    })->create();