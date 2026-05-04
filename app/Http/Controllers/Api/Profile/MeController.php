<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\Profile;

use App\Application\User\UseCases\GetUserProfileUseCase;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Api\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class MeController extends ApiController
{
    public function __construct(
        private GetUserProfileUseCase $useCase,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $user = $this->useCase->execute($request->user()->id);

        return $this->success(new UserResource($user));
    }
}
