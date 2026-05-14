<?php
declare(strict_types=1);

namespace App\Http\Requests\Api\Setting;

use App\Http\Requests\Api\ApiFormRequest;

final class LogoutSpecificDeviceRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'device_id' => ['required', 'string'],
        ];
    }
}
