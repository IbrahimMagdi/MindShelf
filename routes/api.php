<?php

declare(strict_types=1);

use App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Api\Profile;
use App\Http\Controllers\Api\Setting;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('/register', Auth\RegisterController::class);
    Route::post('/resend-otp', Auth\ResendVerifyOtpController::class);
    Route::post('/verify-email', Auth\VerifyEmailController::class);
    Route::post('/login', Auth\LoginController::class);
    Route::post('/forgot-password', Auth\ForgotPasswordController::class);
    Route::post('/reset-password', Auth\ResetPasswordController::class);
    Route::post('/verify-swap', Auth\VerifySwapController::class);
    Route::post('/refresh-token', Auth\RefreshTokenController::class)
        ->middleware('auth:sanctum');
});

/*
|--------------------------------------------------------------------------
| Profile Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'device.check', 'token.lifecycle','verified'])->prefix('profile')->group(function () {
    Route::get('/{userId}', Profile\GetUserController::class);
//    Route::put('/', Profile\UpdateController::class);
    Route::post('/image', [Profile\ImageController::class, 'store']);
    Route::delete('/image', [Profile\ImageController::class, 'destroy']);

});

/*
|--------------------------------------------------------------------------
| Device Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'device.check', 'token.lifecycle','verified'])->prefix('setting')->group(function () {
    Route::post('/change-password', Setting\ChangePasswordController::class);
    Route::post('/logout', Setting\LogoutController::class);
    Route::prefix('devices')->group(function () {
        Route::get('/', Setting\Device\ListController::class);
        Route::delete('/revoke', Setting\Device\LogoutController::class);
        Route::delete('/logout-others', Setting\Device\LogoutOthersController::class);
    });
});








//
//Route::middleware(['auth:sanctum', 'device.check', 'token.lifecycle'])->group(function () {
//    Route::prefix('profile')->group(function ()
//    {
//        Route::get('/my', [ProfileController::class, 'my']); // api/profile/my
//        Route::get('/other/{user}', [ProfileController::class, 'other'])->whereNumber('user')->scopeBindings();   // api/profile/
//        Route::patch('/update-image', [ProfileController::class, 'updateImage']); // api/profile/update
//        Route::put('/update', [ProfileController::class, 'update']); // api/profile/update
//        Route::delete('/remove-image', [ProfileController::class, 'removeImage']);
//        Route::post('/logout', [ProfileController::class, 'logout']); // api/profile/logout
//    });
//

//});
//
//
//
