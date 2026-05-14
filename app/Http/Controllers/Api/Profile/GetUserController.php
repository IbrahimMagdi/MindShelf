<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\Profile;

use App\Application\User\UseCases\GetUserProfileUseCase;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Api\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class GetUserController extends ApiController
{
    public function __construct(
        private readonly GetUserProfileUseCase $useCase,
    ) {}

    public function __invoke(string $userId): JsonResponse
    {
        $result = $this->useCase->execute((int)$userId);
        return match($result->status){
            'myProfile', 'otherProfile' => $this->success(
                data: new UserResource($result->user),
                message: $result->message,
            ),
            'notFound' => $this->error(
                message: $result->message,
            ),
            default => $this->notFound(
                message: 'An unexpected error occurred',
            ),
        };

    }
}
