<?php

namespace App\Services\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UpdateImageService
{
    public function updateImage(User $user, $imageFile): User
    {
        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }

        if ($imageFile) {
            $filename = 'user_' . $user->id . '_' . time() . '.' . $imageFile->getClientOriginalExtension();
            $path = $imageFile->storeAs('profiles', $filename, 'public');
            $user->update(['image' => $path]);
        } else {
            $user->update(['image' => null]);
        }
        return $user->fresh();
    }
}
