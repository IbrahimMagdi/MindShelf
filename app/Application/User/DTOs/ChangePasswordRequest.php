<?php
declare(strict_types=1);

namespace App\Application\User\DTOs;

final readonly class ChangePasswordRequest
{
    public function __construct(
        public int $userId,
        public string $currentPassword,
        public string $newPassword,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            userId: $data['user_id'],
            currentPassword: $data['current_password'],
            newPassword: $data['new_password'],
        );
    }
}
