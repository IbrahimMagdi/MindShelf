<?php
declare(strict_types=1);

namespace App\Application\User\UseCases;

use App\Application\User\Ports\DeviceDetectorInterface;
use App\Application\User\Ports\TokenServiceInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\Email;
use App\Application\User\DTOs\SwapDeviceRequest;
use DomainException;

final readonly class SwapDeviceUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private TokenServiceInterface $tokenService,
        private DeviceDetectorInterface $deviceDetector
    ) {}

    public function execute(SwapDeviceRequest $request): array
    {
        $email = new Email($request->email);
        $user = $this->userRepository->findByEmail($email);
        if (!$user || !$user->verifyPassword($request->password)) {
            throw new DomainException('Invalid credentials');
        }
        $this->tokenService->revokeDeviceToken($user->getId(), $request->logoutDeviceId);
        $deviceInfo = $this->deviceDetector->getCurrentDeviceInfo();
        $tokens = $this->tokenService->createDeviceToken($user, $deviceInfo);
        return [
            'user' => $user,
            'tokens' => $tokens,
        ];
    }
}
