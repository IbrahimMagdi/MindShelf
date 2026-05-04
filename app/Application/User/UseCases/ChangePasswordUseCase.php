<?php
declare(strict_types=1);

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\ChangePasswordRequest;
use App\Application\User\Ports\PasswordHasherInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;

final readonly class ChangePasswordUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher,
    ) {}

    public function execute(ChangePasswordRequest $request): void
    {
        $user = $this->userRepository->findById($request->userId);

        if (!$user) {
            throw new \DomainException('User not found');
        }

        if (!$user->verifyPassword($request->currentPassword)) {
            throw new \DomainException('Current password is incorrect');
        }

        $newHashedPassword = $this->passwordHasher->hash($request->newPassword);
        $user->changePassword($newHashedPassword);

        $this->userRepository->save($user);
    }
}
