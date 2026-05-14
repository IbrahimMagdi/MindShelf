<?php
declare(strict_types=1);

namespace App\Application\User\UseCases;

use App\Application\User\Ports\TokenServiceInterface;
use App\Infrastructure\Services\User\IdentityProvider;
use DomainException;

final readonly class GetUserDevicesUseCase
{
    public function __construct(
        private IdentityProvider $identityProvider,
        private TokenServiceInterface $tokenService,
    ) {}

    public function execute(): array
    {
        $user = $this->identityProvider->getCurrentUser();
        if(!$user){
            throw new DomainException('Unauthorized');
        }
        return $this->tokenService->getUserDevices($user->getId());
    }
}
