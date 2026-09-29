<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();
        // Une route /api protégée doit lever AuthenticationException même si un
        // client ancien omet Accept: application/json. Sans cette règle,
        // Authenticate tente de générer la route web inexistante `login` et
        // transforme un simple 401 en erreur 500.
        $middleware->redirectGuestsTo(
            fn (Request $request) => $request->is('api/*') ? null : '/',
        );
        $middleware->alias([
            'family.access' => \App\Http\Middleware\RequireFamilyAccess::class,
            'mama.access' => \App\Http\Middleware\RequireMamaAccess::class,
            'child.family' => \App\Http\Middleware\EnsureChildBelongsToFamily::class,
        ]);
        $middleware->web(append: [\App\Http\Middleware\SecurityHeaders::class]);
        $middleware->api(append: [\App\Http\Middleware\SecurityHeaders::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $exception, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['message' => 'Authentification requise.'], 401);
            }
        });
    })->create();
