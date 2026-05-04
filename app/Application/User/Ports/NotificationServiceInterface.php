<?php
declare(strict_types=1);

namespace App\Application\User\Ports;

use App\Domain\Otp\Enums\OtpType;
use App\Domain\Otp\ValueObjects\OtpCode;
use App\Domain\User\Entities\UserEntity;

interface NotificationServiceInterface
{
    public function sendOtp(string $email, OtpCode $code, OtpType $type, ?UserEntity $user = null): void;
}
