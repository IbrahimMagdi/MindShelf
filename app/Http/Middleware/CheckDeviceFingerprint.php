<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDeviceFingerprint
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->currentAccessToken()) {
            return $next($request);
        }

        $currentToken = $request->user()->currentAccessToken();
        $headerDeviceId = $request->header('X-Device-Id');

        if (!$headerDeviceId || $headerDeviceId !== $currentToken->device_id) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Security Breach: Device mismatch. Please login again.',
            ], 403);
        }
        return $next($request);
    }
}
