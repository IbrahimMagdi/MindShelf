<?php
declare(strict_types=1);

namespace App\Application\User\DTOs;
use Illuminate\Http\UploadedFile;

final readonly class UpdateProfileImageRequest
{
    public function __construct(
        public ?UploadedFile $image = null,
        public bool $removeImage = false
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            image: $data['image'] ?? null,
            removeImage: $data['remove_image'] ?? false,
        );
    }
}
