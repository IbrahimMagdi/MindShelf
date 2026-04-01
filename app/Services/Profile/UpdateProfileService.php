<?php

namespace App\Services\Profile;

use App\Models\User;


class UpdateProfileService
{
    public function updateProfile(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }
}
