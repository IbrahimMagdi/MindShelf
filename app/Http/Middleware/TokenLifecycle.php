<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Shared\Http\Responses\ApiResponse; // الـ Namespace الموحد بتاعك

class TokenLifecycle
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->user()?->currentAccessToken();

        if (!$token) {
            return $next($request);
        }

        // 1. فحص انتهاء صلاحية الـ Access Token (الـ 10 دقائق)
        if ($token->access_expires_at && $token->access_expires_at->isPast()) {
            $token->delete(); // نمسحه من الداتابيز عشان ميبقاش فيه زحمة توكنز ميتة
            return ApiResponse::error('Your session has expired. Please refresh token or login again.', 401);
        }

        // 2. تأمين الروات: نمنع استخدام الـ Refresh Token في أي روت غير روت التجديد
        if ($token->token_type === 'refresh' && !$request->is('api/auth/refresh*')) {
            return ApiResponse::unauthorized('Unauthorized: This token is for refresh only. Access token required.');
        }

        // تحديث آخر وقت استخدام (اختياري للمراقبة)
        $token->forceFill(['last_used_at' => now()])->save();

        return $next($request);
    }
}
