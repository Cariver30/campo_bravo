<?php



use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Puedes agregar el middleware global aquí si es necesario.
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Puedes agregar excepciones globales aquí si es necesario.
    })
    ->create();

