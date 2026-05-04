<?php
declare(strict_types=1);

namespace App\Domain\Otp\Enums;

enum OtpType: string
{
    case DEVICE_LIMIT = 'device_limit';
    case PASSWORD_RESET = 'password_reset';
    case EMAIL_VERIFICATION = 'email_verification';
    case TWO_FACTOR = 'two_factor';

    public function label(): string
    {
        return match($this) {
            self::DEVICE_LIMIT => 'Device Limit',
            self::PASSWORD_RESET => 'Password Reset',
            self::EMAIL_VERIFICATION => 'Email Verification',
            self::TWO_FACTOR => 'Two Factor Authentication',
        };
    }

    public function expiryMinutes(): int
    {
        return match($this) {
            self::DEVICE_LIMIT => 10,
            self::PASSWORD_RESET => 15,
            self::EMAIL_VERIFICATION => 60,
            self::TWO_FACTOR => 5,
        };
    }

    public function maxAttempts(): int
    {
        return match($this) {
            self::TWO_FACTOR => 3,
            default => 5,
        };
    }
}
