<?php
declare(strict_types=1);

namespace App\Application\User\Ports;

interface TokenOperationsInterface
{
    public function deleteByDeviceId(int $userId, string $deviceId): void;

    public function createAccessToken(int $userId, string $deviceName, array $meta): array;

    public function createRefreshToken(int $userId, string $deviceName, array $meta): array;

    public function countActiveTokens(int $userId): int;

    public function revokeByDeviceId(int $userId, string $deviceId): void;

    public function getDevicesMetadata(int $userId): array;
    public function hasActiveSession(int $userId, string $deviceId): bool;
    public function findRefreshToken(string $plainToken): ?array;
    public function revokeRefreshToken(string $plainToken): void;
}
