<?php
declare(strict_types=1);

namespace App\Providers;

// Ports
use App\Application\User\Ports\NotificationServiceInterface;
use App\Application\User\Ports\PasswordHasherInterface;
use App\Application\User\Ports\TokenOperationsInterface;
use App\Application\User\Ports\TokenServiceInterface;
use App\Application\User\Ports\DeviceDetectorInterface;
use App\Application\User\Ports\IdentityProviderInterface;

// Repositories
use App\Domain\Otp\Repositories\OtpRepositoryInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;
// Implementations
use App\Infrastructure\Persistence\Otp\OtpRepositoryImpl;
use App\Infrastructure\Persistence\User\UserRepositoryImpl;
use App\Infrastructure\Services\User\BcryptPasswordHasher;
use App\Infrastructure\Services\User\Token\TokenOperationsImpl;
use App\Infrastructure\Services\User\TokenServiceImpl;
use App\Infrastructure\Services\User\Device\DeviceDetector;
use App\Infrastructure\Services\User\Device\DeviceNameResolver;
use App\Infrastructure\Services\Notification\EmailNotificationService;
use App\Infrastructure\Services\User\IdentityProvider;
use App\Infrastructure\Persistence\Category\CategoryRepositoryImpl;


use App\Models\PersonalAccessToken;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {

        $this->app->bind(UserRepositoryInterface::class, UserRepositoryImpl::class);
        $this->app->bind(OtpRepositoryInterface::class, OtpRepositoryImpl::class);
        $this->app->bind(IdentityProviderInterface::class, IdentityProvider::class);

        $this->app->bind(PasswordHasherInterface::class, BcryptPasswordHasher::class);
        $this->app->bind(TokenOperationsInterface::class, TokenOperationsImpl::class);
        $this->app->bind(TokenServiceInterface::class, TokenServiceImpl::class);

        $this->app->bind(DeviceDetectorInterface::class, DeviceDetector::class);
        $this->app->bind(NotificationServiceInterface::class, EmailNotificationService::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepositoryImpl::class);

        $this->app->singleton(DeviceNameResolver::class);
    }

    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
