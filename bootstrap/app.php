<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->prepend(\App\Http\Middleware\SetLocale::class);
        $middleware->alias([
            'device.check' => \App\Http\Middleware\CheckDeviceFingerprint::class,
            'token.lifecycle' => \App\Http\Middleware\TokenLifecycle::class,
        ]);

    })

    ->withExceptions(function (Exceptions $exceptions): void {

        // Not Found
        $exceptions->render(function (NotFoundHttpException $e) {
            $previous = $e->getPrevious();
            if ($previous instanceof ModelNotFoundException) {
                $model = class_basename($previous->getModel());
                return ApiResponse::error("{$model} not found", 404);
            }
            return ApiResponse::error("Resource not found", 404);
        });

        // Validation
        $exceptions->render(function (ValidationException $e) {
            return ApiResponse::validation($e->errors());
        });

        // Email Not Verified (403) - الحل هنا
        $exceptions->render(function (HttpException $e) {
            // Check if it's the email verification error
            if ($e->getStatusCode() === 403 &&
                str_contains($e->getMessage(), 'email address is not verified')) {

                return ApiResponse::error(
                    'Your email address is not verified. Please verify your email first.',
                    403
                );
            }

            // أي 403 تاني (غير متعلق بالإيميل)
            if ($e->getStatusCode() === 403) {
                return ApiResponse::error($e->getMessage() ?: 'Forbidden', 403);
            }

            // سيب الـ Exception يكمل لو مش 403 (عشان يتعامل معاه handler تاني)
            return null;
        });

        // Unauthorized (401) - لو محتاجه كمان
        $exceptions->render(function (HttpException $e) {
            if ($e->getStatusCode() === 401) {
                return ApiResponse::error($e->getMessage() ?: 'Unauthorized', 401);
            }
            return null;
        });
    })->create();
