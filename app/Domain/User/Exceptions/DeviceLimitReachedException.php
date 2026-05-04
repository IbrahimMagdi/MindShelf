<?php
declare(strict_types=1);

namespace App\Domain\User\Exceptions;

final class DeviceLimitReachedException extends \DomainException
{
    private array $devices;

    public function __construct(array $devices)
    {
        parent::__construct("Maximum device limit reached.");
        $this->devices = $devices;
    }

    public function getDevices(): array
    {
        return $this->devices;
    }
}
