<?php
declare(strict_types=1);

namespace App\Http\Requests\Api\Profile;

use App\Http\Requests\Api\ApiFormRequest;

final class UpdateImageRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.max' => 'Image size must not exceed 2MB',
        ];
    }
}
