<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\EmployeeMiddleware;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Foundation\Application;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up'
    )
    ->withMiddleware(function ($middleware): void {

        // Web middleware group
        $middleware->group('web', [
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // API middleware group
        $middleware->group('api', [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // Alias for custom middleware (pass as array!)
        $middleware->alias([
            'superadmin' => SuperAdminMiddleware::class,
            'admin' => AdminMiddleware::class,
            'fleet_assistant' => EmployeeMiddleware::class,
            'booking_officer' => EmployeeMiddleware::class,
            'user' => UserMiddleware::class,
        ]);
    })
    ->withExceptions(function ($exceptions): void {
        //
    })
    ->create();
