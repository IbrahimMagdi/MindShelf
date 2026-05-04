<?php
declare(strict_types=1);

namespace App\Application\Otp\UseCases;

use App\Domain\Otp\Enums\OtpType;
use App\Domain\Otp\Exceptions\OtpException;
use App\Domain\Otp\Repositories\OtpRepositoryInterface;
use App\Domain\Otp\ValueObjects\OtpCode;

final readonly class VerifyOtpUseCase
{
    public function __construct(private OtpRepositoryInterface $otpRepository) {}

    public function execute(int $userId, OtpCode $code, OtpType $type): bool
    {
        $otp = $this->otpRepository->findByCode($userId, $code, $type);
        if (!$otp) {
            throw OtpException::notFound();
        }
        $otp->checkValidation();
        $this->otpRepository->markUsed($otp);
        return true;
    }
}
