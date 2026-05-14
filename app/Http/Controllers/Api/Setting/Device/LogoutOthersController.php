<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\Setting\Device;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;
use App\Application\User\UseCases\LogoutOtherDevicesUseCase;

final class LogoutOthersController extends ApiController
{
    public function __construct(
        private readonly LogoutOtherDevicesUseCase $useCase
    ) {}
    public function __invoke(): JsonResponse
    {
        $this->useCase->execute();
        return $this->success(message:'Other devices logged out successfully');
    }
}
