<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Register;
use App\Http\Requests\Auth\Login;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\Auth\UserResource;
use App\Services\Auth\AuthService;
use App\Services\Auth\ForgotPasswordService;
use App\Services\Auth\ResetPasswordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function __construct(protected AuthService $authService){}

    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return response()->json([
            'message' => 'تم تفعيل حسابك بنجاح.'
        ], 200);
    }

    public function resendVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'حسابك مفعل بالفعل.'], 400);
        }
        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'تم إرسال رابط تفعيل جديد.'
        ], 200);
    }
    public function register(Register $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());
        $result['user']->sendEmailVerificationNotification();
        return response()->json([
            'message' => 'تم إنشاء الحساب بنجاح',
            'user'    => new UserResource($result['user']),
            'token'   => $result['token'],
        ], 201);
    }

    public function login(Login $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());

        if (!$result['success']) {
            return response()->json(['message' => $result['message']], 401);
        }

        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'user'    => new UserResource($result['user']),
            'token'   => $result['token'],
        ]);
    }

    public function forgotPassword(ForgotPasswordRequest $request ,ForgotPasswordService $service)
    {
        $result = $service->sendResetLink($request->email);
        if(!$result['success']){
            return response()->json(['message' => $result['message']], 400);
        }
        return response()->json([
            'message' => $result['message'],
            'token' => $result['token'],
        ], 200);
    }
    public function resetPassword(ResetPasswordRequest $request ,ResetPasswordService $service)
    {
        $result = $service->reset($request->validated());
        if (!$result['success']) {
            return response()->json(['message' => $result['message']], 400);
        }
        return response()->json(['message' => $result['message']], 200);
    }




}
