<?php

use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PlanController;
use Illuminate\Support\Facades\Route;

// Admin routes - require Admin role
Route::middleware(['auth', 'verified', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Notification routes
    Route::get('notifications', [NotificationController::class, 'overview'])->name('notifications.overview');
    Route::get('notifications/overview', [NotificationController::class, 'overview'])->name('notifications.overview.alt');
    Route::get('notifications/list', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/api', [NotificationController::class, 'api'])->name('notifications.api');
    Route::get('notifications/global', [NotificationController::class, 'global'])->name('notifications.global');
    Route::get('notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
    Route::post('notifications/send', [NotificationController::class, 'send'])->name('notifications.send');
    Route::post('notifications/bulk-action', [NotificationController::class, 'bulkAction'])->name('notifications.bulk-action');
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');

    // User management routes
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/datatable', [UserController::class, 'datatable'])->name('users.datatable');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::post('/users/{user}/verify', [UserController::class, 'verify'])->name('users.verify');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
    // System Health Monitor
    Route::get('/system-health', function () {
        return view('admin.system-health');
    })->name('system.health');
    
    // Management routes with DataTables
    Route::resource('orders', OrderController::class);
    Route::get('orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('orders/datatable', [OrderController::class, 'datatable'])->name('orders.datatable');
    
    Route::resource('plans', PlanController::class);
    Route::patch('plans/{plan}/toggle-status', [PlanController::class, 'toggleStatus'])->name('plans.toggle-status');
    Route::get('plans/datatable', [PlanController::class, 'datatable'])->name('plans.datatable');
    
    // Legacy Livewire routes (keeping for backwards compatibility)
    Route::get('/manage/plans', [PlanController::class, 'index'])->name('manage.plans');
    Route::get('/manage/orders', [OrderController::class, 'index'])->name('manage.orders');
    
    Route::get('/manage/users', function () {
        return view('admin.manage.users');
    })->name('manage.users');
    
    Route::get('/manage/servers', function () {
        return view('admin.manage.servers');
    })->name('manage.servers');
    
    Route::get('/manage/billing', function () {
        return view('admin.manage.billing');
    })->name('manage.billing');
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
