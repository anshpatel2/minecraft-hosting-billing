<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// Admin routes - require Admin role
Route::middleware(['auth', 'verified', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // User management routes
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/datatable', [UserController::class, 'datatable'])->name('users.datatable');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::post('/users/{user}/verify', [UserController::class, 'verify'])->name('users.verify');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// Reseller routes - require Reseller or Admin role
Route::middleware(['auth', 'verified', 'role:Reseller,Admin'])->prefix('reseller')->name('reseller.')->group(function () {
    Route::get('/dashboard', function () {
        return view('reseller.dashboard');
    })->name('dashboard');
    
    Route::get('/customers', function () {
        return view('reseller.customers');
    })->name('customers');
});

// Customer routes - available to all authenticated users
Route::middleware(['auth', 'verified'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/servers', function () {
        return view('customer.servers');
    })->name('servers');
    
    Route::get('/billing', function () {
        return view('customer.billing');
    })->name('billing');
});
