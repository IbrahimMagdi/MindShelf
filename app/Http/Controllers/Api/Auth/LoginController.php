<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Application\User\DTOs\LoginUserRequest;
use App\Application\User\UseCases\LoginUserUseCase;
use App\Domain\User\Exceptions\DeviceLimitReachedException;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\Api\UserResource;
use Illuminate\Http\JsonResponse;

final class LoginController extends ApiController
{
    public function __construct(
        private readonly LoginUserUseCase $useCase,
    ) {}

    public function __invoke(LoginRequest $request): JsonResponse
    {
        try {
            $dto = LoginUserRequest::fromArray($request->validated());
            $result = $this->useCase->execute($dto);

            return $this->success([
                'user' => new UserResource($result['user']),
                'access_token' => $result['tokens']['access_token'],
                'refresh_token' => $result['tokens']['refresh_token'],
            ], 'Login successful');

        } catch (DeviceLimitReachedException $e) {
            return response()->json([
                'status' => 'device_limit',
                'message' => 'Maximum device limit reached. OTP sent to your email.',
                'code' => 423,
                'data' => [
                    'devices' => $e->getDevices()
                ]
            ], 423);
        }
    }
}
