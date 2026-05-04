<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\Profile;

use App\Application\User\DTOs\UpdateProfileRequest;
use App\Application\User\UseCases\UpdateUserProfileUseCase;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Profile\UpdateProfileRequest as UpdateProfileFormRequest;
use App\Http\Resources\Api\UserResource;
use Illuminate\Http\JsonResponse;

final class UpdateController extends ApiController
{
    public function __construct(
        private UpdateUserProfileUseCase $useCase,
    ) {}

    public function __invoke(UpdateProfileFormRequest $request): JsonResponse
    {
        $dto = UpdateProfileRequest::fromArray([
            'user_id' => $request->user()->id,
            ...$request->validated(),
        ]);

        $user = $this->useCase->execute(
            userId: $dto->userId,
            name: $dto->name,
            bio: $dto->bio,
            gender: $dto->gender,
            birthdate: $dto->birthdate,
        );

        return $this->success(
            new UserResource($user),
            'Profile updated successfully'
        );
    }
}
