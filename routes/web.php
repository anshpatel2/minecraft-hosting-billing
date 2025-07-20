<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'verified', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/datatable', [UserController::class, 'datatable'])->name('users.datatable');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::post('/users/{user}/verify', [UserController::class, 'verify'])->name('users.verify');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/overview', [NotificationController::class, 'overview'])->name('notifications.overview');
    Route::get('/notifications/global', [NotificationController::class, 'globalNotifications'])->name('notifications.global');
    Route::get('/notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications/send', [NotificationController::class, 'send'])->name('notifications.send');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::delete('/notifications/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');
    Route::post('/notifications/bulk-action', [NotificationController::class, 'bulkAction'])->name('notifications.bulkAction');
});

// Reseller Routes  
Route::middleware(['auth', 'verified', 'role:Reseller,Admin'])->prefix('reseller')->name('reseller.')->group(function () {
    Route::get('/dashboard', function () {
        return view('reseller.dashboard');
    })->name('dashboard');
    
    Route::get('/customers', function () {
        return view('reseller.customers');
    })->name('customers');
});

// Customer Routes
Route::middleware(['auth', 'verified', 'role:Customer,Reseller,Admin'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/servers', function () {
        return view('customer.servers');
    })->name('servers');
    
    Route::get('/billing', function () {
        return view('customer.billing');
    })->name('billing');
});

require __DIR__.'/auth.php';
require __DIR__.'/auth.php';
