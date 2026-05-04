<?php
declare(strict_types=1);

namespace App\Application\User\UseCases;

use App\Domain\User\Entities\UserEntity;
use App\Domain\User\Enums\Gender;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\BirthDate;
use App\Domain\User\ValueObjects\Name;

final readonly class UpdateUserProfileUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function execute(
        int $userId,
        ?string $name = null,
        ?string $bio = null,
        ?string $gender = null,
        ?string $birthdate = null,
    ): UserEntity {
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            throw new \DomainException('User not found');
        }

        if ($name !== null) {
            $user->changeName(new Name($name));
        }

        if ($bio !== null) {
            $user->updateBio($bio);
        }

        if ($gender !== null) {
            $user->changeGender(Gender::from($gender));
        }

        if ($birthdate !== null) {
            $user->changeBirthdate(
                new BirthDate(new \DateTimeImmutable($birthdate))
            );
        }

        return $this->userRepository->save($user);
    }
}
