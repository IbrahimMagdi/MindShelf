<?php
declare(strict_types=1);

namespace App\Http\Resources\Api;

use App\Domain\User\Entities\UserEntity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property UserEntity $resource
 */
final class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var UserEntity $user */
        $user = $this->resource;
        $path = $user->getImage();


        return [
            'id' => $user->getId(),
            'name' => $user->getName()->value(),
            'email' => $user->getEmail()->value(),
            'role' => [
                'key' => $user->getRole()->value,
                'label' => $user->getRole()->label(),
            ],
            'gender' => [
                'key' => $user->getGender()->value,
                'label' => $user->getGender()->label(),
            ],
            'birthdate' => $user->getBirthdate()?->toDateTime()->format('Y-m-d'),
            'age' => $user->getAge(),
            'is_adult' => $user->getBirthdate()?->isAdult(),
            'bio' => $user->getBio(),
            'image' => $this->formatImageUrl($path),
            'can_publish' => $user->getRole()->canPublish(),
        ];
    }
    private function formatImageUrl(string $path): string
    {
        if (str_starts_with($path, 'images/')) {
            return asset($path);
        }
        return asset('storage/' . $path);
    }
}
