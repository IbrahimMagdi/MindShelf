<?php
declare(strict_types=1);

namespace App\Application\User\UseCases;

use App\Application\Otp\UseCases\GenerateOtpUseCase;
use App\Domain\Otp\Repositories\OtpRepositoryInterface;
use App\Domain\Otp\Enums\OtpType;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\Email;
use App\Application\User\DTOs\ResendVerifyOtpRequest;
use DomainException;
final readonly class ResendVerifyOtpUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private OtpRepositoryInterface $otpRepository,
        private GenerateOtpUseCase $generateOtpUseCase
    ) {}

    public function execute(ResendVerifyOtpRequest $request): void
    {
        $email = new Email($request->email);
        $user = $this->userRepository->findByEmail($email);
        if(!$user)
        {
            throw new DomainException('User not found with this email.');
        }
        if($user->isEmailVerified()){
            throw new DomainException('User is already verified.');
        }
        $lastOtp = $this->otpRepository->findLatestForUser(
            $user->getId(),
            OtpType::EMAIL_VERIFICATION
        );
        if ($lastOtp && !$lastOtp->canResend()) {
            throw new DomainException('Please wait before requesting a new code.');
        }
        $this->generateOtpUseCase->execute($user, OtpType::EMAIL_VERIFICATION);
    }


}
