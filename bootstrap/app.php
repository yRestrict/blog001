<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Middleware\CheckBanned;
use App\Models\Setting;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'preventBackHistory' => PreventBackHistory::class,
            'role'               => CheckRole::class,
            'checkBanned'        => CheckBanned::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            // Só customiza 404 no frontend, admin usa o padrão do Laravel
            if (! $request->is('admin/*')) {
                return response()->view('errors.404', [
                    'settings' => Setting::first(),
                ], 404);
            }
        });
    })->create();