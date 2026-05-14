<?php

namespace App\Application\User\Ports;

use App\Domain\User\Entities\UserEntity;

interface TokenServiceInterface
{
    public function createDeviceToken(UserEntity $user, array $deviceInfo): array;
    public function getActiveDevicesCount(int $userId): int;
    public function getUserDevices(int $userId): array;
    public function revokeDeviceToken(int $userId, string $deviceId): void;
    public function isDeviceSessionActive(int $userId, string $deviceId): bool;
    public function refreshAccessToken(string $refreshToken, string $deviceId): array;
    public function revokeAllExceptDevice(int $userId, string $exceptDeviceId): void;
}
