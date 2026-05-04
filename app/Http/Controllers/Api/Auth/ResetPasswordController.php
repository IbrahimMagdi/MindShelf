<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Application\User\DTOs\ResetPasswordRequest;
use App\Application\User\UseCases\ResetPasswordUseCase;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\ResetPasswordRequest as ResetPasswordFormRequest;
use Illuminate\Http\JsonResponse;

final class ResetPasswordController extends ApiController
{
    public function __construct(
        private readonly ResetPasswordUseCase $useCase,
    ) {}

    public function __invoke(ResetPasswordFormRequest $request): JsonResponse
    {
        $dto = ResetPasswordRequest::fromArray($request->validated());
        $this->useCase->execute($dto);

        return $this->success(
            null,
            'Password reset successfully'
        );
    }
}
