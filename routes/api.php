<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Plan;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API routes for admin functionality
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/users', function () {
        return User::select('id', 'name', 'email')->orderBy('name')->get();
    });
    
    Route::get('/plans', function () {
        return Plan::select('id', 'name', 'price', 'billing_cycle')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    });
});
