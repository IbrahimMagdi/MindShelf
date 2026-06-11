<?php
declare(strict_types=1);

namespace App\Domain\Category\ValueObjects;

final class CategorySlug
{
    public function __construct(private string $value)
    {
        if ($value === '') {
            throw new \DomainException('Category slug cannot be empty');
        }
        $this->value = strtolower($value);
    }

    public function value():string
    {
        return $this->value;
    }
}
