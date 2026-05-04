<?php
declare(strict_types=1);

namespace App\Application\User\DTOs;

final readonly class DeviceInfoRequest
{
    public function __construct(
        public ?string $deviceId = null,
        public ?string $userAgent = null,
        public ?string $ip = null,
    ) {}

    public static function fromRequest(array $headers, string $ip): self
    {
        return new self(
            deviceId: $headers['x-device-id'] ?? null,
            userAgent: $headers['user-agent'] ?? null,
            ip: $ip,
        );
    }

    public function toArray(): array
    {
        return [
            'device_id' => $this->deviceId,
            'user_agent' => $this->userAgent,
            'ip' => $this->ip,
        ];
    }
}
