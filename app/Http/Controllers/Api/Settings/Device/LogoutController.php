<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\Settings\Device;

use App\Application\User\UseCases\LogoutDeviceUseCase;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class LogoutController extends ApiController
{
    public function __construct(
        private LogoutDeviceUseCase $useCase,
    ) {}

    public function __invoke(Request $request, string $deviceId): JsonResponse
    {
        $this->useCase->execute($request->user()->id, $deviceId);

        return $this->success(
            null,
            'Device logged out successfully'
        );
    }
}
