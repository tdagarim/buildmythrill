<?php

use App\Http\Controllers\WaitlistSignupController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::post('waitlist', [WaitlistSignupController::class, 'store'])->name('waitlist.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
