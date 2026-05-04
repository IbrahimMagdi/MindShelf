<?php
declare(strict_types=1);

namespace App\Application\User\UseCases;

use App\Application\User\Ports\TokenServiceInterface;

final readonly class GetUserDevicesUseCase
{
    public function __construct(
        private TokenServiceInterface $tokenService,
    ) {}

    public function execute(int $userId): int
    {
        return $this->tokenService->getActiveDevicesCount($userId);
    }
}
