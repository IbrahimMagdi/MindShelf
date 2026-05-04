<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\Profile;

use App\Application\User\DTOs\ChangePasswordRequest;
use App\Application\User\UseCases\ChangePasswordUseCase;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Profile\ChangePasswordRequest as ChangePasswordFormRequest;
use Illuminate\Http\JsonResponse;

final class ChangePasswordController extends ApiController
{
    public function __construct(
        private ChangePasswordUseCase $useCase,
    ) {}

    public function __invoke(ChangePasswordFormRequest $request): JsonResponse
    {
        $dto = ChangePasswordRequest::fromArray([
            'user_id' => $request->user()->id,
            ...$request->validated(),
        ]);

        $this->useCase->execute($dto);

        return $this->success(
            null,
            'Password changed successfully'
        );
    }
}
