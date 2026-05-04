<?php
declare(strict_types=1);

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiFormRequest;

final class ResetPasswordRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'email' => $this->emailRule(),
            'code' => $this->otpRule(6),
            'password' => $this->passwordRule(confirmed: true),
            'password_confirmation' => ['required', 'string'],
        ];
    }
}
