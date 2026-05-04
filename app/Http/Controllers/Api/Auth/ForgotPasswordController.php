<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Application\User\DTOs\ForgotPasswordRequest;
use App\Application\User\UseCases\ForgotPasswordUseCase;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\ForgotPasswordRequest as ForgotPasswordFormRequest;
use Illuminate\Http\JsonResponse;

final class ForgotPasswordController extends ApiController
{
    public function __construct(
        private readonly ForgotPasswordUseCase $useCase,
    ) {}

    public function __invoke(ForgotPasswordFormRequest $request): JsonResponse
    {
        $dto = ForgotPasswordRequest::fromArray($request->validated());
        $this->useCase->execute($dto);

        return $this->success(
            null,
            'If email exists, OTP will be sent'
        );
    }
}
