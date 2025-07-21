<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NotificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::get('/new', function () {
    return view('welcome-new');
});

Route::get('/dashboard', function () {
    // Always show the user dashboard - let users choose to go to admin panel manually
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
    
    // Order Management
    Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/manage/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('manage.orders');
    Route::get('/orders/datatable', [App\Http\Controllers\Admin\OrderController::class, 'datatable'])->name('orders.datatable');
    Route::get('/orders/create', [App\Http\Controllers\Admin\OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [App\Http\Controllers\Admin\OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/edit', [App\Http\Controllers\Admin\OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('orders.destroy');
    Route::post('/orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    
    // User Management  
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/datatable', [UserController::class, 'datatable'])->name('users.datatable');
    Route::get('/users/list', [UserController::class, 'list'])->name('users.list');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::post('/users/{user}/verify', [UserController::class, 'verify'])->name('users.verify');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
    // Plan Management
    Route::get('/plans', [App\Http\Controllers\Admin\PlanController::class, 'index'])->name('plans.index');
    Route::get('/manage/plans', [App\Http\Controllers\Admin\PlanController::class, 'index'])->name('manage.plans');
    Route::get('/plans/datatable', [App\Http\Controllers\Admin\PlanController::class, 'datatable'])->name('plans.datatable');
    Route::get('/plans/list', [App\Http\Controllers\Admin\PlanController::class, 'list'])->name('plans.list');
    Route::get('/plans/create', [App\Http\Controllers\Admin\PlanController::class, 'create'])->name('plans.create');
    Route::post('/plans', [App\Http\Controllers\Admin\PlanController::class, 'store'])->name('plans.store');
    Route::get('/plans/{plan}', [App\Http\Controllers\Admin\PlanController::class, 'show'])->name('plans.show');
    Route::get('/plans/{plan}/edit', [App\Http\Controllers\Admin\PlanController::class, 'edit'])->name('plans.edit');
    Route::put('/plans/{plan}', [App\Http\Controllers\Admin\PlanController::class, 'update'])->name('plans.update');
    Route::delete('/plans/{plan}', [App\Http\Controllers\Admin\PlanController::class, 'destroy'])->name('plans.destroy');
    Route::post('/plans/{plan}/toggle-status', [App\Http\Controllers\Admin\PlanController::class, 'toggleStatus'])->name('plans.toggleStatus');
    
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
