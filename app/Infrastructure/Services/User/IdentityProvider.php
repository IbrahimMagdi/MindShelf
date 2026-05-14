<?php
declare(strict_types=1);

namespace App\Infrastructure\Services\User;

use App\Application\User\Ports\IdentityProviderInterface;
use App\Domain\User\Entities\UserEntity;
use App\Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

final readonly class IdentityProvider implements IdentityProviderInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function getCurrentUser(): ?UserEntity
    {
        $userId = Auth::id();
        if(!$userId){
            return null;
        }
        return $this->userRepository->findById($userId);
    }
}
