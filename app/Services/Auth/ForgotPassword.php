<?php

namespace App\Services\Auth;


use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ForgotPassword
{
    public function register(array $data): array
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    public function login(array $credentials)
    {
        if (!Auth::attempt($credentials)) {
            return [
                'success' => false,
                'message' => __('auth.failed'),
            ];
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'success' => true,
            'user'    => $user,
            'token'   => $token,
        ];

    }
}



