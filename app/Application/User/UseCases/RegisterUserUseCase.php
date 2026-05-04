<?php
declare(strict_types=1);

namespace App\Application\User\UseCases;

use App\Application\Otp\UseCases\GenerateOtpUseCase;
use App\Application\User\DTOs\RegisterUserRequest;
use App\Application\User\Ports\PasswordHasherInterface;
use App\Domain\Otp\Enums\OtpType;
use App\Domain\User\Entities\UserEntity;
use App\Domain\User\Enums\Gender;
use App\Domain\User\Enums\UserRole;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\BirthDate;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Name;
use DomainException;
use DateTimeImmutable;

final readonly class RegisterUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher,
        private GenerateOtpUseCase $generateOtp,

    ) {}

    public function execute(RegisterUserRequest $request): array
    {
        $email = new Email($request->email);

        if ($this->userRepository->existsByEmail($email)) {
            throw new DomainException('Email already registered');
        }

        $user = UserEntity::create(
            name: new Name($request->name),
            email: $email,
            gender: Gender::from($request->gender),
            password: $this->passwordHasher->hash($request->password),
            role: UserRole::from($request->role),
            birthdate: $request->birthdate ? new BirthDate(new DateTimeImmutable($request->birthdate)): null,
        );
        $savedUser = $this->userRepository->save($user);
        $this->generateOtp->execute($savedUser, OtpType::EMAIL_VERIFICATION);

        return [
            'user' => $savedUser,
            'message' => 'Registration successful. Please check your email to verify your account.',
        ];
    }
}
