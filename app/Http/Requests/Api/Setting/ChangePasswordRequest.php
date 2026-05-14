<?php
declare(strict_types=1);

namespace App\Http\Requests\Api\Setting;

use App\Http\Requests\Api\ApiFormRequest;

final class ChangePasswordRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'current_password' => $this->passwordRule(min: 1),
            'new_password' => $this->passwordRule(confirmed: true),
        ];
    }

    public function messages(): array
    {
        return [
            'image.max' => 'Image size must not exceed 2MB',
        ];
    }
}
