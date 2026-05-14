<?php
declare(strict_types=1);

namespace App\Application\User\Ports;

use App\Domain\User\Entities\UserEntity;

interface IdentityProviderInterface
{
    public function getCurrentUser(): ?UserEntity;
}
