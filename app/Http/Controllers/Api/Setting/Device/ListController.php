<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\Setting\Device;

use App\Application\User\UseCases\GetUserDevicesUseCase;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Settings\DeviceResource;

final class ListController extends ApiController
{
    public function __construct(
        private readonly GetUserDevicesUseCase $useCase,
    ) {}

    public function __invoke(): JsonResponse
    {
        $devices = $this->useCase->execute();
        return $this->success(
            data: DeviceResource::collection($devices),
            message: 'Devices retrieved successfully'
        );

    }
}
