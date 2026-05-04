<?php
declare(strict_types=1);

namespace App\Domain\User\Exceptions;

final class TokenNotFoundException extends \DomainException
{
    public static function forDevice(string $deviceId): self
    {
        return new self("No active session found for device: {$deviceId}");
    }

    public static function forToken(int $tokenId): self
    {
        return new self("Token with ID {$tokenId} not found.");
    }
}
