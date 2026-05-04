<?php

namespace App\Infrastructure\Services\User\Device;

use Jenssegers\Agent\Agent;

class DeviceNameResolver
{
    public function resolve(array $deviceInfo): array
    {
        $agent = new Agent();
        $agent->setUserAgent($deviceInfo['user_agent'] ?? '');
        return [
            'device_name' => implode(' - ', array_filter([
                $agent->device(),
                $agent->browser(),
                $agent->platform()
            ])) ?: 'Unknown Device',

            'browser' => $agent->browser(),
            'platform' => $agent->platform(),
            'device' => $agent->device(),
        ];
    }
}
