<?php
declare(strict_types=1);

namespace App\Infrastructure\Services\User;

use App\Application\User\Ports\PasswordHasherInterface;
use App\Domain\User\ValueObjects\HashedPassword;

class BcryptPasswordHasher implements PasswordHasherInterface
{
    public function hash(string $plainPassword): HashedPassword
    {
        $hash = password_hash($plainPassword, PASSWORD_BCRYPT);
        return HashedPassword::fromHash($hash);
    }

    public function verify(string $plainPassword, HashedPassword $hashed): bool
    {
        return $hashed->verify($plainPassword);
    }
}
