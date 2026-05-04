<?php
declare(strict_types=1);

namespace App\Application\User\DTOs;
use Illuminate\Http\UploadedFile;

final readonly class UpdateProfileImageRequest
{
    public function __construct(public int $userId, public ?UploadedFile $imageFile) {}

    public static function fromArray(array $data): self
    {
        return new self(userId: $data['user_id'], imageFile: $data['image'] ?? null);
    }
}
