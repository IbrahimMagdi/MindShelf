<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Application\User\DTOs\RegisterUserRequest;
use App\Application\User\UseCases\RegisterUserUseCase;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\Api\UserResource;
use Illuminate\Http\JsonResponse;

final class RegisterController extends ApiController
{
    public function __construct(
        private readonly RegisterUserUseCase $useCase,
    ) {}

    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $dto = RegisterUserRequest::fromArray($request->validated());
        $result = $this->useCase->execute($dto);
        return $this->created(['user' => new UserResource($result['user'])], $result['message']);
    }
}
