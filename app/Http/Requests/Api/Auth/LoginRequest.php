<?php
declare(strict_types=1);

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiFormRequest;

final class LoginRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'email' => $this->emailRule(),
            'password' => $this->passwordRule(min: 1),
        ];
    }
}
