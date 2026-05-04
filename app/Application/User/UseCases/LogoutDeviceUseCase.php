<?php
declare(strict_types=1);

namespace App\Application\User\UseCases;

use App\Application\User\Ports\TokenServiceInterface;

final readonly class LogoutDeviceUseCase
{
    public function __construct(
        private TokenServiceInterface $tokenService,
    ) {}

    public function execute(int $userId, string $deviceId): void
    {
        $this->tokenService->revokeDeviceToken($userId, $deviceId);
    }
}
