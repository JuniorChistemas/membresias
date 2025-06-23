<?php

use App\Http\Controllers\Panel\CustomerController;
use App\Http\Controllers\Panel\LocalController;
use App\Http\Controllers\Panel\UserController;
use App\Http\Controllers\Panel\TypeMembershipController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Reportes\LocalPDFController;
use App\Http\Controllers\Reportes\CustomerPDFController;
use App\Http\Controllers\Reportes\TypeMembershipPDFController;

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

        // module type memberships
        Route::resource('typeMemberships', TypeMembershipController::class)->except(['create', 'edit']);
        Route::get('list-typeMemberships', [TypeMembershipController::class, 'listTypeMemberships'])->name('list-typeMemberships');

        Route::prefix('reports')->name('reports.')->group(function(){

            #Exports to Excel
            Route::get('/export-excel-locals', [LocalController::class, 'exportExcel'])->name('locals.excel');
            Route::get('/export-excel-customers', [CustomerController::class, 'exportExcel'])->name('customers.excel');
            Route::get('/export-excel-typeMemberships', [TypeMembershipController::class, 'exportExcel'])->name('typeMemberships.excel');

            #Excel imports
            Route::post('/import-excel-locals', [LocalController::class, 'importExcel'])->name('locals.import');
            Route::post('/import-excel-customers', [CustomerController::class, 'importExcel'])->name('customers.import');
            Route::post('/import-excel-typeMemberships', [TypeMembershipController::class, 'importExcel'])->name('typeMemberships.import');

            #Exports to PDF
            Route::get('/export-pdf-locals', [LocalPDFController::class, 'exportPDF']);
            Route::get('/export-pdf-customers', [CustomerPDFController::class, 'exportPDF'])->name('customers.pdf');
            Route::get('/export-pdf-typeMemberships', [TypeMembershipPDFController::class, 'exportPDF'])->name('typeMemberships.pdf');
        });
    });
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
