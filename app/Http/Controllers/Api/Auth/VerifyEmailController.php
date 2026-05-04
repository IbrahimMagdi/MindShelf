<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Application\User\DTOs\VerifyEmailRequest;
use App\Application\User\UseCases\VerifyEmailUseCase;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\VerifyEmailRequest as VerifyEmailFormRequest;
use App\Http\Resources\Api\UserResource;
use Illuminate\Http\JsonResponse;

final class VerifyEmailController extends ApiController
{
    public function __construct(
        private readonly VerifyEmailUseCase $useCase,
    ) {}

    public function __invoke(VerifyEmailFormRequest $request): JsonResponse
    {
        $dto = VerifyEmailRequest::fromArray($request->validated());

        $result = $this->useCase->execute($dto);

        return $this->success(['user' => new UserResource($result['user'])], $result['message']);
    }
}
