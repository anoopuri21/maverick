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
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Never crash — always graceful
        $exceptions->render(function (\Throwable $e, $request) {
            // Log all errors
            \Illuminate\Support\Facades\Log::error('Application error', [
                'message' => $e->getMessage(),
                'url' => $request->fullUrl(),
                'trace' => $e->getTraceAsString(),
            ]);

            // In production, show friendly page for public routes
            if (app()->isProduction() && !$request->is('admin/*')) {
                if ($e instanceof \Illuminate\Http\Exceptions\HttpResponseException) {
                    return null;
                }
                return response()->view('errors.500', [], 500);
            }

            return null;
        });
    })->create();
