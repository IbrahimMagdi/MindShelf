<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Register;
use App\Http\Requests\Auth\Login;
use App\Http\Resources\Auth\UserResource;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{

    public function __construct(protected AuthService $authService)
    {}

    public function register(Register $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

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



}
