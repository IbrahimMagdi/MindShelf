<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Application\User\DTOs\SwapDeviceRequest;
use App\Application\User\UseCases\SwapDeviceUseCase;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\VerifySwapRequest;
use App\Http\Resources\Api\UserResource;
use Illuminate\Http\JsonResponse;

final class VerifySwapController extends ApiController
{
    public function __construct(private readonly SwapDeviceUseCase $useCase) {}

    public function __invoke(VerifySwapRequest $request): JsonResponse
    {
        $dto = SwapDeviceRequest::fromArray($request->validated());

        $result = $this->useCase->execute($dto);

        return $this->success([
            'user' => new UserResource($result['user']),
            'access_token' => $result['tokens']['access_token'],
            'refresh_token' => $result['tokens']['refresh_token'],
        ], 'Device swapped and login successful');
    }
}
