<?php
declare(strict_types=1);

namespace App\Application\User\Results;

use App\Domain\User\Entities\UserEntity;

final readonly class LoginResult
{
    private function __construct(
        public bool $isSuccess,
        public string $status,
        public ?string $message = null,
        public ?UserEntity $user = null,
        public ?array $tokens = null,
        public ?array $devices = null,
    ) {}

    public static function success(UserEntity $user, array $tokens): self
    {
        return new self(
            isSuccess: true,
            status: 'success',
            message: 'Login successful',
            user: $user,
            tokens: $tokens,
        );
    }

    public static function invalidCredentials(): self
    {
        return new self(
            isSuccess: false,
            status: 'invalid_credentials',
            message: 'Invalid credentials',
        );
    }

    public static function deviceLimit(array $devices): self
    {
        return new self(
            isSuccess: false,
            status: 'device_limit',
            message: 'Maximum device limit reached.',
            devices: $devices,
        );
    }

    public static function alreadyLoggedIn(): self
    {
        return new self(
            isSuccess: false,
            status: 'already_logged_in',
            message: 'You are already logged in from this device.',
        );
    }

    public static function emailNotVerified(): self
    {
        return new self(
            isSuccess: false,
            status: 'email_not_verified',
            message: 'Please verify your email before logging in.',
        );
    }
}
