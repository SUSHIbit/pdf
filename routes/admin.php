<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use Illuminate\Support\Facades\Route;

// Admin Authentication Routes
Route::middleware('admin.guest')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login']);
});

// Admin Protected Routes
Route::middleware('admin.auth')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
    Route::post('/users/{user}/add-credits', [AdminUserController::class, 'addCredits'])->name('admin.users.add-credits');
    Route::put('/users/{user}/update-credits', [AdminUserController::class, 'updateCredits'])->name('admin.users.update-credits');
});