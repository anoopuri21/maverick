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
        // Trust all proxies (required for LiteSpeed/CloudFlare/Shared Hosting)
        $middleware->trustProxies(at: '*');

        // Redirect unauthenticated users to Filament admin login (not default 'login' route)
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('filament.admin.auth.login');
            }
            return '/';
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Log all errors
        $exceptions->report(function (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Application error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        });

        // Custom friendly 500 page only for public frontend routes in production
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