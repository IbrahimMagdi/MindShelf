<?php
declare(strict_types=1);

namespace App\Application\User\DTOs;

final readonly class LogoutSpecificDeviceRequest
{
    public function __construct(public string $deviceId) {}

    public static function fromArray(array $data): self
    {
        return new self(deviceId: $data['device_id']);
    }
}
