<?php
declare(strict_types=1);

namespace App\Infrastructure\Services\Notification;

use App\Application\User\Ports\NotificationServiceInterface;
use App\Application\User\Ports\DeviceDetectorInterface;
use App\Domain\Otp\Enums\OtpType;
use App\Domain\Otp\ValueObjects\OtpCode;
use App\Mail\DeviceLimitMail;
use App\Mail\ResetPasswordMail;
use App\Mail\WelcomeVerificationMail;
use Illuminate\Support\Facades\Mail;
use App\Domain\User\Entities\UserEntity;

class EmailNotificationService implements NotificationServiceInterface
{
    public function __construct(
        private DeviceDetectorInterface $deviceDetector
    ) {}

    public function sendOtp(string $email, OtpCode $code, OtpType $type, ?UserEntity $user = null): void
    {
        match($type){
            OtpType::EMAIL_VERIFICATION => $this->sendEmailVerification($user, $code),
            OtpType::PASSWORD_RESET => $this->sendPasswordReset($user, $code),
            OtpType::DEVICE_LIMIT => $this->sendDeviceLimit($user, $code),
            default => throw new \InvalidArgumentException("Unsupported OTP type: {$type->value}"),
        };
    }
    public function sendEmailVerification($user, $code):void
    {
        if ($user === null) {
            throw new \InvalidArgumentException('User required for welcome verification email');
        }
        Mail::to($user->getEmail()->value())->send(
            new WelcomeVerificationMail(userName: $user->getName()->value(), code: $code->value())
        );
    }
    public function sendPasswordReset($user, $code):void
    {
        if ($user === null) {
            throw new \InvalidArgumentException('User required for password reset email');
        }
        Mail::to($user->getEmail()->value())->send(
            new ResetPasswordMail(userName: $user->getName()->value(), code: $code->value())
        );
    }

    public function sendDeviceLimit($user, $code): void
    {
        if ($user === null) {
            throw new \InvalidArgumentException('User required for device limit email');
        }
        $info = $this->deviceDetector->getCurrentDeviceInfo();

        Mail::to($user->getEmail()->value())->send(new DeviceLimitMail(
            code: $code->value(),
            browser: $info['browser'] ?? 'Unknown',
            platform: $info['platform'] ?? 'Unknown',
            device: $info['device'] ?? 'Unknown Device',
            ip: $info['ip'] ?? '0.0.0.0',
        ));
    }
}
