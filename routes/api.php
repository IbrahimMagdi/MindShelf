<?php
use App\Http\Controllers\Api\Auth\AuthController;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});



// Route::middleware('auth:sanctum')->group(function () {

//     // تجربة: هات بياناتي كـ User
//     Route::get('/me', function (\Illuminate\Http\Request $request) {
//         return new \App\Http\Resources\Auth\UserResource($request->user());
//     });

//     // تسجيل الخروج (إبطال التوكن)
//     Route::post('/auth/logout', [AuthController::class, 'logout']);
// });
