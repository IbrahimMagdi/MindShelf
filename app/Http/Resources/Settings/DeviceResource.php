<?php
declare(strict_types=1);

namespace App\Http\Resources\Settings;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

final class DeviceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $currentDeviceId = $request->header('X-Device-ID');
        $lastActiveAt = $this['last_used_at'] ?? $this['created_at'];
        return [
            'id' => $this['id'],
            'device_id' => $this['device_id'],
            'device_name' => $this['name'],
            'platform' => $this['platform'],
            'browser' => $this['browser'],
            'created_at' => $this['created_at']?->format('Y-m-d H:i:s'),
            'last_active' => $lastActiveAt ? Carbon::parse($lastActiveAt)->diffForHumans() : 'Just now',
            'is_current' => $this['device_id'] === $currentDeviceId,
            'label' => $this['device_id'] === $currentDeviceId ? 'This Device' : 'Other Device',
        ];
    }
}
