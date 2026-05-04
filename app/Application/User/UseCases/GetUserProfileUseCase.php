<?php
declare(strict_types=1);

namespace App\Application\User\UseCases;

use App\Domain\User\Entities\UserEntity;
use App\Domain\User\Repositories\UserRepositoryInterface;

final readonly class GetUserProfileUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function execute(int $userId): UserEntity
    {
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            throw new \DomainException('User not found');
        }

        return $user;
    }
}
