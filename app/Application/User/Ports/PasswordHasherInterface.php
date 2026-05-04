<?php
declare(strict_types=1);

namespace App\Application\User\Ports;

use App\Domain\User\ValueObjects\HashedPassword;

interface PasswordHasherInterface
{
    public function hash(string $plainPassword): HashedPassword;

    public function verify(string $plainPassword, HashedPassword $hashed): bool;
}
