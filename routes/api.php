<?php

use App\Http\Controllers\Panel\CustomerController;
use App\Http\Controllers\Panel\LocalController;
use App\Http\Controllers\Panel\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// show user
Route::get('/users/{user}', [UserController::class, 'show'])->name('api.user.show');
// create user
Route::post('/users', [UserController::class, 'store'])->name('api.user.store');
// delete user
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('api.user.destroy');
// update user
Route::put('/users/{user}', [UserController::class, 'update'])->name('api.user.update');



# list customers
Route::get(('/customers/list'), [CustomerController::class, 'listCustomers'])->name('api.customer.list');
# crate customer
Route::post('/customers', [CustomerController::class, 'store'])->name('api.customer.store');
# show customer
Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('api.customer.show');
# update customer
Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('api.customer.update');
# delete customer
Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('api.customer.destroy');


# list locals 
ROute::get(('/locals/list'), [LocalController::class, 'listLocals'])->name('api.local.list');
# create local
Route::post('/locals', [LocalController::class, 'store'])->name('api.local.store');
# show local
Route::get('/locals/{local}', [LocalController::class, 'show'])->name('api.local.show');
# update local
Route::put('/locals/{local}', [LocalController::class, 'update'])->name('api.local.update');
# delete local
Route::delete('/locals/{local}', [LocalController::class, 'destroy'])->name('api.local.destroy');
