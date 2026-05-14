<?php
declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Shared\Http\Responses\ApiResponse;

abstract class ApiFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function expectsJson(): bool
    {
        return true;
    }

    /**
     * Return JSON response instead of redirect on validation fail
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            ApiResponse::validation($validator->errors()->toArray())
        );
    }

    /**
     * Common rule helpers
     */
    protected function emailRule(bool $unique = false, ?string $table = null): array
    {
        $rules = ['required', 'email'];

        if ($unique && $table) {
            $rules[] = "unique:{$table},email";
        }

        return $rules;
    }

    protected function passwordRule(int $min = 8, bool $confirmed = false): array
    {
        $rules = ['required', 'string', "min:{$min}", 'max:16'];

        if ($confirmed) {
            $rules[] = 'confirmed';
        }

        return $rules;
    }

    protected function nameRule(int $min = 3, int $max = 100): array
    {
        return ['required', 'string', "min:{$min}", "max:{$max}"];
    }

    protected function genderRule(): array
    {
        return ['required', 'in:male,female'];
    }

    protected function birthdateRule(bool $nullable = true): array
    {
        $rules = ['date', 'before:today'];

        if ($nullable) {
            array_unshift($rules, 'nullable');
        }

        return $rules;
    }

    protected function otpRule(int $digits = 6): array
    {
        return ['required', 'string', 'regex:/^\d{' . $digits . '}$/'];
    }
}
