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
use App\Mail\WelcomeMail;

class AuthService
{
    public function __construct(private OtpService $otpService) {}
    public function register(array $data, Request $request): array
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        Mail::to($user->email)->send(new WelcomeMail($user));
        $tokenResult = $this->createDeviceToken($user, $request);
        return [
            'status' => 'success',
            'user' => $user,
            'access_token' => $tokenResult['access_token'],
            'refresh_token' => $tokenResult['refresh_token'],
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
                $agent = $this->getAgent($request);
                $code = $this->otpService->generate($user, 'device_limit');
                Mail::to($user->email)->send(new DeviceLimitMail(
                    $code,
                    $agent->browser(),
                    $agent->platform(),
                    $agent->device(),
                    $request->ip()
                ));
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
            'access_token' => $tokenResult['access_token'],
            'refresh_token' => $tokenResult['refresh_token'],
            'code' => 200
        ];

    }
    private function generateDeviceName(Agent $agent): string
    {
        return implode(' - ', array_filter([$agent->device(), $agent->browser(), $agent->platform()])) ?: 'Unknown Device';
    }
    private function getAgent(Request $request): Agent
    {
        $agent = new Agent();
        $agent->setUserAgent($request->header('User-Agent', ''));

        return $agent;
    }
    private function createDeviceToken(User $user, Request $request): array
    {
        $agent = $this->getAgent($request);
        $deviceName = $this->generateDeviceName($agent);
        $deviceId = $request->header('X-Device-Id') ?: (string) Str::uuid();

        // 💡 الأفضل: امسح القديم بتاع الجهاز ده عشان م يعملش تعارض
        $user->tokens()->where('device_id', $deviceId)->delete();

        // 1️⃣ Access Token
        $accessToken = $user->createToken($deviceName . '-access');
        $accessToken->accessToken->forceFill([
            'device_id'  => $deviceId,
            'name'       => $deviceName,
            'ip_address' => $request->ip(),
            'browser'    => $agent->browser(),
            'platform'   => $agent->platform(),
            'device'     => $agent->device(),
            'token_type' => 'access', // ✅ متوافق مع المايجريشن
            'access_expires_at' => now()->addMinutes(10),
        ])->save();

        // 2️⃣ Refresh Token
        $refreshToken = $user->createToken($deviceName . '-refresh');
        $refreshToken->accessToken->forceFill([
            'device_id'  => $deviceId,
            'name'       => $deviceName, // مهم عشان الـ Refresh يبقى عارف اسم الجهاز
            'ip_address' => $request->ip(),
            'token_type' => 'refresh', // ✅ متوافق مع المايجريشن
            'refresh_expires_at' => now()->addDays(7),
        ])->save();

        return [
            'already_logged_in' => false,
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken
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
        $currentToken = $request->user()?->currentAccessToken();

        if (!$currentToken) {
            return [
                'status' => 'failed',
                'message' => 'Unauthenticated',
                'code' => 401
            ];
        }

        // 🔥 لازم يكون refresh token
        if ($currentToken->token_type !== 'refresh') {
            return [
                'status' => 'failed',
                'message' => 'Invalid token type',
                'code' => 403
            ];
        }

        // ⛔ expired check
        if ($currentToken->refresh_expires_at && $currentToken->refresh_expires_at->isPast()) {
            $currentToken->delete();

            return [
                'status' => 'failed',
                'message' => 'Refresh token expired, please login again',
                'code' => 401
            ];
        }

        $user = $request->user();

        // 🧠 نحفظ بيانات الجهاز قبل الحذف
        $deviceId = $currentToken->device_id;
        $deviceName = $currentToken->name;

        // ❌ revoke old refresh token (rotation step)
        $currentToken->delete();

        // =========================
        // 🔥 CREATE NEW ACCESS TOKEN
        // =========================
        $newAccess = $user->createToken($deviceName . '-access');

        $newAccess->accessToken->forceFill([
            'device_id' => $deviceId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'last_used_at' => now(),
            'access_expires_at' => now()->addMinutes(10),
            'token_type' => 'access',
        ])->save();

        // =========================
        // 🔥 CREATE NEW REFRESH TOKEN
        // =========================
        $newRefresh = $user->createToken($deviceName . '-refresh');

        $newRefresh->accessToken->forceFill([
            'device_id' => $deviceId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'refresh_expires_at' => now()->addDays(7),
            'token_type' => 'refresh',
        ])->save();

        return [
            'status' => 'success',
            'access_token' => $newAccess->plainTextToken,
            'refresh_token' => $newRefresh->plainTextToken,
            'code' => 200
        ];
    }
}
