<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Application\User\DTOs\ResendVerifyOtpRequest;
use App\Http\Requests\Api\Auth\ResendVerifyOtpRequest as ResendVerifyOtpFormRequest;
use App\Application\User\UseCases\ResendVerifyOtpUseCase;
 use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;
final class ResendVerifyOtpController extends ApiController
{
    public function __construct(private readonly ResendVerifyOtpUseCase $useCase) {}

    public function __invoke(ResendVerifyOtpFormRequest $request): JsonResponse
    {
        $dto = ResendVerifyOtpRequest::fromArray($request->validated());
        $this->useCase->execute($dto);
        return $this->success(null, 'Otp sent successfully');

    }

}
