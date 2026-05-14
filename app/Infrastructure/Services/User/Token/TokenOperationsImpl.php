<?php
declare(strict_types=1);

namespace App\Infrastructure\Services\User\Token;

use App\Domain\User\Exceptions\TokenNotFoundException;
use App\Application\User\Ports\TokenOperationsInterface;
use App\Models\PersonalAccessToken;
use App\Models\User as UserModel;
use Carbon\Carbon;

class TokenOperationsImpl implements TokenOperationsInterface
{
    public function deleteByDeviceId(int $userId, string $deviceId): void
    {
        PersonalAccessToken::where('tokenable_id', $userId)
            ->where('device_id', $deviceId)
            ->delete();
    }


    public function createAccessToken(int $userId, string $deviceName, array $meta): array
    {
        $user = UserModel::find($userId);

        $token = $user->createToken($deviceName . '-access');

        $token->accessToken->forceFill([
            'device_id' => $meta['device_id'],
            'ip_address' => $meta['ip'] ?? null,
            'browser' => $meta['browser'] ?? null,
            'platform' => $meta['platform'] ?? null,
            'device' => $meta['device'] ?? null,
            'token_type' => 'access',
            'access_expires_at' => now()->addMinutes(10),
        ])->save();

        return ['token' => $token->plainTextToken];
    }

    public function createRefreshToken(int $userId, string $deviceName, array $meta): array
    {
        $user = UserModel::find($userId);

        $token = $user->createToken($deviceName . '-refresh');

        $token->accessToken->forceFill([
            'device_id' => $meta['device_id'] ?? null,
            'token_type' => 'refresh',
            'ip_address' => $meta['ip'] ?? null,
            'user_agent' => $meta['user_agent'] ?? null,
            'browser'    => $meta['browser'] ?? null,
            'platform'   => $meta['platform'] ?? null,
            'device'     => $meta['device'] ?? null,
            'refresh_expires_at' => now()->addDays(7),
        ])->save();

        return ['token' => $token->plainTextToken];
    }


    public function countActiveDevices(int $userId): int
    {
        return PersonalAccessToken::where('tokenable_id', $userId)
            ->where('token_type', 'refresh')
            ->where(function ($query) {
                $query->whereNull('refresh_expires_at')
                    ->orWhere('refresh_expires_at', '>', Carbon::now());
            })
            ->count();
    }

//    public function countActiveTokens(int $userId): int
//    {
//        return PersonalAccessToken::where('tokenable_id', $userId)
//            ->where('token_type', 'access')
//            ->where(function ($query) {
//                $query->whereNull('access_expires_at')
//                    ->orWhere('access_expires_at', '>', Carbon::now());
//            })
//            ->count();
//    }


    public function revokeByDeviceId(int $userId, string $deviceId): void
    {
        $exists = PersonalAccessToken::where('tokenable_id', $userId)
        ->where('device_id', $deviceId)
        ->exists();
        if (!$exists) {
            throw TokenNotFoundException::forDevice($deviceId);
        }
        PersonalAccessToken::where('tokenable_id', $userId)
            ->where('device_id', $deviceId)
            ->delete();
    }
    public function hasActiveSession(int $userId, string $deviceId): bool
    {
        return PersonalAccessToken::where('tokenable_id', $userId)
            ->where('device_id', $deviceId)
            ->where('token_type', 'refresh')
            ->where(function($query) {
                $query->whereNull('refresh_expires_at')
                    ->orWhere('refresh_expires_at', '>', now());
            })->exists();
    }

    public function getDevicesMetadata(int $userId): array
    {
        return PersonalAccessToken::where('tokenable_id', $userId)
            ->where('token_type', 'refresh')
            ->where(function ($query) {
                $query->whereNull('refresh_expires_at')
                    ->orWhere('refresh_expires_at', '>', Carbon::now());
            })
            ->get(['id', 'name', 'device_id', 'platform', 'browser', 'last_used_at', 'created_at', 'updated_at'])
            ->map(fn($token) => [
                'id' => $token->id,
                'device_id' => $token->device_id,
                'name' => $token->name,
                'platform' => $token->platform,
                'browser' => $token->browser,
                'last_used_at' => $token->last_used_at,
                'created_at' => $token->created_at,
                'updated_at' => $token->updated_at,
            ])->toArray();
    }

    public function findRefreshToken(string $plainToken): ?array
    {
        $token = PersonalAccessToken::findToken($plainToken);

        if (!$token || $token->token_type !== 'refresh') {
            return null;
        }

        if ($token->refresh_expires_at && $token->refresh_expires_at->isPast()) {
            return null;
        }

        return [
            'user_id' => $token->tokenable_id,
            'device_id' => $token->device_id,
            'name' => $token->name,
        ];
    }

    public function revokeRefreshToken(string $plainToken): void
    {
        $token = PersonalAccessToken::findToken($plainToken);

        if ($token) {
            $token->delete();
        }
    }

}
