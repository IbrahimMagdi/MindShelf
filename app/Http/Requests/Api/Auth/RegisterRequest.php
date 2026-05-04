<?php
declare(strict_types=1);

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiFormRequest;

final class RegisterRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'name' => $this->nameRule(),
            'email' => $this->emailRule(unique: true, table: 'users'),
            'password' => $this->passwordRule(confirmed: true),
            'password_confirmation' => ['required', 'string'],
            'gender' => $this->genderRule(),
            'role' => ['required', 'string', 'in:customer,author'],
            'birthdate' => $this->birthdateRule(),
        ];
    }
}
