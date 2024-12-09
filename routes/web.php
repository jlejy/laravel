<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\abcController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

//Route:post('/register', [RegisteredUserController::class, 'store']);
//Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
//    ->middleware(['signed', 'throttle:6,1'])
//    ->name('verification.verify');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/aa', [abcController::class, 'index']);

Route::get('hello', function() {
    return response('Hello World'); //helper = 함수입니다.dd
});

require __DIR__.'/auth.php';
