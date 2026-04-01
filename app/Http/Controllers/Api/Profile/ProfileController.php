<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\Profile\ProfileResource;
use App\Services\Profile\UpdateImageService;
use App\Services\Profile\UpdateProfileService;
use App\Http\Requests\Profile\UpdateImageRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Helpers\ApiResponse;


class ProfileController extends Controller
{
    public function __construct(protected UpdateProfileService $profileService, protected UpdateImageService $updateImageService) {}
    public function my(Request $request)
    {
        return ApiResponse::success(new ProfileResource($request->user()));
    }
    public function other(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            return ApiResponse::success(
                new ProfileResource($request->user()),
                'Your profile'
            );
        }
        return ApiResponse::success(
            new ProfileResource($user),
            'User profile fetched successfully'
        );
    }
    public function updateImage(UpdateImageRequest $request)
    {
        $user = $this->updateImageService->updateImage(
            $request->user(),
            $request->file('image')
        );

        return ApiResponse::success(
            new ProfileResource($user),
            'Profile image updated successfully'
        );
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $this->updateImageService->updateImage($user, $request->file('image'));
            unset($data['image']);
        }
        $updatedUser = $this->profileService->updateProfile($user, $data);
        return ApiResponse::success(
            new ProfileResource($updatedUser),
            'Profile updated successfully'
        );
    }

    public function removeImage(Request $request)
    {
        $user = $this->updateImageService->updateImage($request->user(), null);
        return ApiResponse::success(new ProfileResource($user), 'Image removed');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return ApiResponse::success(null, 'Logged out successfully');
    }
}
