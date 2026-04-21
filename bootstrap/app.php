<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(_DIR_))
    ->withRouting(
        web: _DIR_.'/../routes/web.php',
        api: _DIR_.'/../routes/api.php',
        commands: _DIR_.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $exception, $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], Response::HTTP_UNAUTHORIZED);
            }

            return null;
        });

        $exceptions->render(function (\Throwable $exception, $request) {
            if (($request->is('api/*') || $request->expectsJson())
                && str_starts_with($exception::class, 'Tymon\\JWTAuth\\Exceptions\\')) {
                return response()->json(['message' => 'Unauthenticated.'], Response::HTTP_UNAUTHORIZED);
            }

            return null;
        });
    })->create();