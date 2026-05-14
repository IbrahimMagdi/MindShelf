<?php
declare(strict_types=1);

namespace App\Infrastructure\Services\User;

use App\Application\User\Ports\TokenOperationsInterface;
use App\Application\User\Ports\TokenServiceInterface;
use App\Domain\User\Entities\UserEntity;
use App\Domain\User\Repositories\UserRepositoryInterface;

class TokenServiceImpl implements TokenServiceInterface
{
    public function __construct(
        private TokenOperationsInterface $tokenOps,
        private UserRepositoryInterface $userRepository
    ) {}

    public function createDeviceToken(UserEntity $user, array $deviceInfo): array
    {
        $deviceId = $deviceInfo['device_id'];
        $deviceName = $deviceInfo['device_name'] ?? 'Unknown Device';

        $this->tokenOps->deleteByDeviceId($user->getId(), $deviceId);
        $access = $this->tokenOps->createAccessToken(
            $user->getId(),
            $deviceName,
            $deviceInfo
        );
        $refresh = $this->tokenOps->createRefreshToken(
            $user->getId(),
            $deviceName,
            $deviceInfo
        );

        return [
            'access_token' => $access['token'],
            'refresh_token' => $refresh['token'],
            'device_id' => $deviceId,
        ];
    }

    public function getActiveDevicesCount(int $userId): int
    {
        return $this->tokenOps->countActiveDevices($userId);
    }

    public function getUserDevices(int $userId): array
    {
        return $this->tokenOps->getDevicesMetadata($userId);
    }

    public function revokeDeviceToken(int $userId, string $deviceId): void
    {
        $this->tokenOps->revokeByDeviceId($userId, $deviceId);
    }

    public function isDeviceSessionActive(int $userId, string $deviceId): bool
    {
        return $this->tokenOps->hasActiveSession($userId, $deviceId);
    }

    public function refreshAccessToken(string $refreshToken, string $deviceId):array
    {
        $tokenData = $this->tokenOps->findRefreshToken($refreshToken);
        if(!$tokenData){
            throw new \DomainException('Invalid or expired refresh token');
        }
        if($tokenData['device_id'] !== $deviceId)
        {
            throw new \DomainException('Refresh token does not match device');
        }
        $user = $this->userRepository->findById($tokenData['user_id']);
        if(!$user) {
            throw new \DomainException('User not found');
        }
        $this->tokenOps->deleteByDeviceId($user->getId(), $deviceId);
        $access = $this->tokenOps->createAccessToken(
            $user->getId(), $tokenData['name'],
            ['device_id' => $tokenData['device_id']]);
        $refresh = $this->tokenOps->createRefreshToken(
            $user->getId(), $tokenData['name'],
            ['device_id' => $tokenData['device_id']]
        );
        return [
            'access_token' => $access['token'],
            'refresh_token' => $refresh['token'],
            'device_id' => $deviceId,
        ];
    }
    public function revokeAllExceptDevice(int $userId, string $exceptDeviceId): void
    {
        $devices = $this->tokenOps->getDevicesMetadata($userId);
        foreach($devices as $device){
            $deviceId = $device['device_id'];
            if($deviceId === $exceptDeviceId){
                continue;
            }
            $this->tokenOps->revokeByDeviceId($userId, $deviceId);
        }
    }
}
