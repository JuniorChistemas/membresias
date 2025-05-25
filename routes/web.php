<?php

use App\Http\Controllers\Panel\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

# route group for panel
Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('panel')->name('panel.')->group(function () {
        // module users
        Route::resource('users', UserController::class);
        Route::put('users/update/{user}', [UserController::class, 'updateUser'])->name('users.updateUser');
        Route::get('list-users', [UserController::class, 'listUsers'])->name('list-users');
        // end module users
    });
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
