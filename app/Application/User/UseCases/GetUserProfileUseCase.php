<?php
declare(strict_types=1);

namespace App\Application\User\UseCases;

use App\Domain\User\Entities\UserEntity;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Application\User\Ports\IdentityProviderInterface;
use App\Application\User\Results\ProfileResult;

final readonly class GetUserProfileUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private IdentityProviderInterface $identityProvider,
    ) {}

    public function execute(int $userId): ProfileResult
    {
        $user = $this->userRepository->findById($userId);
        $currentUser = $this->identityProvider->getCurrentUser();
        if (!$user || !$currentUser) {
            return ProfileResult::notFound();
        }

        if ($currentUser->getId() === $user->getId()) {
            return ProfileResult::my($user);
        }
        return ProfileResult::other($user);
    }
}
