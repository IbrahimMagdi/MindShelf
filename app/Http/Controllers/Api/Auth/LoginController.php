<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Application\User\DTOs\LoginUserRequest;
use App\Application\User\UseCases\LoginUserUseCase;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\Api\UserResource;
use App\Http\Resources\Settings\DeviceResource;
use App\Shared\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

final class LoginController extends ApiController
{
    public function __construct(
        private readonly LoginUserUseCase $useCase,
    ) {}

    public function __invoke(LoginRequest $request): JsonResponse
    {
        $dto = LoginUserRequest::fromArray($request->validated());
        $result = $this->useCase->execute($dto);
        return match($result->status) {
            'success' => $this->success(
                data: [
                    'user' => new UserResource($result->user),
                    'access_token' => $result->tokens['access_token'],
                    'refresh_token' => $result->tokens['refresh_token'],
                ],
                message: $result->message
            ),
            'device_limit' => ApiResponse::error(
                message: $result->message,
                status: 423,
                data: DeviceResource::collection($result->devices)
            ),
            'already_logged_in' => $this->error(
                message: $result->message,
                status: 409
            ),
            'invalid_credentials' => $this->error(
                message: $result->message,
                status: 401
            ),
            'email_not_verified' => $this->error(
                message: $result->message,
                status: 403
            ),
            default => $this->error(
                message: 'An unexpected error occurred',
            ),
        };
    }
}
