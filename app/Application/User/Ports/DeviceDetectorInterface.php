<?php

namespace App\Application\User\Ports;

interface DeviceDetectorInterface
{
    public function getCurrentDeviceInfo(): array;
    public function validateDevice(string $storedDeviceId): bool;

}
