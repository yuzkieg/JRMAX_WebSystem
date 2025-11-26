<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\BookingOfficerController;




// Public homepage
Route::get('/', fn() => view('webdefault'));

// Login routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/layout', fn() => view('superadmin.layout'));

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

Route::middleware(['web', 'superadmin'])->group(function () {
    Route::get('/superadmin/dashboard', [AdminController::class, 'index'])
         ->name('superadmin.dashboard');

    Route::post('/superadmin/admins', [AdminController::class, 'store'])
        ->name('superadmin.admins.store');
    
    Route::put('/superadmin/admins/{id}', [AdminController::class, 'update'])
        ->name('superadmin.admins.update');
    
    Route::delete('/superadmin/admins/{id}', [AdminController::class, 'destroy'])
        ->name('superadmin.admins.destroy');
});

Route::middleware(['web', 'admin'])->group(function () {

    Route::get('/admin/adminanalysis', [AdminDashboardController::class, 'index'])
        ->name('admin.adminanalysis');

    Route::get('/admin/users', [EmployeeController::class, 'usermanagement'])
        ->name('admin.users');

    Route::get('/admin/adminhr', fn() => view('admin.adminhr'));
    Route::get('/admin/vehicles', fn() => view('admin.vehicles'));


 

         // Employee CRUD
    Route::post('/admin/employees', [EmployeeController::class, 'store'])->name('admin.employees.store');
    Route::put('/admin/employees/{id}', [EmployeeController::class, 'update'])->name('admin.employees.update');
    Route::delete('/admin/employees/{id}', [EmployeeController::class, 'destroy'])->name('admin.employees.destroy');

});

Route::middleware(['web', 'fleet_assistant'])->group(function () {

    Route::get('/employee/booking/bookingdash', [BookingOfficerController::class, 'index'])
        ->name('employee.booking.bookingdash');

});

Route::middleware(['web', 'booking_officer'])->group(function () {

    Route::get('/employee/fleet/fleetdash', [FleetController::class, 'index'])
        ->name('employee.fleet.fleetdash');

});

Route::middleware(['web', 'user'])->group(function () {

    Route::get('/user/dashboard', [UserController::class, 'index'])
        ->name('user.dashboard');

});