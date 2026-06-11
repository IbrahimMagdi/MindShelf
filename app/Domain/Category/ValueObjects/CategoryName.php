<?php
declare(strict_types=1);

namespace App\Domain\Category\ValueObjects;

final class CategoryName
{
    public function __construct(private string $value)
    {
        $value = trim($value);
        if (strlen($value) < 2) {
            throw new \InvalidArgumentException('Category name must be at least 2 characters long');
        }
        if (strlen($value) > 100) {
            throw new \InvalidArgumentException('Category name must be at most 100 characters long');
        }
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
