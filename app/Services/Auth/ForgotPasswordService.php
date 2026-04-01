<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordService
{
    public function sendResetLink(string $email): array
    {
//        $user = User::where('email', $email)->first();
//        if (!$user) {
//            return [
//                'success' => false,
//                'message' => 'هذا الإيميل غير موجود'
//            ];
//        }
        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );
        // Mail::to($email)->send(new ResetPasswordMail($token));
        return [
            'success' => true,
            'message' => __('auth.sucssessForgot'),
            'token' => $token
        ];
    }
}
