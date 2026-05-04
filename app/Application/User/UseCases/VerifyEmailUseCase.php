<?php
declare(strict_types=1);

namespace App\Application\User\UseCases;

use App\Application\Otp\UseCases\VerifyOtpUseCase;
use App\Domain\Otp\Enums\OtpType;
use App\Domain\Otp\ValueObjects\OtpCode;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\Email;
use App\Application\User\DTOs\VerifyEmailRequest;
use DomainException;

final readonly class VerifyEmailUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private VerifyOtpUseCase $verifyOtpUseCase
    ) {}

    public function execute(VerifyEmailRequest $request): array
    {
        $user = $this->userRepository->findByEmail(new Email($request->email));

        if (!$user) {
            throw new DomainException('User not found');
        }
        $this->verifyOtpUseCase->execute(
            $user->getId(),
            new OtpCode($request->code),
            OtpType::EMAIL_VERIFICATION
        );
        $user->verifyEmail();
        $savedUser = $this->userRepository->save($user);
        return [
            'user' => $savedUser,
            'message' => 'Account activated successfully.'
        ];
    }
}
