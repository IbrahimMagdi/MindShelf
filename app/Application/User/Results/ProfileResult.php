<?php
declare(strict_types=1);

namespace App\Application\User\Results;

use App\Domain\User\Entities\UserEntity;

final readonly class ProfileResult
{
    private function __construct(
        public bool $isSuccess,
        public string $status,
        public ?string $message = null,
        public ?UserEntity $user = null,
    ) {}

    public static function my(UserEntity $user): self
    {
        return new self(
            isSuccess: true,
            status: 'myProfile',
            message: 'Profile retrieved.',
            user: $user,
        );
    }

    public static function other(UserEntity $user): self
    {
        return new self(
            isSuccess: true,
            status: 'otherProfile',
            message: 'Other profile retrieved.',
            user: $user,
        );
    }

     public static function notFound(): self
    {
        return new self(
            isSuccess: false,
            status: 'notFound',
            message: 'User not found.',
        );
    }
}
