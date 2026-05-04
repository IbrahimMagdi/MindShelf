<?php
declare(strict_types=1);

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\UpdateProfileImageRequest;
use App\Domain\User\Repositories\UserRepositoryInterface;

final class UpdateProfileImageUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        // private ImageStorageInterface $imageStorage, // Port for file storage
    ) {}

    public function execute(UpdateProfileImageRequest $request): void
    {
        $user = $this->userRepository->findById($request->userId);

        if (!$user) {
            throw new \DomainException('User not found');
        }

        // TODO: Implement image storage via Port
        // if ($request->imageFile) {
        //     $path = $this->imageStorage->store($request->imageFile, 'profiles');
        //     $user->updateImage($path);
        // } else {
        //     $user->updateImage(null);
        // }

        $this->userRepository->save($user);
    }
}
