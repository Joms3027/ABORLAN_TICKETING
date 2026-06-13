<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\HomeGalleryController as AdminHomeGalleryController;
use App\Http\Controllers\Admin\QuotaController as AdminQuotaController;
use App\Http\Controllers\Admin\TourGuideController as AdminTourGuideController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AtupAtupController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\OpeningCeremonyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/opening', [OpeningCeremonyController::class, 'index'])->name('opening.index');
Route::post('/opening/complete', [OpeningCeremonyController::class, 'complete'])->name('opening.complete');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/atup-atup', [AtupAtupController::class, 'overview'])->name('atup.overview');

Route::get('/docs/view', function (Request $request) {
    $catalog = [
        'NAG ATUP INFORMATION.pdf' => 'Nag Atup information',
        'HEALTH DECLARATION FORM.pdf' => 'Health declaration form',
        'ACKNOWLEDGEMENT AND WAIVER OF RISK.pdf' => 'Acknowledgement and waiver of risk',
        'NAG-ATUP Visitors Entry Permit.pdf' => 'Nag-Atup visitors entry permit',
    ];

    $file = $request->query('f', '');
    if ($file === '' || ! array_key_exists($file, $catalog)) {
        abort(404);
    }

    $documentUrl = asset('docs/'.rawurlencode($file));

    return view('docs.preview', [
        'file' => $file,
        'pageTitle' => $catalog[$file],
        'documentUrl' => $documentUrl,
    ]);
})->name('docs.view');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:20,1');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/permit', [BookingController::class, 'downloadPermit'])->name('bookings.permit');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/bookings/{booking}/feedback', [BookingController::class, 'storeFeedback'])->name('bookings.feedback.store');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
        Route::patch('/bookings/{booking}', [AdminBookingController::class, 'update'])->name('bookings.update');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
        Route::post('/users/{user}/toggle-admin', [AdminUserController::class, 'toggleAdmin'])->name('users.toggleAdmin');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        Route::get('/tour-guides', [AdminTourGuideController::class, 'index'])->name('tour-guides.index');
        Route::post('/tour-guides', [AdminTourGuideController::class, 'store'])->name('tour-guides.store');
        Route::patch('/tour-guides/{tourGuide}', [AdminTourGuideController::class, 'update'])->name('tour-guides.update');
        Route::delete('/tour-guides/{tourGuide}', [AdminTourGuideController::class, 'destroy'])->name('tour-guides.destroy');

        Route::get('/quotas', [AdminQuotaController::class, 'index'])->name('quotas.index');
        Route::post('/quotas/default', [AdminQuotaController::class, 'updateDefault'])->name('quotas.default');
        Route::post('/quotas/date', [AdminQuotaController::class, 'upsertDate'])->name('quotas.upsert');
        Route::delete('/quotas/date/{quota}', [AdminQuotaController::class, 'destroyDate'])->name('quotas.destroy');

        Route::get('/home-page', [AdminHomeGalleryController::class, 'index'])->name('homePage.index');
        Route::post('/home-page/gallery', [AdminHomeGalleryController::class, 'store'])->name('homePage.gallery.store');
        Route::patch('/home-page/gallery/{slide}', [AdminHomeGalleryController::class, 'update'])->name('homePage.gallery.update');
        Route::delete('/home-page/gallery/{slide}', [AdminHomeGalleryController::class, 'destroy'])->name('homePage.gallery.destroy');
        Route::post('/home-page/hero', [AdminHomeGalleryController::class, 'updateHero'])->name('homePage.hero');
    });
