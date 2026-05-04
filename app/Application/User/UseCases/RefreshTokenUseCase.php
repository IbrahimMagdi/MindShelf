<?php
declare(strict_types=1);

namespace App\Application\User\UseCases;

use App\Application\User\Ports\TokenServiceInterface;



final readonly class RefreshTokenUseCase
{
    public function __construct(
        private TokenServiceInterface $tokenService,
    ) {}

    public function execute(string $refreshToken, string $deviceId): array
    {
        return $this->tokenService->refreshAccessToken($refreshToken, $deviceId);
    }
}
