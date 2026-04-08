<?php

namespace App\Services\Auth;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Str;
use App\Services\Settings\OtpService;
use Illuminate\Support\Facades\Mail;
use App\Mail\DeviceLimitMail;
class AuthService
{
    public function __construct(private OtpService $otpService) {}
    public function register(array $data, Request $request): array
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $tokenResult = $this->createDeviceToken($user, $request);
        return [
            'status' => 'success',
            'user' => $user,
            'token' => $tokenResult['token'],
            'message' => __('auth.successRegister'),
            'code' => 200
        ];
    }

    public function login(array $credentials, Request $request): array
    {
        if (!Auth::attempt($credentials)) {
            return [
                'status' => 'failed',
                'message' => __('auth.failed'),
                'code' => 401
            ];
        }
        $user = Auth::user();
        if ($user->tokens()->count() >= 3) {

            try {
                $code = $this->otpService->generate($user, 'device_limit');
                Mail::to($user->email)->send(new DeviceLimitMail($code));
                $devices = $user->tokens()
                    ->select(['id', 'name', 'device_id', 'browser', 'platform', 'last_used_at'])
                    ->get();
                return [
                    'status' => 'device_limit',
                    'message' => 'Max devices reached. OTP sent to your email.',
                    'code' => 423,
                    'data' => $devices
                ];

            } catch (\RuntimeException $e) {
                return [
                    'status' => 'rate_limited',
                    'message' => $e->getMessage(),
                    'code' => 429
                ];
            }
        }
        $tokenResult = $this->createDeviceToken($user, $request);
        if ($tokenResult['already_logged_in']) {
            return [
                'status' => 'already_logged_in',
                'message' => 'You are already logged in from this device',
                'code' => 409
            ];
        }

        return [
            'status' => 'success',
            'user' => $user,
            'token' => $tokenResult['token'],
            'code' => 200
        ];

    }
    private function generateDeviceName(Agent $agent): string
    {
        return implode(' - ', array_filter([$agent->device(), $agent->browser(), $agent->platform()])) ?: 'Unknown Device';
    }

    private function createDeviceToken(User $user, Request $request): array
    {
        $agent = new Agent();
        $agent->setUserAgent($request->header('User-Agent'));

        $deviceName = $this->generateDeviceName($agent);
        $deviceId = $request->header('X-Device-Id') ?: (string) Str::uuid();

        $existingToken = $user->tokens()->where('device_id', $deviceId)
            ->first();

        if ($existingToken) {
            return [
                'already_logged_in' => true,
                'token' => null
            ];
        }
        $tokenInstance = $user->createToken($deviceName);

        $tokenInstance->accessToken->forceFill([
            'device_id'  => $deviceId,
            'name' => $deviceName,
            'ip_address' => $request->ip(),
            'browser'    => $agent->browser(),
            'platform'   => $agent->platform(),
            'device'     => $agent->device(),
            'last_used_at' => now(),
        ])->save();

        return [
            'already_logged_in' => false,
            'token' => $tokenInstance->plainTextToken
        ];
    }

    public function verifyDevice(array $data, Request $request): array
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return [
                'status' => 'failed',
                'message' => 'User not found',
                'code' => 404
            ];
        }

        if (!$this->otpService->verify($user, 'device_limit', $data['otp'])) {
            return [
                'status' => 'failed',
                'message' => 'Invalid or expired OTP',
                'code' => 422
            ];        }

        $user->tokens()->where('id', $data['logout_device_id'])->delete();
        $tokenResult = $this->createDeviceToken($user, $request);

        return [
            'status' => 'success',
            'user' => $user,
            'token' => $tokenResult['token'],
            'code' => 200
        ];
    }

    public function refreshToken(Request $request): array
    {
        $user = $request->user();
        $currentToken = $user->currentAccessToken();

        $attributes = [
            'device_id'    => $currentToken->device_id,
            'ip_address'   => $request->ip(), // تحديث الـ IP للجديد
            'user_agent'   => $request->header('User-Agent'),
            'browser'      => $currentToken->browser,
            'platform'     => $currentToken->platform,
            'device'       => $currentToken->device,
            'last_used_at' => now(),
        ];

        $deviceName = $currentToken->name;

        $currentToken->delete();

        $newToken = $user->createToken($deviceName);
        $newToken->accessToken->forceFill($attributes)->save();

        return [
            'status' => 'success',
            'token'  => $newToken->plainTextToken,
            'code'   => 200
        ];
    }
}
