<?php

namespace App\Services\Auth;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

use App\Services\Settings\OtpService;

class ForgotPasswordService
{
    public function __construct(protected OtpService $otpService){}
    public function sendResetLink(string $email): array
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return [
                'success' => false,
                'message' => __('auth.userNotFound'),
            ];
        }
        try {
            $code = $this->otpService->generate($user, 'password_reset');

            Mail::to($user->email)->send(new ResetPasswordMail($user, $code));

            return [
                'success' => 'success',
                'message' => __('auth.successForgot'),
            ];

        } catch (\RuntimeException $e) {
            return [
                'success' => 'failed',
                'message' => $e->getMessage(),
                'code' => 429
            ];
        }
    }
}
