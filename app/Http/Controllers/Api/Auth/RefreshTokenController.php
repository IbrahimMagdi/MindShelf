<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Application\User\UseCases\RefreshTokenUseCase;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class RefreshTokenController extends ApiController
{
    public function __construct(
        private readonly RefreshTokenUseCase $useCase,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $refreshToken = $request->bearerToken();
        $deviceId = $request->header('X-Device-Id');
        if (!$refreshToken) {
            return $this->error('Refresh token required', 401);
        }
        if (!$deviceId) {
            return $this->error('Device ID required');
        }
        $result = $this->useCase->execute($refreshToken, $deviceId);
        return $this->success([
            'access_token' => $result['access_token'],
            'refresh_token' => $result['refresh_token'],
            'device_id' => $result['device_id'],
        ], 'Token refreshed');
    }
}
