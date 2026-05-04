<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Shared\Http\Responses\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;

use App\Domain\User\Exceptions\TokenNotFoundException;
use App\Domain\Otp\Exceptions\OtpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->prepend(\App\Http\Middleware\SetLocale::class);

        // تسجيل الـ Middlewares كـ Aliases عشان تناديهم في الـ Routes بسهولة
        $middleware->alias([
            'device.check' => \App\Http\Middleware\CheckDeviceFingerprint::class,
            'token.lifecycle' => \App\Http\Middleware\TokenLifecycle::class,
        ]);

        // نصيحة: ممكن تضيفهم للـ API group عشان يتطبقوا أوتوماتيك بعد Sanctum
        $middleware->api(append: [
            'token.lifecycle',
            'device.check',
        ]);
    })


    ->withExceptions(function (Exceptions $exceptions): void {

        // ← 0. Token Not Found (404) — لازم تكون قبل DomainException
        $exceptions->render(function (TokenNotFoundException $e) {
            return ApiResponse::notFound($e->getMessage());
        });

        // ← 0. OTP Errors (422)
        $exceptions->render(function (OtpException $e) {
            return ApiResponse::error($e->getMessage(), 422);
        });

        // ← 0. Domain Exceptions العامة (400)
        // ده "الصيدية" اللي بتاخد أي DomainException مش متعامل معاه فوق
        $exceptions->render(function (\DomainException $e) {
            return ApiResponse::error($e->getMessage(), 400);
        });

        // 1. Unauthorized (401)
        $exceptions->render(function (AuthenticationException $e) {
            return ApiResponse::unauthorized('Authentication required. Please login.');
        });

        // 2. Not Found (404)
        $exceptions->render(function (NotFoundHttpException $e) {
            $previous = $e->getPrevious();
            if ($previous instanceof ModelNotFoundException) {
                $model = class_basename($previous->getModel());
                return ApiResponse::notFound("{$model} not found");
            }
            return ApiResponse::notFound("Resource not found");
        });

        // 3. Validation (422)
        $exceptions->render(function (ValidationException $e) {
            return ApiResponse::validation($e->errors());
        });

        // 4. Http Exceptions (403, 401, etc.)
        $exceptions->render(function (HttpException $e) {
            $status = $e->getStatusCode();
            $message = $e->getMessage();

            if ($status === 403) {
                if (str_contains($message, 'email address is not verified')) {
                    return ApiResponse::forbidden('Your email address is not verified. Please verify your email first.');
                }
                return ApiResponse::forbidden($message ?: 'Forbidden');
            }

            if ($status === 401) {
                return ApiResponse::unauthorized($message ?: 'Unauthorized');
            }

            return ApiResponse::error($message ?: 'Server Error', $status);
        });
    })->create();

