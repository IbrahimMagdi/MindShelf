<?php
declare(strict_types=1);

namespace App\Application\User\DTOs;

final readonly class ResetPasswordRequest
{
    public function __construct(
        public string $email,
        public string $code,
        public string $password,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            code: $data['code'],
            password: $data['password'],
        );
    }
}
