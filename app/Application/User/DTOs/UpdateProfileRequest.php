<?php
declare(strict_types=1);

namespace App\Application\User\DTOs;

final readonly class UpdateProfileRequest
{
    public function __construct(
        public int $userId,
        public ?string $name = null,
        public ?string $bio = null,
        public ?string $gender = null,
        public ?string $birthdate = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            userId: $data['user_id'],
            name: $data['name'] ?? null,
            bio: $data['bio'] ?? null,
            gender: $data['gender'] ?? null,
            birthdate: $data['birthdate'] ?? null,
        );
    }
}
