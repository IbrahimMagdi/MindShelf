<?php
declare(strict_types=1);

namespace App\Domain\User\Repositories;

use App\Domain\User\Entities\UserEntity;
use App\Domain\User\ValueObjects\Email;

interface UserRepositoryInterface
{
    public function findByEmail(Email $email): ?UserEntity;
    public function findById(int $id): ?UserEntity;
    public function save(UserEntity $user): UserEntity;
    public function delete(UserEntity $user): void;
    public function findAll(): array;
    public function existsByEmail(Email $email): bool;
}
