<?php
declare(strict_types=1);

namespace App\Application\User\UseCases;

use App\Application\User\Ports\DeviceDetectorInterface;
use App\Application\User\Ports\IdentityProviderInterface;
use App\Application\User\Ports\TokenServiceInterface;
use DomainException;

final readonly class LogoutCurrentDeviceUseCase
{
    public function __construct(
        private IdentityProviderInterface $identityProvider,
        private DeviceDetectorInterface $deviceDetector,
        private TokenServiceInterface $tokenService,
    ) {}

    public function execute(): void
    {
        $user = $this->identityProvider->getCurrentUser();
        if(!$user){
            throw new DomainException('Unauthorized');
        }
        $deviceInfo = $this->deviceDetector->getCurrentDeviceInfo();
        $this->tokenService->revokeDeviceToken($user->getId(), $deviceInfo['device_id']);


    }
}
