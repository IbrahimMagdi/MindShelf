<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\Setting\Device;

use App\Application\User\UseCases\LogoutSpecificDeviceUseCase;
use App\Http\Controllers\Api\ApiController;
use App\Application\User\DTOs\LogoutSpecificDeviceRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Api\Setting\LogoutSpecificDeviceRequest as LogoutRequest;

final class LogoutController extends ApiController
{
    public function __construct(private readonly LogoutSpecificDeviceUseCase $useCase) {}

    public function __invoke(LogoutRequest $request): JsonResponse
    {
        $dto = LogoutSpecificDeviceRequest::fromArray($request->validated());
        $this->useCase->execute($dto);
        return $this->success(message:'Device logged out successfully');
    }
}
