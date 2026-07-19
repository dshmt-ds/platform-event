<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\RegistrationController;

Route::get('/', [LandingPageController::class, 'index'])->name('landing');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/event/{id}/checkout', [RegistrationController::class, 'checkout'])->name('registrations.checkout');
    
    Route::post('/event/payment-store', [RegistrationController::class, 'storePayment'])->name('registrations.payment_store');

    Route::get('/history', [RegistrationController::class, 'history'])->name('registrations.history');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('pages/dashboard');
    })->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('events', EventController::class);
    Route::resource('instructors', InstructorController::class);

    Route::get('/admin/registrations', [RegistrationController::class, 'adminIndex'])->name('admin.registrations.index');
    Route::post('/admin/registrations/{id}/verify', [RegistrationController::class, 'verify'])->name('admin.registrations.verify');
});

require __DIR__.'/auth.php';
