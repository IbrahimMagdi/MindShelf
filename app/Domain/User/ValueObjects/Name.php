<?php
declare(strict_types=1);

namespace App\Domain\User\ValueObjects;

final class Name
{
    public function __construct(private string $value)
    {
        $trimmed = trim($value);

        if (strlen($trimmed) < 3) {
            throw new \InvalidArgumentException("Name must be at least 3 characters");
        }

        if (strlen($trimmed) > 100) {
            throw new \InvalidArgumentException("Name must not exceed 100 characters");
        }

        $this->value = $trimmed;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
