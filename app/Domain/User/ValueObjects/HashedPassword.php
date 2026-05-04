<?php
declare(strict_types=1);

namespace App\Domain\User\ValueObjects;

final class HashedPassword
{
    private function __construct(private string $hash) {}

    public static function fromHash(string $hash): self
    {
        if (!str_starts_with($hash, '$2y$') && !str_starts_with($hash, '$argon2')) {
            throw new \InvalidArgumentException("Invalid password hash format");
        }
        return new self($hash);
    }

    public function hash(): string
    {
        return $this->hash;
    }
    public function value(): string
    {
        return $this->hash();
    }
    public function verify(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->hash);
    }

    public function equals(self $other): bool
    {
        return $this->hash === $other->hash;
    }

    public function __toString(): string
    {
        return '[HIDDEN]';
    }
}
