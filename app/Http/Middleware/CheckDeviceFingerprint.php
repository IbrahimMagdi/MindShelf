<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Shared\Http\Responses\ApiResponse;

class CheckDeviceFingerprint
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $currentToken = $user?->currentAccessToken();

        // لو مفيش يوزر أو توكن، عدي الريكويست والـ auth:sanctum هيتصرف
        if (!$user || !$currentToken) {
            return $next($request);
        }

        $headerDeviceId = $request->header('X-Device-Id');

        // المقارنة بين الهيدر اللي جاي وبين اللي متخزن في التوكن وقت الـ Login/Register
        if (!$headerDeviceId || $headerDeviceId !== $currentToken->device_id) {
            return ApiResponse::forbidden('Security Breach: Device identity mismatch. Access denied.');
        }

        return $next($request);
    }
}
