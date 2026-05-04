<?php
declare(strict_types=1);

namespace App\Http\Requests\Api\Profile;

use App\Http\Requests\Api\ApiFormRequest;

final class UpdateProfileRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'min:3', 'max:100'],
            'bio' => ['sometimes', 'nullable', 'string', 'max:500'],
            'gender' => ['sometimes', 'in:male,female'],
            'birthdate' => $this->birthdateRule(),
        ];
    }
}
