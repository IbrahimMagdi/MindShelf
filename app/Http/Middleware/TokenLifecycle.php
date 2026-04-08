<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\ApiResponse;

class TokenLifecycle
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->user()?->currentAccessToken();

        if (!$token) {
            return $next($request);
        }

        if ($token->expires_at && $token->expires_at->isPast()) {
            $token->delete();
            return ApiResponse::error('Token expired', 401);
        }

        $token->forceFill([
            'last_used_at' => now(),
        ])->save();

        return $next($request);    }
}
