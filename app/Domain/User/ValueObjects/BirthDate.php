<?php
declare(strict_types=1);

namespace App\Domain\User\ValueObjects;

final class BirthDate
{
    private const ADULT_AGE = 18;

    public function __construct(private \DateTimeImmutable $date) {}

    public function age(): int
    {
        return (new \DateTimeImmutable())->diff($this->date)->y;
    }

    public function isAdult(): bool
    {
        return $this->age() >= self::ADULT_AGE;
    }

    public function toDateTime(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function equals(self $other): bool
    {
        return $this->date->format('Y-m-d') === $other->date->format('Y-m-d');
    }

    public function __toString(): string
    {
        return $this->date->format('Y-m-d');
    }
}
