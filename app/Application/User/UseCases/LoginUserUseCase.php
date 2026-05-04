<?php
declare(strict_types=1);

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\LoginUserRequest;
use App\Application\User\Ports\DeviceDetectorInterface;
use App\Application\User\Ports\TokenServiceInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\Email;
use DomainException;
use App\Domain\User\Exceptions\DeviceLimitReachedException;
final readonly class LoginUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private TokenServiceInterface $tokenService,
        private DeviceDetectorInterface $deviceDetector,
    ) {}

    public function execute(LoginUserRequest $request): array
    {
        $email = new Email($request->email);
        $user = $this->userRepository->findByEmail($email);
        if (!$user || !$user->verifyPassword($request->password)) {
            throw new DomainException('Invalid credentials');
        }

        $deviceInfo = $this->deviceDetector->getCurrentDeviceInfo();
        $deviceId = $deviceInfo['device_id'];
        if ($this->tokenService->isDeviceSessionActive($user->getId(), $deviceId)) {
            throw new DomainException('You are already logged in from this device.');
        }

        $activeDevicesCount = $this->tokenService->getActiveDevicesCount($user->getId());
        if ($activeDevicesCount >= 3) {
            $devices = $this->tokenService->getUserDevices($user->getId());
            throw new DeviceLimitReachedException($devices);
        }

        $tokens = $this->tokenService->createDeviceToken($user, $deviceInfo);
        return [
            'user' => $user,
            'tokens' => $tokens,
        ];
    }
}
