<?php
declare(strict_types=1);

namespace App\Application\User\UseCases;

use App\Application\Otp\UseCases\GenerateOtpUseCase;
use App\Application\User\DTOs\ForgotPasswordRequest;
use App\Domain\Otp\Enums\OtpType;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\Email;

final readonly class ForgotPasswordUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private GenerateOtpUseCase $generateOtp,
    ) {}

    public function execute(ForgotPasswordRequest $request): void
    {
        $emailVo = new Email($request->email);
        $user = $this->userRepository->findByEmail($emailVo);
        if (!$user) {
            return;
        }

        $this->generateOtp->execute($user, OtpType::PASSWORD_RESET);
    }
}
