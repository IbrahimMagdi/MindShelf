<?php

namespace App\Http\Resources\Settings;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array

    {
        $currentTokenId  = $request->user()->currentAccessToken()?->id;
        return [
            'id' => $this->id,
            'device_id' => $this->device_id,
            'device_name' => $this->name,
            'device' => $this->device,
            'browser' => $this->browser,
            'platform' => $this->platform,
            'created_at' => date('Y-m-d H:i:s', $this->created_at),
            'updated_at' => date('Y-m-d H:i:s', $this->updated_at),
            'is_current' => $this->id === $currentTokenId,
            'label' => $this->id === $currentTokenId ? 'This Device' : 'This Not Current Device',
        ];
    }
}
