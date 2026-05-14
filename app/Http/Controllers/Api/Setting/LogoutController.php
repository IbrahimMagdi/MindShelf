<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\Setting;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;
use App\Application\User\UseCases\LogoutCurrentDeviceUseCase;
final class LogoutController extends ApiController
{
    public function __construct(
        private readonly LogoutCurrentDeviceUseCase $useCase,
    ) {}
    public function __invoke(): JsonResponse
    {
        $this->useCase->execute();
        return $this->success(message: 'Logged out successfully');
    }
}
