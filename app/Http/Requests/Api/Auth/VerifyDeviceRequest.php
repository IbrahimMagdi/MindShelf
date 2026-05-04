<?php
declare(strict_types=1);

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiFormRequest;

final class VerifyDeviceRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'otp' => $this->otpRule(6),
            'logout_device_id' => ['nullable', 'string'],
        ];
    }
}
