<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\Entities\UserEntity;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\Email;
use App\Infrastructure\Persistence\User\Mapper\UserMapper;
use App\Models\User as UserModel;


final class UserRepositoryImpl implements UserRepositoryInterface
{
    public function __construct(private readonly UserMapper $mapper) {}

    public function findById(int $id): ?UserEntity
    {
        $model = UserModel::find($id);
        return $model ? $this->mapper->toEntity($model) : null;
    }

    public function findByEmail(Email $email): ?UserEntity
    {
        $model = UserModel::where('email', $email->value())->first();
        return $model ? $this->mapper->toEntity($model) : null;
    }
    public function save(UserEntity $user): UserEntity
    {
        $model = $this->mapper->toModel($user);
        $model->save();

        return $this->mapper->toEntity($model);
    }

    public function delete(UserEntity $user): void
    {
        UserModel::destroy($user->getId());
    }

    public function findAll(): array
    {
        return UserModel::all()
            ->map(fn (UserModel $model) => $this->mapper->toEntity($model))
            ->toArray();
    }

    public function existsByEmail(Email $email): bool
    {
        return UserModel::where('email', $email->value())->exists();
    }
}
