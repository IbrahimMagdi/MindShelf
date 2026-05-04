<?php
declare(strict_types=1);

namespace App\Application\User\UseCases;

use App\Application\User\Ports\TokenOperationsInterface;

final readonly class LogoutOtherDevicesUseCase
{
    public function __construct(
        private TokenOperationsInterface $tokenOps,
    ) {}

    public function execute(int $userId, string $currentDeviceId): int
    {
        $count = $this->tokenOps->countActiveTokens($userId);
        return $count - 1;
    }
}
