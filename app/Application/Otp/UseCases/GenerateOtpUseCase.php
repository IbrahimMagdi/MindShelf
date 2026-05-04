<?php
declare(strict_types=1);

namespace App\Application\Otp\UseCases;

use App\Application\User\Ports\NotificationServiceInterface;
use App\Domain\Otp\Entities\OtpEntity;
use App\Domain\Otp\Enums\OtpType;
use App\Domain\Otp\Repositories\OtpRepositoryInterface;
use App\Domain\Otp\ValueObjects\OtpCode;
use App\Domain\User\Entities\UserEntity;

final readonly class GenerateOtpUseCase
{
    public function __construct(
        private OtpRepositoryInterface $otpRepository,
        private NotificationServiceInterface $notificationService,
    ) {}

    public function execute(UserEntity $user, OtpType $type): OtpCode
    {
        $this->otpRepository->invalidatePrevious($user->getId(), $type);

        $otp = OtpEntity::create($user->getId(), $type);

        $this->otpRepository->save($otp);

        $this->notificationService->sendOtp(
            $user->getEmail()->value(),
            $otp->getCode(),
            $type,
            $user
        );

        return $otp->getCode();
    }
}
