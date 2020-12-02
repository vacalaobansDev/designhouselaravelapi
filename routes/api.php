<?php

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\User\MeController;
use App\Http\Controllers\User\SettingsController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

//Public routes
Route::get('me', [MeController::class, 'getMe'])->name('me');


//Routes for authenticated users only
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::put('settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::put('settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
});

//Routes for guest only
Route::group(['middleware' => ['guest:api']], function () {

    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::post('verification/resend', [VerificationController::class, 'resend'])->name('verification.resend');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');

    Route::post('verification/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');

});


