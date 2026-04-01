<?php
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Profile\ProfileController;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
    ->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', [AuthController::class, 'resendVerification'])
    ->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');


Route::middleware(['auth:sanctum', 'verified'])->prefix('profile')->group(function ()
    {
        Route::get('/my', [ProfileController::class, 'my']); // api/profile/my
        Route::get('/other/{user}', [ProfileController::class, 'other'])->whereNumber('user')->scopeBindings();   // api/profile/
        Route::patch('/update-image', [ProfileController::class, 'updateImage']); // api/profile/update
        Route::put('/update', [ProfileController::class, 'update']); // api/profile/update
        Route::delete('/remove-image', [ProfileController::class, 'removeImage']);
        Route::post('/logout', [ProfileController::class, 'logout']); // api/profile/logout
    });
