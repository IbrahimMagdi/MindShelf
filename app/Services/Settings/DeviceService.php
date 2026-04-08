<?php
namespace App\Services\Settings;

use App\Models\User;


class DeviceService
{
    public function getDevices(User $user): array
    {
        $devices = $user->tokens()
            ->select(['id', 'name', 'device_id', 'device', 'browser', 'platform', 'last_used_at'])
            ->get();
        return [
            'status' => 'success',
            'data' => $devices,
            'code' => 200
            ];
    }

    public function logoutDevice(User $user, int $id): void
    {
        $token = $user->tokens()->findOrFail($id);

        if ($token->id === $user->currentAccessToken()?->id) {
            throw new \InvalidArgumentException('You cannot logout from your current device this way.');
        }

        $token->delete();
    }

    public function logoutOtherDevices(User $user): int
    {
        $currentToken = $user->currentAccessToken();
        if (!$currentToken) {
            throw new \RuntimeException('No active session found');
        }
        return $user->tokens()->where('id', '!=', $currentToken->id)->delete();
    }
}
