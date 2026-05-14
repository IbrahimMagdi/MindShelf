<?php
declare(strict_types=1);

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\UpdateProfileImageRequest;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Application\User\Ports\ImageStorageInterface;
use App\Application\User\Ports\IdentityProviderInterface;
use DomainException;

final class UpdateProfileImageUseCase
{
    public function __construct(
        private IdentityProviderInterface $identityProvider,
        private UserRepositoryInterface $userRepository,
         private ImageStorageInterface $imageStorage,
    ) {}

    public function execute(UpdateProfileImageRequest $request): void
    {
        $user = $this->identityProvider->getCurrentUser();

        if (!$user) {
            throw new DomainException('Unauthorized');
        }



        $this->userRepository->save($user);
    }
}
