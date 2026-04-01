<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'bio' => $this->bio,
            'email' => $this->email,
            'birthdate' => $this->birthdate?->toDateString(),
            'gender' => $this->gender,
            'role' => $this->role,
            'image' => $this->image_url,
        ];
    }
}
