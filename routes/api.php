<?php

use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Auth\OtpController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| OTP API Endpoints
|--------------------------------------------------------------------------
|
| These endpoints support programmatic OTP flows. They use the web session
| for pending verification state and return JSON responses.
|
*/

Route::middleware('web')->prefix('otp')->name('api.otp.')->group(function () {
    Route::post('/send', [OtpController::class, 'apiSend'])
        ->middleware('throttle:6,1')
        ->name('send');

    Route::middleware('otp.pending')->group(function () {
        Route::post('/verify', [OtpController::class, 'apiVerify'])
            ->middleware('throttle:15,1')
            ->name('verify');

        Route::post('/resend', [OtpController::class, 'apiResend'])
            ->middleware('throttle:6,1')
            ->name('resend');
    });
});

Route::middleware(['web', 'auth', 'admin'])->prefix('admin/notifications')->name('api.admin.notifications.')->group(function () {
    Route::get('/emails', [NotificationController::class, 'emailHistory'])->name('emails');
    Route::get('/audit-logs', [NotificationController::class, 'auditLogs'])->name('audit');
});
