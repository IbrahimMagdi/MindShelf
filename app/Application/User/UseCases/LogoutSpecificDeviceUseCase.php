<?php
declare(strict_types=1);

namespace App\Application\User\UseCases;

use App\Application\User\Ports\IdentityProviderInterface;
use App\Application\User\Ports\DeviceDetectorInterface;
use App\Application\User\Ports\TokenServiceInterface;
use App\Application\User\DTOs\LogoutSpecificDeviceRequest;
use DomainException;

final readonly class LogoutSpecificDeviceUseCase
{
    public function __construct(
        private IdentityProviderInterface $identityProvider,
        private DeviceDetectorInterface $deviceDetector,
        private TokenServiceInterface $tokenService,
    ) {}

    public function execute(LogoutSpecificDeviceRequest $request): void
    {
        $user = $this->identityProvider->getCurrentUser();
        if(!$user){
            throw new DomainException('Unauthorized');
        }

        $currentDeviceInfo = $this->deviceDetector->getCurrentDeviceInfo();
        $currentDeviceId = $currentDeviceInfo['device_id'];
        if ($request->deviceId === $currentDeviceId) {
            throw new DomainException('Cannot logout from the device you are currently using');
        }

        $devices = $this->tokenService->getUserDevices($user->getId());
        $deviceIds = array_column($devices, 'device_id');
        if (!in_array($request->deviceId, $deviceIds, true)) {
            throw new DomainException('Device not found');
        }

        $this->tokenService->revokeDeviceToken($user->getId(), $request->deviceId);
    }
}
