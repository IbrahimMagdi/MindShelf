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
use App\Helpers\ApiResponse;

class AuthController extends Controller
{

    public function __construct(protected AuthService $authService){}

    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return ApiResponse::success(null, __('auth.verify'));
    }

    public function resendVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return ApiResponse::error(__('auth.already_verified'));
        }
        $request->user()->sendEmailVerificationNotification();
        return ApiResponse::success(null, __('auth.verification_link_sent'));
    }
    public function register(Register $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());
        $user = tap($result['user'], function ($user) use ($result) {
            $user->sendEmailVerificationNotification();
            $user->token = $result['token'];
        });
        return ApiResponse::success(new UserResource($user), __('auth.successRegister'), 201);

    }

    public function login(Login $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());

        if (!$result['success']) {
            return ApiResponse::error($result['message'], 401);
        }
        $user = tap($result['user'], function ($user) use ($result) {$user->token = $result['token'];});
        return ApiResponse::success(new UserResource($user), __('auth.successLogin'), 201);
    }

    public function forgotPassword(ForgotPasswordRequest $request ,ForgotPasswordService $service)
    {
        $result = $service->sendResetLink($request->email);
        if(!$result['success']){
            return ApiResponse::error($result['message']);
        }
        return ApiResponse::success(['token' => $result['token']], $result['message']);
    }

    public function resetPassword(ResetPasswordRequest $request ,ResetPasswordService $service)
    {
        $result = $service->reset($request->validated());
        if (!$result['success']) {
            return ApiResponse::error($result['message']);
        }
        return ApiResponse::success(null, $result['message']);
    }




}
