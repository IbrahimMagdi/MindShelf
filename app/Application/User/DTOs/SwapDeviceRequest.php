<?php

declare(strict_types=1);

namespace App\Application\User\DTOs;

final class SwapDeviceRequest
{
    public function __construct(
        public string $email,
        public string $password,
        public string $logoutDeviceId,
    ){}

    public static function fromArray(array $data):self
    {
        return new self(
            email: $data['email'],
            password: $data['password'],
            logoutDeviceId: $data['logout_device_id'],
        );
    }
}
