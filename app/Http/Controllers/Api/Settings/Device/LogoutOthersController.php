<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\Settings\Device;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;

final class LogoutOthersController extends ApiController
{
    public function __invoke(): JsonResponse
    {
        // TODO: Implement LogoutOtherDevicesUseCase
        return $this->success(
            null,
            'Other devices logged out'
        );
    }
}
