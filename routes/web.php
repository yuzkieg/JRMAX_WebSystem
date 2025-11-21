<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Controllers\AdminController;

// Public homepage
Route::get('/', fn() => view('webdefault'));

// Login routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/layout', fn() => view('superadmin.layout'));

// Superadmin routes (protected)
Route::middleware(['web', 'superadmin'])->group(function () {
    // Fixed: Using index method instead of dashboard
    Route::get('/superadmin/dashboard', [AdminController::class, 'index'])
         ->name('superadmin.dashboard');

    // Admin management routes
    Route::post('/superadmin/admins', [AdminController::class, 'store'])
        ->name('superadmin.admins.store');
    
    Route::put('/superadmin/admins/{id}', [AdminController::class, 'update'])
        ->name('superadmin.admins.update');
    
    Route::delete('/superadmin/admins/{id}', [AdminController::class, 'destroy'])
        ->name('superadmin.admins.destroy');
});