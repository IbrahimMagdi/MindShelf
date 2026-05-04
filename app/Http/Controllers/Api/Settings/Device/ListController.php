<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\Settings\Device;

use App\Application\User\UseCases\GetUserDevicesUseCase;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ListController extends ApiController
{
    public function __construct(
        private GetUserDevicesUseCase $useCase,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $count = $this->useCase->execute($request->user()->id);

        return $this->success([
            'active_devices_count' => $count,
        ]);
    }
}
