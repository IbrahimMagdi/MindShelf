<?php
declare(strict_types=1);

namespace App\Application\User\DTOs;

final readonly class RegisterUserRequest
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $gender,
        public string $role,
        public ?string $birthdate = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            gender: $data['gender'],
            role: $data['role'],
            birthdate: $data['birthdate'] ?? null,
        );
    }
}
