<?php

use App\Http\Controllers\Panel\CustomerController;
use App\Http\Controllers\Panel\LocalController;
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
        // module locals
        Route::resource('locals', LocalController::class)->except(['create', 'edit']);
        Route::get('list-locals', [LocalController::class, 'listLocals'])->name('list-locals');
        // end module locals
        // module customers
        Route::resource('customers', CustomerController::class)->except(['create', 'edit']);
        Route::get('list-customers', [CustomerController::class, 'listCustomers'])->name('list-customers');
        // end module customers
    });
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
