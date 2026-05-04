<?php
declare(strict_types=1);

namespace App\Domain\Otp\ValueObjects;

final class OtpCode
{
    private const LENGTH = 6;

    public function __construct(private string $value)
    {
        $cleaned = preg_replace('/\D/', '', $value);

        if (strlen($cleaned) !== self::LENGTH) {
            throw new \InvalidArgumentException(
                sprintf('OTP must be exactly %d digits, got %d', self::LENGTH, strlen($cleaned))
            );
        }

        $this->value = $cleaned;
    }

    public static function generate(): self
    {
        return new self((string) random_int(100000, 999999));
    }

    public function value(): string
    {
        return $this->value;
    }

    public function masked(): string
    {
        return '***' . substr($this->value, -3);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->masked();
    }
}
