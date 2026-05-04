<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\User\Mapper;

use App\Domain\User\Entities\UserEntity;
use App\Domain\User\Enums\Gender;
use App\Domain\User\Enums\UserRole;
use App\Domain\User\ValueObjects\BirthDate;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\HashedPassword;
use App\Domain\User\ValueObjects\Name;
use App\Models\User as UserModel;

class UserMapper
{
    // Model (DB) → Entity (Domain)
    public function toEntity(UserModel $model): UserEntity
    {
        return UserEntity::fromPersistence(
            id: $model->id,
            name: new Name($model->name),
            email: new Email($model->email),
            emailVerifiedAt: $model->email_verified_at
                ? $model->email_verified_at->toDateTimeImmutable()
                : null,
            role: UserRole::from($model->role),
            birthdate: $model->birthdate
                ? new BirthDate(\DateTimeImmutable::createFromMutable($model->birthdate->toDateTime()))
                : null,
            gender: Gender::from($model->gender),
            image: $model->image,
            bio: $model->bio,
            password: $model->password
                ? HashedPassword::fromHash($model->password)
                : null
        );
    }

    // Entity (Domain) → Model (DB) - للـ Save
    public function toModel(UserEntity $entity): UserModel
    {
        $model = UserModel::find($entity->getId()) ?? new UserModel();

        $model->name = $entity->getName()->value();
        $model->email = $entity->getEmail()->value();
        $model->role = $entity->getRole()->value;
        $model->gender = $entity->getGender()->value;

        $emailVerifiedAt = $entity->getEmailVerifiedAt();
        $model->email_verified_at = $emailVerifiedAt
            ? $emailVerifiedAt->format('Y-m-d H:i:s')
            : null;

        $birthdate = $entity->getBirthdate();
        $model->birthdate = $birthdate ? $birthdate->toDateTime() : null;

        $model->image = $entity->getImage();
        $model->bio = $entity->getBio();

        $password = $entity->getPasswordHash();
        $model->password = $password ? $password->value() : null;

        return $model;
    }
}
