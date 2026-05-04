<?php

namespace App\Infrastructure\Services\User\Device;
use App\Application\User\Ports\DeviceDetectorInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DeviceDetector implements DeviceDetectorInterface
{
    public function __construct(
        private Request $request,
        private DeviceNameResolver $nameResolver
    ) {}
    public function getCurrentDeviceInfo(): array
    {
        $raw = [
            'device_id'  => $this->request->header('X-Device-Id') ?? (string) Str::uuid(),
            'user_agent' => $this->request->userAgent(),
            'ip'         => $this->request->ip(),
        ];

        $resolved = $this->nameResolver->resolve($raw);
        return array_merge($raw, $resolved);
    }

    public function validateDevice(string $storedDeviceId): bool
    {
        $currentId = $this->request->header('X-Device-Id');
        return $currentId && hash_equals($storedDeviceId, $currentId);
    }

}
