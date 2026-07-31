<?php

declare(strict_types=1);

use App\Http\Middleware\EnsureCompanySelected;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'ensure.company' => EnsureCompanySelected::class,
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Renderiza páginas customizadas para 403/404/500/503
        $exceptions->render(function (HttpExceptionInterface $e, $request) {
            $status = $e->getStatusCode();

            if (in_array($status, [403, 404, 419, 429, 500, 503], true)) {
                $view = 'errors.'.$status;

                if (view()->exists($view)) {
                    if (app()->environment('testing')) {
                        return response()->view($view, [
                            'exception' => $e,
                            'request' => $request,
                        ], $status);
                    }

                    return response()->view($view, [
                        'exception' => $e,
                        'request' => $request,
                    ], $status);
                }
            }

            return null;
        });
    })->create();
