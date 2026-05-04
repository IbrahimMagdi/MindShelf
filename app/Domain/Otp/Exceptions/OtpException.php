<?php
declare(strict_types=1);

namespace App\Domain\Otp\Exceptions;

class OtpException extends \DomainException
{
    public static function alreadyUsed(): self
    {
        return new self('OTP has already been used');
    }

    public static function expired(): self
    {
        return new self('OTP has expired');
    }

    public static function invalidCode(): self
    {
        return new self('Invalid OTP code');
    }

    public static function maxAttemptsReached(int $max): self
    {
        return new self(sprintf('Maximum attempts (%d) reached', $max));
    }

    public static function notFound(): self
    {
        return new self('No valid OTP found');
    }
}
