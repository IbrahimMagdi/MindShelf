<?php
declare(strict_types=1);

namespace App\Application\User\UseCases;

use App\Application\Otp\UseCases\VerifyOtpUseCase;
use App\Application\User\DTOs\ResetPasswordRequest;
use App\Application\User\Ports\PasswordHasherInterface;
use App\Domain\Otp\Enums\OtpType;
use App\Domain\Otp\ValueObjects\OtpCode;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\Email;
use DomainException;

final readonly class ResetPasswordUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private VerifyOtpUseCase $verifyOtp,
        private PasswordHasherInterface $passwordHasher,
    ) {}

    public function execute(ResetPasswordRequest $request): void
    {
        $user = $this->userRepository->findByEmail(new Email($request->email));

        if (!$user) {
            throw new DomainException('Invalid request');
        }

        $otpCode = new OtpCode($request->code);
        $this->verifyOtp->execute($user->getId(), $otpCode, OtpType::PASSWORD_RESET);

        $hashedPassword = $this->passwordHasher->hash($request->password);
        $user->changePassword($hashedPassword);

        $this->userRepository->save($user);
    }
}
