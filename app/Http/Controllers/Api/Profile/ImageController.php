<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\Profile;

use App\Application\User\DTOs\UpdateProfileImageRequest;
use App\Application\User\UseCases\UpdateProfileImageUseCase;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Profile\UpdateImageRequest;
use Illuminate\Http\JsonResponse;

final class ImageController extends ApiController
{
    public function __construct(
        private UpdateProfileImageUseCase $useCase,
    ) {}

    public function store(UpdateImageRequest $request): JsonResponse
    {
        $dto = UpdateProfileImageRequest::fromArray([
            'user_id' => $request->user()->id,
            'image' => $request->file('image'),
        ]);

        $this->useCase->execute($dto);

        return $this->success(
            null,
            'Profile image updated'
        );
    }

    public function destroy(Request $request): JsonResponse
    {
        $dto = UpdateProfileImageRequest::fromArray([
            'user_id' => $request->user()->id,
            'image' => null,
        ]);

        $this->useCase->execute($dto);

        return $this->success(
            null,
            'Profile image removed'
        );
    }
}
