<?php
declare(strict_types=1);

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\LoginUserRequest;
use App\Application\User\Ports\DeviceDetectorInterface;
use App\Application\User\Ports\TokenServiceInterface;
use App\Application\User\Results\LoginResult;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\Email;
final readonly class LoginUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private TokenServiceInterface $tokenService,
        private DeviceDetectorInterface $deviceDetector,
    ) {}

    public function execute(LoginUserRequest $request): LoginResult

    {
        $email = new Email($request->email);
        $user = $this->userRepository->findByEmail($email);
        if (!$user || !$user->verifyPassword($request->password)) {
            return LoginResult::invalidCredentials();
        }
        if (!$user->isEmailVerified()) {
            return LoginResult::emailNotVerified();
        }

        $deviceInfo = $this->deviceDetector->getCurrentDeviceInfo();
        $deviceId = $deviceInfo['device_id'];
        if ($this->tokenService->isDeviceSessionActive($user->getId(), $deviceId)) {
            return LoginResult::alreadyLoggedIn();
        }

        $activeDevicesCount = $this->tokenService->getActiveDevicesCount($user->getId());
        if ($activeDevicesCount >= 3) {
            $devices = $this->tokenService->getUserDevices($user->getId());
            return LoginResult::deviceLimit($devices);
        }

        $tokens = $this->tokenService->createDeviceToken($user, $deviceInfo);
        return LoginResult::success($user, $tokens);
    }
}
